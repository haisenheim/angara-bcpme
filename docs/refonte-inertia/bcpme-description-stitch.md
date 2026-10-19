# ANGARA × BCPME — Description fonctionnelle pour la génération des maquettes (Google Stitch)

> Document source pour les prompts Stitch de la refonte Inertia (Vue 3 + Tailwind) de la branche `bcpme`.
> Partie 1 à 4 : contexte à fournir à Stitch. Partie 5 : prompts prêts à coller, écran par écran.

---

## 1. Le projet

**ANGARA** est une plateforme bancaire de gestion du cycle de financement des PME, éditée par ANGARA SOLUTIONS
et déployée pour la **BCPME** (Banque Camerounaise des Petites et Moyennes Entreprises).

Elle couvre tout le parcours d'une PME cliente :

1. **Entrée en relation** : un prospect est enregistré, reçoit les avis juridique et conformité, puis devient client.
2. **Structuration** : le client est analysé et ses besoins (financiers et non financiers) sont identifiés.
3. **Dossier d'instruction** : le client est rattaché à un ou plusieurs **programmes d'appui** avec un budget par programme.
4. **Instruction des engagements** : le dossier traverse les pôles Exploitation, Juridique, Engagements et Risques,
   puis la Direction générale, qui l'accorde ou le rejette.
5. **Pilotage** : 5 familles de tableaux de bord, des exports et des notifications e-mail à chaque transmission.

Organisation : la banque est découpée en **régions → agences**. Les profils d'agence (gestionnaire, chef d'agence, chef de
filière) ne voient que leur agence, le responsable régional voit sa région, les pôles centraux et la direction voient
tout le portefeuille national.

Langue de l'interface : **français**. Montants en **FCFA** (séparateur de milliers espace, pas de décimales).
Dates au format `jj/mm/aaaa`.

---

## 2. Les profils (24)

| Code | Profil | Périmètre | Famille |
|---|---|---|---|
| GEST | Gestionnaire de portefeuille | Agence | Agence |
| CA | Chef d'agence | Agence | Agence |
| CF | Chef de filière | Agence | Agence |
| RR | Responsable régional | Région | Supervision |
| REXP | Responsable Exploitation | National | Pôle Exploitation |
| AF | Analyste financier | Dossiers affectés | Pôle Exploitation |
| RJU | Responsable Juridique | National | Pôle Juridique |
| AJ | Analyste juridique | Dossiers affectés | Pôle Juridique |
| RENG | Responsable Engagements | National | Pôle Engagements |
| AC | Analyste crédit | Dossiers affectés | Pôle Engagements |
| RISQ | Responsable Risques | National | Pôle Risques |
| AR | Analyste risques | Dossiers affectés | Pôle Risques |
| RCONF | Responsable Conformité | National | Pôle Conformité |
| ACONF | Analyste conformité | National | Pôle Conformité |
| DG | Directeur général | National | Direction |
| DGA | Directeur général adjoint | National | Direction |
| PCA | Président du conseil d'administration | National | Gouvernance (lecture) |
| ADM | Administrateur (membre du conseil) | National | Gouvernance (lecture) |
| RAUD | Responsable Audit interne | National | Contrôle (lecture) |
| RCI | Responsable Contrôle interne | National | Contrôle (lecture) |
| AUD | Auditeur | National | Contrôle (lecture) |
| CTRL | Contrôleur | National | Contrôle (lecture) |
| ADMIN | Administrateur système | Global | Paramétrage |
| IT | Support informatique | Global | Paramétrage |

---

## 3. Les workflows

### Règles transverses à tous les workflows

- **Verrouillage** : dès qu'une étape transmet au maillon suivant, son auteur ne peut plus modifier ce qu'il a produit.
- **Rejet = réouverture** : un rejet à une étape rouvre l'étape précédente ; son auteur corrige et retransmet.
  Le motif de rejet est obligatoire et s'affiche en bandeau sur le dossier rouvert.
