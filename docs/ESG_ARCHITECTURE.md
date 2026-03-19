# Architecture du module ESG - ANGARA

## 1. Hypothèses retenues

### 1.1 Rôles et middleware existants
- **Gestionnaire** : `role_id = 13`, middleware `gestionnaire`
- **Chef d'agence (CA)** : `role_id = 12`, middleware `ca`
- **Analyste** : middleware `analyste` (role_id à vérifier)
- **Admin** : middleware `admin`

### 1.2 Liaison entreprise-gestionnaire
- Une entreprise appartient au portefeuille d'un gestionnaire si `user_id` ou `gestionnaire_id` = id du gestionnaire
- Les entreprises ont aussi `agence_id` (celle du gestionnaire)

### 1.3 Liaison dossier
- Table `dossiers` : `entreprise_id`, `programme_id`, `gestionnaire_id`, `analyste_id`, `agence_id`
- Un dossier lie une entreprise à un programme
- Le chef d'agence voit les dossiers de son agence (`user.agence_id`)

### 1.4 Connexion base de données
- Le modèle `Entreprise` utilise `central_app_mysql` (même DB que mysql par défaut)
- Les nouvelles tables ESG utiliseront la connexion par défaut (mysql)

### 1.5 Tables de référence
- `agences` : existe (sans timestamps)
- `users` : existe avec `agence_id`
- Pas de table `evaluation_settings` initiale : créée vide pour extensibilité

### 1.6 Stratégie des clés étrangères
- FK vers `entreprises`, `dossiers`, `programmes`, `agences`, `users` avec `nullable()` si la table cible peut ne pas exister ou pour éviter les erreurs de migration
- Pas de `foreignId()->constrained()->cascadeOnDelete()` pour éviter les blocages si structure incertaine

## 2. Schéma des tables

```
entreprise_evaluation_profiles (1 par entreprise)
    └── entreprise_id (unique)
    └── gestionnaire_id, agence_id

dossier_esg_evaluations (1 par dossier)
    └── dossier_id (unique)
    └── entreprise_id, programme_id, agence_id, gestionnaire_id, analyste_id
    └── status: draft | submitted | validated | rejected

dossier_esg_evaluation_items (N par évaluation)
    └── dossier_esg_evaluation_id
    └── category_code, indicator_code, value_*, score, weight

evaluation_frameworks
evaluation_categories (framework_id)
evaluation_indicators (framework_id, category_id)
evaluation_score_thresholds
evaluation_settings (key-value)
```

## 3. Workflow ESG

1. **Gestionnaire** : crée/met à jour le profil ESG entreprise (données structurelles)
2. **Analyste** : crée/met à jour l'évaluation ESG dossier (draft) → soumet
3. **Chef d'agence** : valide ou rejette l'évaluation soumise
4. Si rejet : analyste peut modifier et resoumettre
5. Pas d'historisation : une seule évaluation par dossier, mise à jour en place

## 4. Ordre des migrations

1. evaluation_frameworks
2. evaluation_categories
3. evaluation_indicators
4. evaluation_score_thresholds
5. evaluation_settings
6. entreprise_evaluation_profiles
7. dossier_esg_evaluations
8. dossier_esg_evaluation_items
