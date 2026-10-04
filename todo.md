# Taskomiam — Planning backend

## État actuel

Déjà en place :

- [x] Projet Symfony créé
- [x] Docker configuré
- [x] PostgreSQL configuré
- [x] EasyAdmin installé
- [x] Dashboard / contrôleur Admin créé
- [x] Authentification du back-office
- [x] CRUD User terminé

---

# Semaine 1 — Utilisateurs et authentification

## Mercredi 30/09 — CRUD User

Objectif : avoir une gestion complète des utilisateurs depuis EasyAdmin.

- [x] Vérifier l'entité `User`
- [x] Ajouter les champs nécessaires
  - [x] email
  - [x] password
  - [x] roles
  - [x] date de création si nécessaire
  - [x] avatar
- [x] Créer / terminer `UserCrudController`
- [x] Configurer les champs EasyAdmin
- [x] Vérifier l'unicité de l'adresse email
- [x] Vérifier la création d'un utilisateur
- [x] Vérifier la modification d'un utilisateur
- [x] Vérifier la suppression d'un utilisateur

### Bonus

- [x] Afficher proprement les rôles dans EasyAdmin
- [x] Empêcher certaines valeurs incohérentes

### Fin de session

Le CRUD User doit être fonctionnel manuellement depuis le back-office.

---

## Jeudi 01/10 — Gestion du mot de passe

Objectif : ne jamais stocker ou modifier un mot de passe en clair.

- [x] Injecter `UserPasswordHasherInterface`
- [x] Hasher le mot de passe lors de la création
- [x] Gérer correctement la modification du mot de passe
- [x] Ne pas écraser le mot de passe si aucun nouveau mot de passe n'est fourni
- [x] Tester création + modification
- [x] Vérifier directement en DB que le mot de passe est hashé

### À comprendre

- [x] Différence entre mot de passe brut et mot de passe hashé
- [x] Fonctionnement de `PasswordAuthenticatedUserInterface`
- [x] Pourquoi le hash ne doit pas être réalisé dans l'entité

---

## Vendredi 02/10 — Repos / rattrapage

Pas de nouvelle fonctionnalité importante.

Si nécessaire :

- [x] Terminer le CRUD User
- [x] Corriger les bugs
- [x] Refactorer le code
- [x] Relire la documentation Symfony Security

Sinon : soirée libre.

---

## Samedi 03/10 — Authentification du back-office

Objectif : protéger `/admin`.

- [x] Créer le système de login Symfony
- [x] Configurer `security.yaml`
- [x] Configurer le firewall
- [x] Créer le formulaire de connexion
- [x] Ajouter le logout
- [x] Protéger `/admin`
- [x] Tester un utilisateur non authentifié
- [x] Tester un utilisateur authentifié

### Résultat attendu

Un utilisateur non connecté ne peut pas accéder au back-office.

---

## Dimanche 04/10 — Gestion des rôles

Objectif : seuls les administrateurs peuvent accéder à EasyAdmin.

- [x] Ajouter / vérifier `ROLE_USER`
- [x] Ajouter `ROLE_ADMIN`
- [x] Ajouter au moins un utilisateur administrateur
- [x] Configurer `access_control`
- [x] Vérifier qu'un `ROLE_USER` ne peut pas accéder à `/admin`
- [x] Vérifier qu'un `ROLE_ADMIN` peut accéder à `/admin`

### Bonus

- [x] Comprendre la hiérarchie des rôles Symfony
- [ ] Tester `isGranted()`

---

# Semaine 2 — Base métier : recettes

## Lundi 05/10 — Entité Recipe

Objectif : créer le premier véritable objet métier.

- [ ] Définir les propriétés de `Recipe`
- [ ] Créer l'entité Doctrine
- [ ] Créer la migration
- [ ] Exécuter la migration
- [ ] Vérifier la table en base de données

Première proposition :

```text
Recipe

id
name
description
servings
preparationTime
cookingTime
createdAt
updatedAt
```

Ne pas ajouter trop de champs immédiatement.

---

## Mardi 06/10 — CRUD Recipe

Objectif : administrer les recettes depuis EasyAdmin.

- [ ] Générer le CRUD EasyAdmin
- [ ] Configurer les champs
- [ ] Ajouter les validations Symfony
- [ ] Tester création
- [ ] Tester modification
- [ ] Tester suppression

### Validations possibles

- [ ] nom obligatoire
- [ ] nombre de portions supérieur à 0
- [ ] temps de préparation positif
- [ ] temps de cuisson positif