- **Notification** : chaque transmission, affectation ou rejet envoie un e-mail au destinataire (expéditeur, événement,
  action attendue, bouton d'accès direct) et alimente le centre de notifications.
- **Traçabilité** : chaque action est horodatée avec son auteur et apparaît dans la chronologie du dossier.

### Workflow 1 — Entrée en relation

**Schéma :** Gestionnaire → (Resp. Juridique ∥ Resp. Conformité) → Chef d'agence → Chef de filière → Chef d'agence

#### Phase 1.1 — Prospect → client

| Étape | Acteur | Action | Statut résultant |
|---|---|---|---|
| 1 | GEST | Crée la fiche prospect (identité, localisation, sites, équipe, tiers, questionnaire, pièces exigibles) | Prospect (brouillon) |
| 2 | GEST | Soumet le prospect pour avis | Prospect — avis en cours |
| 3a | RJU | Donne un avis juridique (en parallèle de 3b) | — |
| 3b | RCONF | Donne un avis conformité (en parallèle de 3a) | — |
| 4 | CA | Une fois les deux avis reçus : **valide** (conversion en client) ou **rejette** avec motif | Client (en attente de structuration) / Prospect rejeté |

#### Phase 1.2 — Structuration et dossier d'instruction

| Étape | Acteur | Action | Statut résultant |
|---|---|---|---|
| 5 | CF | Structure le client : positionnement filière, éligibilité (import-substitution, filières ciblées), besoins produits (financement, accompagnement, structuration) | Structuration en cours |
| 6 | CF | Soumet la structuration | Structuration soumise — en validation agence |
| 7 | CA | Valide ou rejette la structuration (motif) | Structuré / Structuration rejetée (retour CF) |
| 8 | CF | Crée le **dossier d'instruction** : client + un ou plusieurs programmes ; pour chaque programme, budget d'appuis financiers et non financiers ; saisit le **total des engagements sollicités** et le **total des engagements en cours** | Dossier soumis à l'agence |
| 9 | CA | Saisit l'avis agence, valide ou rejette le dossier d'instruction, puis le transmet à l'Exploitation | Au pôle Exploitation |

Statuts client affichés en badge : *En attente de structuration* (gris) · *Structuration en cours* (bleu) ·
*Structuré* (vert) · *Structuration rejetée* (rouge). « Non structuré » est un filtre regroupant les trois premiers états non finaux.

### Workflow 2 — Instruction du dossier des engagements

**Schéma :** Exploitation → Juridique → Engagements → Risques → Direction générale (ou délégataire)

Chaque pôle suit le **même motif en 4 temps** :

1. Le responsable du pôle consulte les dossiers en instance (file « À affecter »).
2. Il **affecte (cote)** le dossier à un analyste de son pôle.
3. L'analyste instruit le dossier, enregistre en brouillon autant que nécessaire, puis **soumet** au responsable.
4. Le responsable lit le travail, saisit **son avis**, puis **valide et transmet** au pôle suivant,
   **rejette vers l'analyste** (réouverture de l'analyse) ou **rejette vers le pôle précédent** (rejet inter-pôle).

| Pôle | Responsable | Analyste | Travail spécifique de l'analyste | Transmet à | Rejet inter-pôle vers |
|---|---|---|---|---|---|
| Exploitation | REXP | AF | Grille de notation corporate, grille de traitement des engagements, analyse critique, chargement des états financiers (DSF), budgets et engagements, avis de crédit | Juridique | — (retour agence) |
| Juridique | RJU | AJ | Analyse juridique, garanties, avis juridique | Engagements | Exploitation |
| Engagements | RENG | AC | Contre-analyse, mise à jour de la grille des engagements, avis crédit et réglementaire | Risques | Juridique |
| Risques | RISQ | AR | Analyse des risques, avis de gestion des risques | Direction | Engagements |
| Direction | DG / DGA | — | Avis et décision finale : **accord** ou **rejet** + note de clôture | Clôture | Risques |

En plus de l'avis, REXP valide la grille des engagements (décision sur les engagements) avant de transmettre au Juridique.

**Délégation de pouvoir.** L'administrateur paramètre une grille « seuil maximal du total des engagements sollicités → profil
habilité à clôturer ». Selon le montant du dossier, la clôture (accord ou rejet) est faite par un responsable de pôle
intermédiaire au lieu du DG ou du DGA. Sans paramétrage, ce sont le DG et le DGA qui clôturent. Toute clôture exige une note et
enregistre l'auteur et l'heure. Les boutons « Accorder » et « Rejeter » n'apparaissent qu'au profil habilité.

