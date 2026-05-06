# ANGARA — Diagrammes Mermaid des workflows

> Référence métier : `prompt.txt` (lignes 110-227) — implémentation : voir `docs/WORKFLOW.md`.

Ce document contient trois diagrammes complémentaires :

1. **Workflow 1 — Entrée en relation** : prospect → client → structuration → dossier d'instruction.
2. **Workflow 2 — Instruction du dossier** : 5 pôles + clôture par délégation de pouvoir.
3. **Vue d'ensemble** : enchaînement des états macro et points de réouverture.

---

## 1. Workflow 1 — Entrée en relation

```mermaid
flowchart TD
    A[Gestionnaire crée le prospect] -->|submit-prospect| B[Avis parallèles]
    B -->|RJU| B1[Avis juridique]
    B -->|RCO| B2[Avis conformité]
    B1 --> C{Chef d'agence statue}
    B2 --> C
    C -->|Approuver| D[Prospect promu client]
    C -->|Rejeter motif| Rfin[Prospect rejeté fin]:::stop
    D --> E[Chef de filière structure le client]
    E -->|Soumission au CA| F{CA valide la structuration ?}
    F -->|Rejet motif| E
    F -->|Validation| G[Chef de filière crée le dossier d'instruction<br/>client + N programmes + budgets + engagements]
    G -->|Soumission au CA| H{CA valide le bundle ?}
    H -->|Rejet motif| G
    H -->|Validation| I[Dossier visible chez le REXP]:::ok

    classDef ok fill:#d1f7d6,stroke:#2e7d32,color:#1b5e20;
    classDef stop fill:#ffd6d6,stroke:#c62828,color:#7f0000;
```

---

## 2. Workflow 2 — Instruction (5 pôles)

```mermaid
flowchart TD
    Start[Dossier validé par le CA] --> P1
    
    subgraph P1[Pôle Exploitation]
        direction TB
        P1a[REXP affecte AF] --> P1b[AF saisit grille + rubriques]
        P1b -->|Soumission| P1c[REXP statue]
        P1c -->|Rejet motif| P1b
        P1c -->|Avis crédit + accord engagements| P1d[Transmet au juridique]
    end

    subgraph P2[Pôle Juridique]
        direction TB
        P2a[RJU affecte AJ] --> P2b[AJ saisit avis]
        P2b -->|Soumission| P2c[RJU statue]
        P2c -->|Rejet motif| P2b
        P2c -->|Avis RJU| P2d[Transmet aux engagements]
    end

    subgraph P3[Pôle Engagements]
        direction TB
        P3a[RENG affecte AC] --> P3b[AC saisit contre-analyse + avis]
        P3b -->|Soumission| P3c[RENG statue]
        P3c -->|Rejet motif| P3b
        P3c -->|Avis RENG| P3d[Transmet aux risques]
    end

    subgraph P4[Pôle Risques]
        direction TB
        P4a[RISQ affecte AR] --> P4b[AR saisit analyse + avis]
        P4b -->|Soumission| P4c[RISQ statue]
        P4c -->|Rejet motif| P4b
        P4c -->|Avis RISQ| P4d[Transmet à la direction]
    end

    P1d --> P2
    P2d --> P3
    P3d --> P4
    P4d --> P5

    subgraph P5[Direction - Délégation de pouvoir]
        direction TB
        P5a[Profils habilités<br/>DG + DGA + délégué selon seuil] --> P5b{Décision}
        P5b -->|Accord<br/>note de clôture obligatoire| P5c[Dossier clos - accord]:::ok
        P5b -->|Rejet<br/>motif obligatoire| P5d[Dossier clos - rejet]:::ko
    end

    classDef ok fill:#d1f7d6,stroke:#2e7d32,color:#1b5e20;
    classDef ko fill:#ffd6d6,stroke:#c62828,color:#7f0000;
```

---

## 3. Vue d'ensemble — états et réouvertures

