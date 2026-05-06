# Workflows ANGARA — synthèse opérationnelle

> Source de vérité métier : `prompt.txt` (lignes 110-227).
> Implémentation : `app/Models/Dossier.php`, `app/Models/Entreprise.php`, `app/Services/*`, `app/Http/Controllers/*`, `routes/web.php`, `resources/views/*`.

Ce document synthétise les deux grands workflows ANGARA et les **règles transverses** qui s'y appliquent. Il sert de référence rapide pour le développement, les tests, l'audit et la documentation utilisateur.

---

## 1. Règles transverses (s'appliquent partout)

### 1.1. Soumission = verrouillage
> *« Une fois le dossier soumis au maillon suivant, il devient impossible de faire des modifications par l'acteur de l'étape précédente. »*

À chaque étape de chaque workflow :
- Une **soumission** enregistre **date, heure et identité** de l'auteur.
- Tant que le maillon suivant n'a pas rendu sa décision, l'acteur précédent **ne peut plus modifier** son contenu (sauf réouverture explicite par rejet motivé).

### 1.2. Rejet = réouverture
> *« En cas de rejet à une étape donnée, l'acteur de l'étape précédente peut à nouveau modifier son contenu et le transmettre à nouveau. »*

Tout rejet :
- Enregistre **date, heure, identité du rejeteur** et **motif obligatoire**.
- **Réouvre** la saisie côté acteur précédent (sans effacer l'historique : l'événement de rejet reste dans la timeline).
- Notifie l'acteur concerné par e-mail (motif inclus).

### 1.3. Délégation de pouvoir (clôture du dossier d'instruction)
> *« L'accord ou le rejet du dossier peut être fait par des responsables intermédiaires avant le DG ou le DGA, en fonction des montants définis dans la grille de délégation de pouvoir. »*

- Configurable par l'admin : `Admin → Délégation de pouvoir (instruction)`.
- Règle : **seuil d'engagements sollicités max → profil habilité**.
- Le **DG et le DGA conservent toujours** le pouvoir de clôture en dernier ressort, **en parallèle** de tout profil délégué.
- La **note de clôture est obligatoire** pour toute clôture (validation comme rejet).

### 1.4. Vue unique
> *« Les acteurs ont une vue unique de la fiche client / du dossier d'instruction, mais leurs actions diffèrent selon leurs responsabilités. »*

Les vues `entreprise/show` et `dossier/show` sont **partagées** entre tous les profils habilités. Seules les **actions** (CRUD, validation, soumission, etc.) sont conditionnées par le rôle.

---

## 2. Workflow 1 — Entrée en relation (prospect → client)

Tout client commence sa vie comme **prospect** créé par le **gestionnaire**.

### 2.1. Création du prospect (Gestionnaire)
- Acteur : `gestionnaire` (`role_gestionnaire`)
- Vue : `Gestionnaire/Companies/create_prospect`
- Action : `POST /gestionnaire/companies/store-prospect` (`Gestionnaire/CompanyController@storeProspect`)
- Soumission au circuit d'avis : `POST .../submit-prospect` → `entreprises.prospect_submitted_at = now()`
- À la soumission : notification e-mail parallèle au **responsable juridique** (`role_responsable_juridique`) et au **responsable conformité** (`role_responsable_conformite`).
- **Verrouillage** : après soumission, le gestionnaire ne peut plus modifier le prospect tant que le circuit d'avis n'est pas refermé (validation/rejet par le CA).

### 2.2. Avis parallèles RJU + RCO
- Acteurs : `responsable_juridique`, `responsable_conformite`
- Vues : `Juridique/Prospects/show`, `Conformite/Prospects/show`
- Action : `POST .../prospects/{token}/avis` (`Prospect/ProspectReviewController@store`)
- Champs : `juridique_avis_at`, `juridique_avis_by_user_id`, `conformite_avis_at`, `conformite_avis_by_user_id`
- **Quand les deux avis sont saisis** : notification du chef d'agence pour décision.
- **Verrouillage** : si le prospect est déjà tranché par le CA (`isProspectAvisCircuitClosed()`), les avis ne sont plus modifiables.

### 2.3. Décision du Chef d'agence
- Acteur : `chef_agence` (`role_chef_agence`)
- Vue : `Ca/Companies/show_prospect`
- Actions :
  - **Approuver** : `POST .../prospects/{token}/approve` (`Ca/WorkflowController@prospectApprove`)
    → `entreprises.promu_client_at = now()`, création d'un `DossierEntreeRelation` et d'un `DossierAnalyseCritique` initial.
  - **Rejeter** : `POST .../prospects/{token}/reject` (`Ca/WorkflowController@prospectReject`)
    → `entreprises.prospect_rejected_at = now()`, motif obligatoire (`prospect_rejected_motif`).
- Si **approuvé** : le prospect devient client visible chez le **chef de filière** pour structuration.