**Statut global d'un dossier :** *Instruction en cours* (bleu, avec l'étape courante : « Au pôle juridique »…) ·
*Validé* (vert) · *Rejeté* (rouge, avec le motif) · *Validé et rejeté — parcours mixte* (orange).

### Workflow 3 — Grille des engagements (transverse)

Pour chaque client, une grille de type tableur liste les engagements (ligne par banque ou partenaire : type, montant, encours,
échéance…). **AF et AC saisissent** ; tous les autres acteurs du portefeuille la consultent en lecture seule.
Export Excel disponible.

### Workflow 4 — Pilotage (tableaux de bord)

| Famille | Objectif | Indicateurs | Profils |
|---|---|---|---|
| Opérationnel | Activité quotidienne | Dossiers en cours, en attente de validation, délais de traitement, rejetés | AF, CF, analystes, responsables de pôle |
| Portefeuille | Engagements et clients | Volume de crédits, répartition par filière/secteur, encours par client et par filière | CA, REXP, RENG, RISQ |
| Risques | Expositions | Taux d'impayés, créances en souffrance, dossiers à risque, alertes | RISQ, DG, DGA, conseil |
| Stratégique | Pilotage de la banque | Performance globale, croissance du portefeuille, impact des programmes | DG, DGA, PCA, ADM |
| Programmes | Dispositifs d'appui | Bénéficiaires, montants financés, impact économique, consommation des budgets | Tous les responsables |

Le périmètre des données est restreint automatiquement au profil (agence, région ou national).

---

## 4. Fonctionnalités par profil

> Les fonctions communes à tous les profils connectés : tableau de bord personnel (KPI, « mes tâches », alertes),
> centre de notifications, profil utilisateur (identité, mot de passe), bibliothèque de modèles de documents (téléchargement),
> tableaux de bord de pilotage selon le périmètre, déconnexion.

### Gestionnaire (GEST)
- Lister ses prospects et ses clients (filtres, recherche, statistiques, export Excel).
- Créer et modifier un prospect : identité de l'entreprise (forme, taille, caractère, RCCM, capital, CA, effectif),
  localisation (région → département → arrondissement → village/quartier), secteur et filière.
- Gérer les **sites** de l'entreprise (libellé, localité, GPS, contacts, note) et son **équipe** (nom, téléphone, fonction,
  niveau d'étude, associé oui/non, site d'affectation).
- Enregistrer les tiers liés (personnes physiques et morales, recherche dans le portefeuille).
- Remplir le questionnaire de qualification et les avis sur critères.
- Déposer les **pièces exigibles** (liste paramétrée, statut par pièce).
- Saisir l'analyse critique de l'entreprise.
- **Soumettre le prospect** pour avis juridique et conformité.
- Consulter la fiche client complète, l'exporter en PDF, consulter la grille des engagements.
- Consulter les programmes et les dossiers de son portefeuille.

### Chef d'agence (CA)
- Tableau de bord d'agence : performance de l'équipe, statistiques mensuelles, dossiers récents, alertes.
- Portefeuille de l'agence : prospects, clients et dossiers (lecture, export).
- **File « Prospects à valider »** : fiche et deux avis (juridique et conformité), puis **valider la conversion en client** ou **rejeter** avec motif.
- **File « Structurations à valider »** : structuration du chef de filière, puis **valider** ou **rejeter** avec motif.
- **File « Dossiers d'instruction à valider »** : client et programmes, budgets, totaux d'engagements.
  Il saisit l'**avis agence**, puis **valide et transmet à l'Exploitation** ou **rejette**.
- Consulter la synthèse d'analyse critique (PDF).
- Gérer les utilisateurs de l'agence (activer ou désactiver).
- Consulter les programmes et l'organisation du territoire.

### Chef de filière (CF)
- Lister les clients de l'agence avec leur statut de structuration (export).
- **Structurer** un client : analyse stratégique et opérationnelle, éligibilité, besoins et produits (édition des besoins produits).
- Soumettre la structuration au CA ; la corriger et la resoumettre en cas de rejet.
- **Créer le dossier d'instruction** : choix d'un ou plusieurs programmes, budget d'appuis financiers et non financiers par programme,
  total des engagements sollicités et en cours ; soumission au CA.