```mermaid
stateDiagram-v2
    [*] --> Prospect_brouillon: Gestionnaire
    Prospect_brouillon --> Avis_parallele: submit-prospect
    Avis_parallele --> Decision_CA: 2 avis saisis
    Decision_CA --> Prospect_rejete: Rejet
    Decision_CA --> Client_a_structurer: Approbation
    Prospect_rejete --> [*]
    Client_a_structurer --> Structuration_en_cours: CF
    Structuration_en_cours --> CA_decision_struct: Soumission
    CA_decision_struct --> Structuration_en_cours: Rejet
    CA_decision_struct --> Bundle_a_creer: Validation
    Bundle_a_creer --> Bundle_soumis: CF soumet
    Bundle_soumis --> Bundle_a_creer: Rejet CA
    Bundle_soumis --> Pole_Exploitation: Validation CA

    state "Pôle Exploitation" as Pole_Exploitation {
        [*] --> AF_saisie
        AF_saisie --> REXP_decision: Soumission
        REXP_decision --> AF_saisie: Rejet motif
        REXP_decision --> [*]: Transmission juridique
    }
    Pole_Exploitation --> Pole_Juridique

    state "Pôle Juridique" as Pole_Juridique {
        [*] --> AJ_saisie
        AJ_saisie --> RJU_decision: Soumission
        RJU_decision --> AJ_saisie: Rejet motif
        RJU_decision --> [*]: Transmission engagements
    }
    Pole_Juridique --> Pole_Engagements

    state "Pôle Engagements" as Pole_Engagements {
        [*] --> AC_saisie
        AC_saisie --> RENG_decision: Soumission
        RENG_decision --> AC_saisie: Rejet motif
        RENG_decision --> [*]: Transmission risques
    }
    Pole_Engagements --> Pole_Risques

    state "Pôle Risques" as Pole_Risques {
        [*] --> AR_saisie
        AR_saisie --> RISQ_decision: Soumission
        RISQ_decision --> AR_saisie: Rejet motif
        RISQ_decision --> [*]: Transmission direction
    }
    Pole_Risques --> Cloture_delegation

    Cloture_delegation --> Dossier_accord: Accord (note obligatoire)
    Cloture_delegation --> Dossier_rejet: Rejet (motif obligatoire)
    Dossier_accord --> [*]
    Dossier_rejet --> [*]
```

---

## 4. Délégation de pouvoir — règle de clôture

```mermaid
flowchart LR
    A[Dossier prêt à clôturer<br/>engagements_sollicites_total] --> B{total ≤ seuil ?}
    B -->|Oui : règle ordonnée par seuil_engagements_max| C[Profil délégué<br/>habilité]
    B -->|Aucune règle ne couvre| D[Direction]
    C --> E[Profils habilités =<br/>profil délégué ∪ DG ∪ DGA]
    D --> E
    E --> F[Action de clôture<br/>note obligatoire]
    F --> G{Décision}
    G -->|Approuver| OK[Dossier clos accord]:::ok
    G -->|Rejeter motif| KO[Dossier clos rejet]:::ko

    classDef ok fill:#d1f7d6,stroke:#2e7d32,color:#1b5e20;
    classDef ko fill:#ffd6d6,stroke:#c62828,color:#7f0000;
```

> Le DG et le DGA sont **toujours** habilités, en parallèle de tout profil délégué : ils gardent le pouvoir de clôture en dernier ressort. Implémentation : `InstructionDelegationService::authorizedProfilIdsForDossier()`.

---

## 5. Lecture des diagrammes

- **Flèches pleines** = transitions normales (soumission, validation, transmission).
- **Flèches « Rejet motif »** = retour à l'étape précédente avec motif obligatoire enregistré (date, heure, identité).
- **Soumission au maillon suivant** = verrouillage des modifications par l'acteur précédent.
- Les motifs et notes (rejet, clôture) sont **toujours obligatoires**, jamais en texte vide.
