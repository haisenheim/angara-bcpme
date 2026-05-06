# ANGARA (version BCPME) — Acteurs & Rôles

> Référence : CONTEXT.md
> Ce fichier décrit tous les acteurs du système, leurs droits,
> leur guard d'authentification et leur périmètre d'action.
> Cursor doit s'y référer pour toute Policy, Middleware ou vérification d'accès.

---------

## Acteur 1 — SUPER ADMINISTRATEUR (role_id = 1)

### Identité
- Alias = admin
- Acteur de preparation du systeme

### Accès
- Accès à toutes les fonctionnalités de l'application

### Droits (inclut tous les droits Dentiste + Secrétaire + Assistant)

| Ressource | Créer | Lire | Modifier | Supprimer |
|---|:---:|:---:|:---:|:---:|
| Utilisateurs | ✅ | ✅ | ✅ | ✅ soft |
| Entreprises | ❌ | ✅ | ❌ | ❌ |
| Prospects | ❌ | ✅ | ❌ | ❌|
| Documents | ✅ | ✅ | ✅ | ✅ |
| Pieces exigibles | ✅ | ✅ | ✅ | ✅ |
| Dossiers d'instruction | ❌ | ✅ | ❌ | ❌ |

---

## Acteur 2 — Gestionnaire (role_id=16)

### Identité
- Portée niveau agence

### Droits

| Ressource | Créer | Lire | Modifier | Supprimer |
|---|:---:|:---:|:---:|:---:|
| Utilisateurs | ✅ | ✅ | ✅ | ✅ soft |
| Entreprises |  | ✅ | ✅ | ❌ |
| Prospects | ✅| ✅ | ✅ | ✅|
| Dossiers d'instruction | ❌ | ✅ | ❌ | ❌ |

**Règle importante :** un gestionnaire ne voit que les prospects et les clients qu'il a créés ainsi les dossiers d'instruction qui leurs sont associés.
Le gestionnaire ne peut plus effectuer de modification lorsque le prospect a ete soumis au chef d'agence
Le gestionnaire peut a nouveau effectuer des modification si le chef d'agence rejette le prospect

---
## Acteur 2 — Chef d'agence (role_id=15)

### Identité
- Portée niveau agence

### Droits

| Ressource | Créer | Lire | Modifier | Supprimer |
|---|:---:|:---:|:---:|:---:|
| Utilisateurs | ✅ | ✅ | ✅ | ✅ soft |
| Entreprises |  | ✅ | ✅ | ❌ |
| Prospects | ✅| ✅ | ✅ | ✅|
| Dossiers d'instruction | ❌ | ✅ | ❌ | ❌ |

**Règle importante :** un chef d'agence ne voit que les prospects et les clients créés dans son agence ainsi que les dossiers d'instruction qui leurs sont associés.


---
## Acteur 3 — Chef de filiere (role_id=24)

### Identité
- Portée niveau agence

### Droits

| Ressource | Créer | Lire | Modifier | Supprimer |
|---|:---:|:---:|:---:|:---:|
| Entreprises |  | ❌ | ✅ | ❌ |
| Prospects | ❌| ✅ | ✅ | ✅|
| Dossiers d'instruction | ❌ | ✅ | ❌ | ❌ |

**Règle importante :** un chef de filiere procedere a la structuration du client une fois que ce dernier a ete valider par le chef d'agence.
Une fois que le client a ete structure il peut par la suite lui creer un dossier d'instruction.

---
## Acteur 2 — Chef d'agence (role_id=15)

### Identité
- Portée niveau agence

### Droits

| Ressource | Créer | Lire | Modifier | Supprimer |
|---|:---:|:---:|:---:|:---:|
| Utilisateurs | ✅ | ✅ | ✅ | ✅ soft |
| Entreprises |  | ✅ | ✅ | ❌ |
| Prospects | ✅| ✅ | ✅ | ✅|
| Dossiers d'instruction | ❌ | ✅ | ❌ | ❌ |

**Règle importante :** un chef ne voit que les prospects et les clients créés dans son agence ainsi que les dossiers d'instruction qui leurs sont associés.