- Suivre les instructions « en attente » et « en cours », déposer des pièces au dossier.
- Compléter les pièces exigibles du client et consulter son analyse critique.
- Consulter les programmes (catalogue, budget consommé).

### Responsable régional (RR)
- Tableau de bord régional.
- Consulter les prospects, clients et dossiers de toutes les agences de la région (fiche, export).
- Consulter les programmes, gérer les utilisateurs de la région (activer ou désactiver) et consulter le territoire.

### Responsable Exploitation (REXP)
- File des dossiers d'instruction transmis par les agences.
- **Affecter** un dossier à un analyste financier.
- Consulter l'instruction de l'AF (grilles, analyse critique, synthèse PDF).
- **Rejeter vers l'analyste** (réouverture) avec motif.
- Saisir l'**avis de crédit** et la **décision sur les engagements**.
- **Soumettre au Juridique**.
- Consulter le portefeuille national (entreprises, dossiers, pièces, fiche PDF, exports).

### Analyste financier (AF)
- File « Mes dossiers affectés » avec tableau de bord (performance, délais, alertes).
- Instruire le dossier :
  - grille de notation corporate (critères et choix de réponse) ;
  - chargement des états financiers (DSF) ;
  - analyse critique ;
  - budgets et engagements par programme ;
  - **grille des engagements** (saisie).
- Enregistrer l'avis en brouillon, puis **soumettre au REXP** (verrouillage).
- Gérer les pièces du dossier ; corriger après un rejet.

### Responsable Juridique (RJU)
- **Avis juridique sur les prospects** (workflow 1) : file des prospects soumis, fiche, saisie de l'avis, export.
- File des dossiers d'instruction au pôle juridique : **affecter** à un analyste juridique, lire, saisir **son avis**,
  **soumettre aux Engagements**, **rejeter vers l'analyste** ou **rejeter vers l'Exploitation**.
- Consulter le portefeuille national (prospects, entreprises, dossiers).

### Analyste juridique (AJ)
- File des dossiers affectés et tableau de bord.
- Analyse juridique et des garanties, avis, pièces ; **soumettre au RJU**.

### Responsable Engagements (RENG)
- File du pôle : **affecter** à un analyste crédit, lire la contre-analyse, saisir **son avis**,
  **soumettre aux Risques**, **rejeter vers l'analyste** ou **rejeter vers le Juridique**.
- Consulter le portefeuille national.

### Analyste crédit (AC)
- File des dossiers affectés et tableau de bord.
- Contre-analyse, **mise à jour de la grille des engagements** (saisie), avis crédit et réglementaire, brouillon ;
  **soumettre au RENG**.

### Responsable Risques (RISQ)
- File du pôle : **affecter** à un analyste risques, lire, saisir **son avis**, **soumettre à la Direction**,
  **rejeter vers l'analyste** ou **rejeter vers les Engagements**.
- Tableaux de bord risques, portefeuille national.

### Analyste risques (AR)
- File des dossiers affectés et tableau de bord.
- Analyse des risques et avis, brouillon ; **soumettre au RISQ**.

### Responsable Conformité (RCONF)
- **Avis conformité sur les prospects** (workflow 1) : file des prospects ouverts, fiche, saisie de l'avis,
  historique des dossiers traités, export.
- Consulter le portefeuille national (prospects, entreprises, dossiers).

### Analyste conformité (ACONF)
- Tableau de bord conformité. Ce profil est prévu pour appuyer le RCONF : sa file de travail reste à définir.

### Directeur général (DG) et Directeur général adjoint (DGA)
- Tableau de bord stratégique et risques, avec la file « Dossiers en attente de la direction » et « Dossiers validés par les chefs d'agence ».
- Consulter tout le dossier : instruction, avis de chaque pôle, analyse critique, synthèse PDF.
- **Clôturer le dossier** : **accorder** ou **rejeter** avec une note de clôture, ou **renvoyer au pôle Risques**.
- Consulter les prospects, entreprises, dossiers et programmes du portefeuille national.
- Gérer les utilisateurs (activer ou désactiver).
- Les DG et DGA peuvent aussi valider les dossiers d'instruction au même titre que le CA.