### 2.4. Structuration du client (Chef de filière → Chef d'agence)
- Acteur 1 : `chef_filiere` (`role_chef_filiere`)
- Vue : `ChefFiliere/clients/show`
- Action : `POST .../clients/{token}/qualification` (`ChefFiliere/QualificationController@*`) puis soumission au CA
- **Verrouillage** : après soumission au CA, modifications gelées (`$lockedPendingCa`, `$lockedAfterValidation`)
- Acteur 2 : `chef_agence`
- Actions :
  - Valider la structuration : `WorkflowController@approveQualification`
  - Rejeter : `WorkflowController@rejectQualification` → réinitialisation de `programmes_submitted_at`, le CF peut corriger et resoumettre.

### 2.5. Création du dossier d'instruction (Chef de filière → Chef d'agence)
- Une fois la structuration validée, le CF crée un **dossier d'instruction** = client + 1..N programmes (avec budgets `appui_financier` + `appui_non_financier` par programme).
- Saisie obligatoire : `engagements_sollicites_total`, `engagements_en_cours_total` (alimente la délégation de pouvoir).
- Acteur 1 : `chef_filiere` — `POST /chef-filiere/clients/{token}/instruction-bundle` (`ChefFiliere/ClientController@submitInstructionBundle`)
- Acteur 2 : `chef_agence` — valide ou rejette le bundle :
  - Valider : `WorkflowController@approveInstructionBundle` → `instruction_agence_validated_at`, dossier visible chez le **REXP**.
  - Rejeter : `WorkflowController@rejectInstructionBundle` → `instruction_agence_rejected_at` + motif, le CF peut corriger.

---

## 3. Workflow 2 — Instruction du dossier des engagements (5 pôles)

Une fois le dossier validé par le CA, il est transmis au **responsable exploitation** pour entrer dans le circuit des cinq pôles.

### 3.1. Pôle Exploitation
- Acteur 1 : `responsable_exploitation` (REXP)
  - **Affecte** un analyste financier : `POST /respexp/dossiers/{token}/assign-analyste`
  - À tout moment, peut **rejeter** la soumission de l'analyste (motif obligatoire) : `POST /respexp/dossiers/{token}/rejeter-analyste`
- Acteur 2 : `analyste_financier` (AF)
  - **Saisit** la grille de notation, le dossier d'analyse critique, les rubriques d'analyse (Summernote x 7).
  - **Soumet** au REXP : `POST /analyste/dossiers/{token}/soumettre-exploitation` → `exploitation_analyste_transmitted_to_exploitation_at`.
  - **Verrouillage** : après soumission, les rubriques sont gelées sauf rejet par REXP.
- Acteur 1 (REXP) — suite :
  - **Saisit** son avis crédit : `POST .../avis-credit`
  - **Statue** sur le dossier des engagements (accord/rejet) : `POST .../validation-engagements`
  - **Transmet** au pôle juridique : `POST .../soumettre-juridique` → `juridique_instruction_submitted_at`.

### 3.2. Pôle Juridique
- Acteur 1 : `responsable_juridique` (RJU)
  - **Affecte** l'analyste juridique : `POST /juridique/dossiers/{token}/assign-juridique-analyste`
  - **Rejette** la soumission AJ : `POST .../rejeter-analyste` (motif obligatoire)
- Acteur 2 : `analyste_juridique` (AJ)
  - **Saisit** son avis : `POST /analyste-juridique/dossiers/{token}/soumettre-reju`
  - **Verrouillage strict** : avis figé dès la soumission au RJU (sauf rejet).
- Acteur 1 (RJU) — suite :
  - **Saisit** son avis : `POST .../responsable-avis`
  - **Transmet** au pôle engagements : `POST .../soumettre-engagements` → `juridique_submitted_to_engagements_at`.

### 3.3. Pôle Engagements
- Acteur 1 : `responsable_engagements` (RENG)
  - **Affecte** l'analyste crédit : `POST /reng/dossiers/{token}/assign-analyste-credit`
  - **Rejette** la soumission AC : `POST .../rejeter-analyste-credit` (motif obligatoire)
- Acteur 2 : `analyste_credit` (AC)
  - **Saisit** la contre-analyse + l'avis : `POST /analyste-credit/dossiers/{token}/soumettre-reng`
  - **Verrouillage** : modifications gelées dès soumission (sauf rejet).
- Acteur 1 (RENG) — suite :
  - **Saisit** son avis : `POST .../responsable-avis`
  - **Transmet** au pôle risques : `POST .../soumettre-risques` → `reng_submitted_to_risques_at`.

### 3.4. Pôle Risques
- Acteur 1 : `responsable_risques` (RISQ)
  - **Affecte** l'analyste risques : `POST /rerx/dossiers/{token}/assign-analyste-risques`
  - **Rejette** la soumission AR : `POST .../rejeter-analyste-risques` (motif obligatoire)
- Acteur 2 : `analyste_risques` (AR)
  - **Saisit** l'analyse + l'avis : `POST /analyste-risques/dossiers/{token}/soumettre-rerx`
  - **Verrouillage** : modifications gelées dès soumission (sauf rejet).