---

## Mercredi 07/10 — Entité Ingredient

Objectif : pouvoir administrer les ingrédients indépendamment.

- [ ] Créer `Ingredient`
- [ ] Définir les propriétés
- [ ] Migration
- [ ] CRUD EasyAdmin
- [ ] Validation

Première proposition :

```text
Ingredient

id
name
createdAt
```

---

## Jeudi 08/10 — Relation Recipe / Ingredient

Objectif : réfléchir correctement au modèle de données.

Ne pas créer directement un `ManyToMany` sans réfléchir.

Une recette contient par exemple :

```text
200 g farine
2 oeufs
50 g sucre
```

Il faut donc une entité intermédiaire.

Créer :

```text
RecipeIngredient

id
recipe
ingredient
quantity
unit
```

À faire :

- [ ] Créer `RecipeIngredient`
- [ ] Relation `ManyToOne` vers Recipe
- [ ] Relation `ManyToOne` vers Ingredient
- [ ] Ajouter `quantity`
- [ ] Ajouter `unit`
- [ ] Générer la migration
- [ ] Tester les relations

---

## Vendredi 09/10 — Repos / rattrapage

- [ ] Corriger les problèmes rencontrés
- [ ] Nettoyer le modèle Doctrine
- [ ] Relire les relations Doctrine si nécessaire
- [ ] Aucun nouveau gros développement

---

## Samedi 10/10 — Gestion des ingrédients dans une recette

Objectif : créer une recette complète depuis le back-office.

- [ ] Permettre d'associer des ingrédients à une recette
- [ ] Ajouter quantité + unité
- [ ] Tester l'ajout de plusieurs ingrédients
- [ ] Tester la suppression d'un ingrédient d'une recette
- [ ] Vérifier le comportement Doctrine

Résultat attendu :

```text
Pâte à crêpes

250 g farine
3 oeufs
500 ml lait
```

---

## Dimanche 11/10 — Refactoring + tests

Objectif : consolider les deux premières semaines.

- [ ] Vérifier toutes les migrations
- [ ] Vérifier les contraintes DB
- [ ] Vérifier les validations Symfony
- [ ] Nettoyer les contrôleurs EasyAdmin
- [ ] Identifier la logique qui ne devrait pas être dans les contrôleurs
- [ ] Écrire les premiers tests simples

---

# Semaine 3 — Utilisateur et données métier

## Objectif général

Relier progressivement les données métier à l'utilisateur.

- [ ] Relation `User -> Recipe`
- [ ] Une recette possède un auteur
- [ ] Un utilisateur peut avoir plusieurs recettes
- [ ] Empêcher les incohérences de propriété
- [ ] Commencer à réfléchir aux règles d'accès
- [ ] Ajouter des fixtures de développement
- [ ] Ajouter Faker si utile

---

# Semaine 4 — Première vraie logique métier

Objectif : sortir du simple CRUD.

Commencer la fonctionnalité centrale de Taskomiam :

```text
Recipes
    ↓
Ingredients
    ↓
Shopping list / Todo list
```

Travail prévu :

- [ ] Créer `TodoList`
- [ ] Créer `TodoItem`
- [ ] Relier les listes aux utilisateurs
- [ ] Générer une liste à partir d'une recette
- [ ] Commencer à regrouper les ingrédients identiques

---

# Règles de travail

## Une soirée = un objectif principal

Éviter :

> Travailler sur Symfony.

Préférer :

> Ce soir, un administrateur doit pouvoir créer un utilisateur avec un mot de passe correctement hashé.

---

## Limite par session

Session normale :

```text
60 à 90 minutes
```

Une fois la limite atteinte :

- noter ce qui bloque ;
- noter la prochaine action ;
- arrêter.

---

## Fin de chaque session

Ajouter une petite note :

```markdown
### Session du 01/10

Fait :

- hash du password à la création
- injection du PasswordHasher

Blocage :

- password écrasé pendant l'édition

Prochaine étape :

- gérer un champ password non mappé dans EasyAdmin
```

---

# Backlog

Fonctionnalités volontairement repoussées :

- [ ] API Platform
- [ ] React
- [ ] React Native
- [ ] Stripe
- [ ] système d'abonnement
- [ ] email
- [ ] Messenger avancé
- [ ] cache
- [ ] optimisation
- [ ] CI/CD
- [ ] déploiement production

Ces sujets ne sont pas prioritaires tant que le modèle métier principal n'est pas suffisamment solide.