### Responsable délégataire (selon la délégation de pouvoir)
- Tout responsable de pôle désigné dans la grille de délégation voit les actions **Accorder** et **Rejeter**
  avec une note de clôture sur les dossiers dont le total des engagements sollicités est inférieur ou égal à son seuil.

### PCA, Administrateur, Responsable Audit, Responsable Contrôle interne, Auditeur, Contrôleur
- Accès **en lecture seule** à un espace de gouvernance commun :
  - tableau de bord (statistiques, indicateurs, tâches) ;
  - entreprises ;
  - dossiers ;
  - fiche entreprise avec export PDF ;
  - pièces ;
  - exports Excel.
- Les tableaux de bord stratégique et risques sont réservés au PCA et à l'administrateur.

### Administrateur système (ADMIN)
- **Utilisateurs** : création, profil, agence, activation et désactivation.
- **Organisation** : régions, agences (activation), territoire (régions → départements → arrondissements → villages).
- **Référentiels** :
  - secteurs et filières ;
  - organismes partenaires ;
  - banques ;
  - **pièces exigibles** (définitions) ;
  - types de fichiers ;
  - **modèles de documents** (dépôt) ;
  - questionnaire et critères de notation.
- **Programmes d'appui** : création et modification. Chaque programme comprend :
  - des composantes ;
  - des appuis financiers et non financiers ;
  - des produits ;
  - des indicateurs et résultats ;
  - des organismes et un budget.
- **Délégation de pouvoir** : tableau « seuil maximal → profil habilité ».
- Accès complet au portefeuille (entreprises, prospects, dossiers) pour le support.

---

## 5. Prompts Stitch

### 5.0 Prompt de contexte (à envoyer en premier, ou à préfixer à chaque écran)

```
Application web bancaire B2B « ANGARA » pour la BCPME (banque camerounaise des PME). Interface en français,
desktop-first (1440 px), responsive tablette. Montants en FCFA (« 15 000 000 FCFA »), dates jj/mm/aaaa.

Design system :
- Identité actuelle BCPME « banque sobre » (design system Stitch « BCPME — Banque sobre ») : boutons principaux
  anthracite #111827 ; sidebar vert foncé #506926 (élément actif en pastille blanche, icône verte #88B824) ; accent vert
  logo #91BD47 en micro-détails ; rouge logo #D0362E réservé aux rejets, risques et alertes ; topbar blanche ; fond
  #F7F8FA, bordures #E6E8EC, texte #111827. Aucun bordeaux.
- Police Inter. Cartes rayon 12 px, boutons 10 px. Ombres très légères. Densité moyenne : application métier, beaucoup de tableaux.
- Badges de statut : gris = brouillon/en attente, bleu = en cours, vert = validé, rouge = rejeté, orange = mixte/alerte.

Layout unique pour tous les profils :
- Sidebar gauche repliable (logo ANGARA, menu dont les entrées dépendent du profil, groupées par section :
  Pilotage, Portefeuille, Workflow, Programmes, Administration).
- Topbar : fil d'Ariane, recherche globale, cloche de notifications avec compteur, sélecteur d'agence (si multi-agences),
  avatar avec nom + libellé du profil (ex. « Chef d'agence — Agence Yaoundé »).
- Zone de contenu : titre de page + actions principales à droite, puis contenu.

Composants réutilisables à garder identiques sur tous les écrans : cartes KPI, tableau de données (recherche, filtres
en chips, tri, pagination serveur, menu d'actions « ⋯ » par ligne, export Excel), fiche à onglets, timeline de
workflow (stepper horizontal des étapes avec étape courante en surbrillance), panneau d'actions de workflow (sticky à
droite), modale d'affectation à un analyste, modale de rejet avec motif obligatoire, bandeau de rejet (motif + auteur
+ date), zone de dépôt de pièces avec statut par pièce.
```

### 5.1 Connexion
```
Écran de connexion ANGARA. Split-screen : à gauche visuel institutionnel (PME camerounaises, agriculture et
transformation, overlay vert foncé #506926) avec la promesse « Financer et accompagner les PME » ; à droite carte de
connexion : logo, champs e-mail et mot de passe, « Se souvenir de moi », lien « Mot de passe oublié », bouton primaire
« Se connecter ». Pied : « BCPME — propulsé par ANGARA SOLUTIONS ».
```