- Acteur 1 (RISQ) — suite :
  - **Saisit** son avis : `POST .../responsable-avis`
  - **Transmet** à la direction : `POST .../soumettre-direction` → `rerx_submitted_to_direction_at`.

### 3.5. Direction (DG / DGA / délégué)
- Acteurs habilités : déterminés par `app/Services/InstructionDelegationService::authorizedProfilIdsForDossier($dossier)`
  - DG et DGA **toujours** habilités.
  - + Le profil habilité par la grille `delegation_pouvoirs` quand `engagements_sollicites_total ≤ seuil_engagements_max`.
- Vue : `RoleSpace/dossiers/show` — onglet **« Clôturer le dossier d'instruction »**
- Actions :
  - **Valider (clôturer)** : `POST /instruction/dossiers/{token}/closure/approve` (`InstructionClosureController@approve`)
    → `instruction_closure_validated_at`, `instruction_closure_validated_by_user_id`, **note de clôture obligatoire** (`instruction_closure_note`).
  - **Rejeter** : `POST /instruction/dossiers/{token}/closure/reject` (`InstructionClosureController@reject`)
    → `instruction_closure_rejected_at`, **motif obligatoire** (`instruction_closure_reject_motif`) + note de clôture obligatoire.

---

## 4. Récapitulatif des verrouillages et réouvertures (workflow 2)

| Étape | Soumission par | Verrouillage à | Réouverture par | Colonne `*_rejected_at` |
|---|---|---|---|---|
| Rubriques d'analyse + grille | Analyste financier | Soumission au REXP | Rejet REXP (motif) | `exploitation_analyste_rejected_at` |
| Avis analyste juridique | Analyste juridique | Soumission au RJU | Rejet RJU (motif) | `juridique_analyste_rejected_at` |
| Contre-analyse + avis crédit | Analyste crédit | Soumission au RENG | Rejet RENG (motif) | `reng_analyste_credit_rejected_at` |
| Analyse + avis risques | Analyste risques | Soumission au RISQ | Rejet RISQ (motif) | `rerx_analyste_risques_rejected_at` |
| Avis REXP / RJU / RENG / RISQ | Responsables | Transmission au pôle suivant | (réouverture inter-pôle non implémentée) | — |
| Dossier d'instruction (bundle) | Chef de filière | Soumission au CA | Rejet CA (motif) | `instruction_agence_rejected_at` |
| Clôture du dossier | DG / DGA / délégué | Validation finale | (rejet de clôture = état terminal) | `instruction_closure_rejected_at` |

---

## 5. Localisation des fichiers clés

| Domaine | Fichiers |
|---|---|
| Modèles principaux | `app/Models/Dossier.php`, `app/Models/Entreprise.php`, `app/Models/DelegationPouvoir.php`, `app/Models/User.php` |
| Services workflow | `app/Services/InstructionDelegationService.php`, `app/Services/StructurationClosureService.php`, `app/Services/InstructionDossierConsultationService.php`, `app/Services/InstructionDossierAnalyseCritiqueSyntheseService.php`, `app/Services/WorkflowEmailNotificationService.php` |
| Contrôleurs Workflow 1 | `Gestionnaire/CompanyController`, `Prospect/ProspectReviewController`, `Ca/WorkflowController`, `Ca/CompanyController`, `ChefFiliere/QualificationController`, `ChefFiliere/ClientController` |
| Contrôleurs Workflow 2 | `RoleSpace/PortfolioController` (transverse), `Analyste/DossierController`, `AnalysteJuridique/DossierController`, `AnalysteCredit/DossierController`, `AnalysteRisques/DossierController`, `InstructionClosureController` |
| Vues Workflow 1 | `resources/views/Gestionnaire/Companies/*`, `resources/views/Juridique/Prospects/*`, `resources/views/Conformite/Prospects/*`, `resources/views/Ca/Companies/*` |
| Vues Workflow 2 | `resources/views/RoleSpace/dossiers/*`, `resources/views/RoleSpace/dossiers/partials/*workflow*.blade.php`, `resources/views/RoleSpace/dossiers/partials/respexp_dossier_hub.blade.php` |
| Vues partagées (rejets) | `resources/views/RoleSpace/dossiers/partials/_pole_analyste_reject_modal.blade.php`, `resources/views/RoleSpace/dossiers/partials/_analyste_reject_banner.blade.php` |
| Routes | `routes/web.php` |
| Migrations délégation | `database/migrations/2026_04_24_100000_instruction_delegation_pouvoir.php`, `database/migrations/2026_04_26_100000_add_instruction_closure_fields_to_dossiers.php` |
| Migration rejets analystes | `database/migrations/2026_05_06_100000_add_pole_analyste_reject_columns_to_dossiers.php` |

---

## 6. Diagramme

Voir `docs/WORKFLOW.diagram.md` pour le diagramme Mermaid complet (workflow 1 + workflow 2 + délégation de pouvoir).