### 5.2 Tableau de bord (gabarit commun, contenu selon le profil)
```
Tableau de bord du profil « Chef d'agence — Agence Yaoundé ». Ligne de 4 cartes KPI (Prospects à valider 6,
Structurations à valider 3, Dossiers d'instruction à valider 2, Clients actifs 148) avec variation vs mois précédent.
Bloc « Mes tâches » : liste priorisée d'actions attendues (icône, libellé, entreprise, ancienneté, bouton « Traiter »).
Graphique barres « Dossiers par étape du workflow » et donut « Répartition par filière ». Tableau « Performance de
l'équipe » (gestionnaire, prospects créés, convertis, délai moyen). Encart « Alertes » (dossiers en retard > 15 j).
Le même gabarit doit pouvoir afficher d'autres jeux de widgets (analyste : mes dossiers affectés, délais ; direction :
encours, croissance du portefeuille, taux d'impayés).
```

### 5.3 Liste du portefeuille (prospects / clients / dossiers — composant unique)
```
Page « Prospects » du gestionnaire. En-tête : titre, compteur, boutons « Exporter » et primaire « Nouveau prospect ».
Ligne de 4 cartes statistiques (Brouillons, Avis en cours, Convertis ce mois, Rejetés). Barre de filtres : recherche,
selects Secteur, Taille, Caractère (formel/informel), Statut, Agence ; filtres actifs en chips effaçables. Tableau :
Désignation (nom + RCCM en sous-texte), Secteur, Localité, Gestionnaire, Date de création, Statut (badge), menu « ⋯ »
(Ouvrir, Modifier, Soumettre pour avis, Fiche PDF). Pagination serveur 25/50/100. Préciser visuellement que la même
page sert pour « Clients » (colonne Statut de structuration) et « Dossiers d'instruction » (colonnes Client, Programmes,
Montant sollicité, Étape courante, Statut) et que le bouton de création n'apparaît que pour les profils autorisés.
```

### 5.4 Formulaire prospect (création / modification)
```
Formulaire multi-étapes « Nouveau prospect » avec stepper vertical à gauche : 1 Identité (raison sociale, forme
juridique, taille, caractère, RCCM, date de création, capital, chiffre d'affaires, effectif, e-mail, téléphone,
dirigeant), 2 Localisation (Région → Département → Arrondissement → Village/Quartier en selects dépendants), 3 Activité
(secteur, filière, titre et description du projet, description de l'activité), 4 Tiers (personnes physiques et
morales), 5 Questionnaire, 6 Pièces exigibles. Barre basse sticky : « Enregistrer le brouillon », primaire « Continuer ».
```

### 5.5 Fiche entreprise (prospect / client — composant unique à onglets)
```
Fiche entreprise « Agro Transfo SARL ». En-tête : avatar initiales, nom, RCCM, badges (Client, Structuré), agence,
gestionnaire, boutons « Fiche PDF » et actions de workflow selon le profil. Stepper horizontal du parcours : Prospect →
Avis juridique/conformité → Client → Structuration → Dossier d'instruction → Instruction → Décision. Onglets : Synthèse
(chiffres clés, contacts, carte des sites), Sites (cartes avec localité, GPS, contacts, bouton « Ajouter un site »),
Équipe (tableau : nom, fonction, âge, niveau d'étude, associé, site), Tiers, Pièces exigibles (liste avec statut
déposé/manquant/validé et zone de dépôt), Questionnaire & critères, Analyse critique, Avis (juridique et conformité,
auteur, date, texte), Engagements (grille), Dossiers (liste des dossiers d'instruction), Historique (timeline).
```

### 5.6 Avis juridique / conformité sur un prospect
```
Écran « Avis conformité — Prospect Agro Transfo SARL » pour le Responsable Conformité. Gauche (2/3) : résumé du
prospect en lecture (identité, dirigeants, tiers, pièces avec aperçu). Droite (1/3) : panneau sticky « Mon avis » :
choix Favorable / Réservé / Défavorable, zone de texte riche, rappel de l'état de l'autre avis (« Avis juridique :
reçu le 12/10/2026 — Favorable »), bouton primaire « Enregistrer l'avis ». Au-dessus, onglets de file : « À traiter (8) »
et « Traités ».
```

### 5.7 Validation chef d'agence (prospect, structuration, dossier d'instruction — même gabarit)
```
Écran « Validation — Conversion en client » du chef d'agence. Fiche condensée du prospect, deux cartes d'avis côte à côte
(Juridique / Conformité, avec badge et texte), panneau de décision : boutons vert « Valider la conversion en client » et
contour rouge « Rejeter », la modale de rejet exige un motif. Variantes du même gabarit : « Validation de la
structuration » (affiche l'analyse du chef de filière et les besoins identifiés) et « Validation du dossier
d'instruction » (affiche les programmes choisis, budgets, totaux d'engagements, champ « Avis agence », bouton
« Valider et transmettre à l'Exploitation »).
```

### 5.8 Structuration du client (chef de filière)
```
Écran « Structuration — Agro Transfo SARL ». Sections en cartes : Positionnement filière (filière, maillon de la
chaîne de valeur), Éligibilité (cases : import-substitution, filière ciblée, profil ciblé, avec commentaires),
Besoins identifiés (tableau éditable : type Financement / Accompagnement / Structuration, produit, description, montant
estimé), Synthèse. Si la structuration a été rejetée : bandeau rouge en haut avec motif, auteur et date. Actions :
« Enregistrer », primaire « Soumettre au chef d'agence ».
```

### 5.9 Création du dossier d'instruction (chef de filière)
```
Écran « Nouveau dossier d'instruction » pour un client structuré. Étape 1 : sélection d'un ou plusieurs programmes
d'appui (cartes avec nom, organisme, budget restant, filières éligibles). Étape 2 : pour chaque programme retenu, un
bloc avec appuis financiers (produit, montant) et appuis non financiers (service, coût). Étape 3 : totaux — total des
engagements sollicités, total des engagements en cours — et récapitulatif. Résumé latéral sticky des montants
cumulés. Bouton primaire « Soumettre au chef d'agence ».
```

### 5.10 Fiche dossier d'instruction (composant unique, actions selon le profil et l'étape)
```
Fiche « Dossier d'instruction DI-2026-0042 — Agro Transfo SARL ». En-tête : client, agence, montant sollicité
25 000 000 FCFA (Dette 15 M + Equity 10 M), badge statut « Instruction en cours — Au pôle Engagements ». Stepper
horizontal : Agence → Exploitation → Juridique → Engagements → Risques → Direction → Clôture, avec pour chaque étape
l'acteur et la date. Onglets : Synthèse, Programmes & budgets, Grille de notation, Analyse critique, Grille des
engagements, Avis des pôles (une carte par pôle : avis analyste + avis responsable, auteur, date, verrouillé), Pièces,
Historique. Panneau d'actions sticky à droite, contenu selon le profil connecté — exemple Responsable Engagements :
« Affecter à un analyste crédit », « Saisir mon avis », primaire « Soumettre aux Risques », menu « Rejeter » (vers
l'analyste / vers le pôle Juridique). Bandeau rouge de rejet si le dossier a été renvoyé.
```

### 5.11 Espace de travail analyste (AF / AJ / AC / AR — gabarit unique)
```
Écran « Instruction — Analyste financier » sur un dossier affecté. Layout 2 colonnes : gauche, navigation des
livrables à compléter avec indicateur de progression (Grille de notation 80 %, États financiers DSF ✓, Analyse critique
en cours, Budgets & engagements, Grille des engagements, Avis) ; droite, l'éditeur du livrable sélectionné — ici la
grille de notation corporate : critères groupés par axe, choix de réponse en boutons segmentés, score calculé en direct
et note globale. Barre basse sticky : « Enregistrer le brouillon », primaire « Soumettre au Responsable Exploitation »
(confirmation : « Après soumission vous ne pourrez plus modifier »). Variantes AJ / AC / AR : mêmes zones, livrables
propres au pôle.
```

### 5.12 Grille des engagements
```
Grille éditable type tableur « Engagements — Agro Transfo SARL » : colonnes Partenaire/Banque, Nature, Montant
autorisé, Encours, Taux, Date de mise en place, Échéance, Garanties, Observations ; ligne de totaux ; ajout de ligne,
suppression, sélection d'un partenaire avec création à la volée. Mode lecture seule (cadenas + mention « Consultation »)
pour les profils non autorisés. Bouton « Exporter Excel ».
```

### 5.13 Modales communes
```
Trois modales au même style : (1) « Affecter à un analyste » — liste des analystes du pôle avec charge actuelle
(nb dossiers en cours), recherche, commentaire optionnel, bouton « Affecter ». (2) « Rejeter » — choix de la cible
(analyste du pôle / pôle précédent), motif obligatoire, bouton rouge « Confirmer le rejet ». (3) « Clôturer le dossier »
— décision Accord / Rejet, note de clôture obligatoire, rappel « Vous clôturez au titre de la délégation de pouvoir
(seuil 50 000 000 FCFA) », bouton « Clôturer ».
```

### 5.14 Décision de la Direction / délégataire
```
Écran « Décision — DI-2026-0042 » pour le Directeur général. Synthèse exécutive en haut : client, montant, programmes,
score de notation, avis de chaque pôle en 4 cartes compactes (Exploitation, Juridique, Engagements, Risques : badge
Favorable/Réservé/Défavorable + extrait), lien vers la synthèse d'analyse critique PDF. Bloc décision : boutons vert
« Accorder », rouge « Rejeter », lien « Renvoyer au pôle Risques », note de clôture obligatoire. Après décision : carte
« Dossier clôturé — Accordé par … le … ».
```

### 5.15 Tableaux de bord de pilotage
```
Module « Pilotage » avec onglets : Opérationnel, Portefeuille, Risques, Stratégique, Programmes. Filtres globaux :
période, région, agence, filière. Exemple onglet Portefeuille : KPI (Volume de crédits, Encours total, Nombre de
clients, Ticket moyen), graphique encours par filière (barres horizontales), répartition par secteur (donut),
top 10 encours par client (tableau), évolution mensuelle (courbe). Onglet Risques : taux d'impayés, créances en
souffrance, dossiers à risque, liste d'alertes. Bouton export PDF/Excel.
```

### 5.16 Programmes d'appui
```
(a) Catalogue des programmes : cartes ou tableau (nom, organisme, période, budget total, budget consommé en barre de
progression, nb bénéficiaires, statut). (b) Fiche programme à onglets : Présentation, Composantes, Appuis financiers,
Appuis non financiers, Produits, Indicateurs & résultats, Organismes, Bénéficiaires (dossiers rattachés),
Consommation budgétaire. (c) Formulaire de création/modification (admin) en étapes.
```

### 5.17 Administration
```
Espace « Administration » avec sous-menu : Utilisateurs (tableau nom, e-mail, profil, agence, statut actif + bascule,
formulaire latéral de création), Organisation (régions et agences en arbre, activation), Territoire (arbre Région →
Département → Arrondissement → Village), Référentiels (secteurs, filières, organismes, banques, types de fichiers —
même gabarit liste + panneau d'édition), Pièces exigibles, Modèles de documents (dépôt et liste), Délégation de
pouvoir (tableau éditable : seuil maximal du total des engagements → profil habilité, ligne par défaut « Au-delà :
DG / DGA »).
```

### 5.18 Notifications
```
Panneau déroulant de notifications depuis la cloche + page « Notifications » : liste groupée par jour, icône par type
(affectation, transmission, rejet, décision), texte « Ibrahim Nana vous a affecté le dossier DI-2026-0042 », lien
d'action « Ouvrir le dossier », état lu/non lu, filtre « Non lues ».
```

---

## 6. Consignes pour la refonte (rappel de l'analyse de factorisation)

- **Un écran = un composant Vue**, paramétré par les droits du profil et non dupliqué par profil :
  - liste portefeuille (5.3) ;
  - fiche entreprise (5.5) ;
  - fiche dossier (5.10) ;
  - espace analyste (5.11) ;
  - validation (5.7) ;
  - tableau de bord (5.2).
- **Le menu et les actions se calculent à partir des permissions** (Spatie). Un layout unique remplace les 18 layouts actuels.
- **Une seule implémentation de tableau** avec pagination serveur.
- Les profils en lecture seule (gouvernance et contrôle) utilisent les mêmes écrans, sans panneau d'actions.
