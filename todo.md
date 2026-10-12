# Taskomiam — TODO backend Symfony + frontend Angular

Mis à jour le 06/10/2026, à partir de ton planning et du CRUD Recipe partagé.

Les cases cochées correspondent aux éléments déjà réalisés ou confirmés. Une proposition discutée reste à faire tant que sa mise en place n'est pas confirmée. Les dates sont des repères : si une étape déborde, décale la suite sans chercher à tout rattraper le lendemain.

## Cap du projet

Symfony gère l'API, la sécurité et les règles métier. EasyAdmin reste le back-office. Angular gère les pages de l'application, le routing, les formulaires et les appels HTTP.

Deux usages distincts :

- **Liste de courses** : ingrédients, quantités, unités, génération depuis une recette.
- **Liste de tâches** : texte libre, tâches terminées ou non, sans recherche d'ingrédients.

Le premier périmètre comprend : recettes, ingrédients, listes de courses et listes de tâches personnelles. Les fonctions avancées restent dans le backlog.

## État actuel

### Infrastructure et utilisateurs

- [x] Projet Symfony créé
- [x] Docker et PostgreSQL configurés
- [x] EasyAdmin installé
- [x] Dashboard / contrôleur Admin créé
- [x] Authentification du back-office et logout
- [x] Protection de `/admin` pour les administrateurs
- [x] Rôles `ROLE_USER` et `ROLE_ADMIN` configurés et accès vérifiés
- [x] CRUD User terminé : email, mot de passe, rôles, date de création, avatar
- [x] Unicité de l'email et opérations CRUD vérifiées
- [x] Hash du mot de passe à la création et à la modification
- [x] Conservation du hash si aucun nouveau mot de passe n'est fourni
- [x] Comprendre le rôle de `PasswordAuthenticatedUserInterface`
- [ ] Tester explicitement `isGranted()` sur un cas concret

### Recettes

- [x] Entité Recipe créée, migration exécutée, table vérifiée
- [x] Champs : name, description, servings, preparationTime, cookingTime, createdAt, updatedAt
- [x] Relation Recipe → User via `author`
- [x] Statut avec l'enum `RecipeStatus`
- [x] CRUD Recipe généré et champs configurés
- [x] Création d'une recette testée
- [x] Dates de création / modification gérées dans le contrôleur EasyAdmin
- [x] Représentation du User via `__toString()` mise en place
- [x] Valider la modification et la suppression d'une recette
- [x] Finaliser les contraintes Symfony
- [x] Déplacer la gestion des dates vers une solution commune au BO et à l'API
- [x] Vérifier / simplifier `configureFields()` : utiliser des `yield` seuls ou un tableau sans `yield`

## Semaine 1 — 30/09 au 04/10 : socle terminé

- [x] Mercredi : CRUD User
- [x] Jeudi : hash et modification du mot de passe
- [x] Vendredi : corrections / rattrapage
- [x] Samedi : authentification du back-office
- [x] Dimanche : rôles et restriction d'accès

**Résultat acquis :** un administrateur peut gérer les utilisateurs depuis un back-office protégé.

## Semaine 2 — 05/10 au 11/10 : consolider Recipe et commencer Ingredient

### Lundi 05/10 — Entité Recipe

- [x] Définir les propriétés
- [x] Créer l'entité et sa migration
- [x] Exécuter la migration et vérifier la table

### Mardi 06/10 — CRUD Recipe

- [x] Configurer les champs EasyAdmin
- [x] Ajouter auteur et statut
- [x] Afficher le nom de l'auteur avec `__toString()`
- [x] Tester la création
- [x] Terminer les vérifications restantes lors de la prochaine session

### Mercredi 07/10 — Terminer les validations Recipe

**Objectif :** empêcher l'enregistrement d'une recette incohérente.

- [x] Nom obligatoire et longueur maximale cohérente avec la colonne DB
- [x] Portions strictement supérieures à 0
- [x] Préparation et cuisson supérieures ou égales à 0 : zéro peut être valide
- [x] Définir si description et auteur sont obligatoires
- [x] Vérifier la valeur de statut par défaut
- [x] Vérifier l'utilité réelle du groupe `recipe_create`
- [x] Tester des données valides et invalides
- [x] Tester modification et suppression

### Jeudi 08/10 — Nettoyer le CRUD et gérer les dates

**Objectif :** préparer Recipe à être manipulée aussi par l'API.

- [x] Corriger le mélange `return [yield ...]`
- [x] Utiliser les noms exacts des propriétés, notamment `servings`
- [x] Simplifier AssociationField : retirer la configuration redondante
- [x] Garder le tri des auteurs et le placeholder
- [x] Masquer la description sur l'index et préciser les durées en minutes
- [x] Déplacer les timestamps dans des callbacks / un listener Doctrine ou une autre solution partagée
- [x] Vérifier que createdAt ne change pas lors d'une modification
- [x] Vérifier updatedAt après une vraie modification persistée
- [x] Retirer les overrides EasyAdmin devenus inutiles

### Vendredi 09/10 — Repos / rattrapage

- [x] Terminer uniquement ce qui bloque la validation de Recipe
- [x] Sinon : soirée libre

### Samedi 10/10 — Ingredient

**Objectif :** administrer un catalogue simple d'ingrédients.

- [x] Créer Ingredient : id, name, createdAt
- [x] Ajouter `__toString()` pour l'affichage
- [x] Définir la règle pour les doublons : espaces et casse du nom
- [x] Créer / exécuter la migration
- [x] Créer le CRUD EasyAdmin
- [x] Ajouter les validations

### Dimanche 11/10 — Vérifications et modèle RecipeIngredient

- [x] Tester création / modification / suppression d'un ingrédient
- [x] Vérifier un doublon selon la règle retenue
- [x] Dessiner Recipe → RecipeIngredient → Ingredient
- [x] Définir quantity et unit, avec quantités décimales possibles
- [x] Décider comment représenter une unité « pièce »
- [x] Noter le comportement de suppression attendu

**Jalon :** Recipe est consolidée ; le catalogue Ingredient est utilisable.

## Semaine 3 — 12/10 au 18/10 : recettes complètes

### Lundi — Entité RecipeIngredient

- [x] Créer id, recipe, ingredient, quantity, unit
- [x] ManyToOne vers Recipe et vers Ingredient
- [x] OneToMany côté Recipe, avec add / remove cohérents
- [x] Choisir un stockage adapté aux quantités décimales
- [x] Définir les unités de départ : g, kg, ml, l, pièce
- [x] Valider quantité strictement positive et relations obligatoires

### Mardi — Persistance et suppression

- [x] Créer / exécuter la migration
- [x] Configurer la persistance des lignes avec la recette
- [x] Supprimer une ligne retirée de la collection selon la règle retenue
- [x] Vérifier que retirer une ligne ne supprime pas l'Ingredient du catalogue
- [x] Empêcher la suppression d'un ingrédient utilisé, avec un message compréhensible

### Mercredi — FormType d'une ligne

- [x] Créer RecipeIngredientType
- [x] Sélection d'un Ingredient, quantité et unité
- [x] Définir la classe de données du formulaire
- [x] Vérifier le rendu et les validations d'une ligne

### Jeudi — Collection dans EasyAdmin

- [x] Ajouter CollectionField dans RecipeCrudController
- [x] Utiliser RecipeIngredientType comme type d'entrée
- [x] Permettre ajout / suppression des lignes
- [x] Vérifier que les méthodes add / remove de Recipe sont utilisées correctement

### Vendredi — Repos / rattrapage

- [x] Corriger uniquement les blocages du formulaire imbriqué

### Samedi — Scénarios complets

- [x] Créer une recette avec plusieurs ingrédients
- [x] Modifier une quantité et une unité
- [x] Ajouter et retirer une ligne sur une recette existante
- [x] Vérifier les données après sauvegarde et rechargement
- [x] Tester une quantité décimale et une ligne invalide
- [x] Vérifier la suppression d'une recette et de ses lignes

### Dimanche — Fixtures et consolidation

- [ ] Ajouter quelques utilisateurs, ingrédients et recettes de développement
- [ ] Garder des fixtures reproductibles ; Faker seulement si utile
- [ ] Vérifier l'association auteur, déjà présente
- [ ] Ajouter la collection inverse User → recipes si elle est utile
- [ ] Ajouter un premier test pertinent de validation ou persistance

**Jalon :** une recette complète peut être administrée depuis le BO.

## Semaine 4 — 19/10 au 25/10 : listes de courses

### Lundi — Définir le modèle

- [ ] Créer ShoppingList : id, name, owner, createdAt, updatedAt
- [ ] Créer ShoppingListItem : liste, ingredient, quantity, unit, checked
- [ ] Définir les règles de propriété et les suppressions
- [ ] Prévoir l'ajout manuel d'un ingrédient du catalogue
- [ ] Repousser les lignes libres hors catalogue si elles compliquent ce premier périmètre

### Mardi — Entités et migrations

- [ ] Créer les relations et méthodes add / remove
- [ ] Ajouter les contraintes Symfony et DB
- [ ] Exécuter les migrations
- [ ] Vérifier la persistance d'une liste avec ses lignes

### Mercredi — Back-office

- [ ] Administrer une liste de courses et ses lignes
- [ ] Modifier les quantités et cocher un article
- [ ] Vérifier que les listes appartiennent à un utilisateur

### Jeudi — Génération depuis une recette

- [ ] Créer un service métier pour ajouter les ingrédients d'une recette à une liste
- [ ] Choisir une liste existante ou en créer une
- [ ] Copier les données sans modifier la recette source
- [ ] Définir ce qui se passe si la même recette est ajoutée deux fois

### Vendredi — Repos / rattrapage

- [ ] Stabiliser la génération simple

### Samedi — Portions et regroupement

- [ ] Adapter les quantités au nombre de portions demandé
- [ ] Regrouper le même ingrédient avec la même unité
- [ ] Garder deux lignes distinctes si les unités diffèrent dans cette première version
- [ ] Définir une règle d'arrondi pour l'affichage
- [ ] Tester plusieurs recettes dans une liste

### Dimanche — Tests métier

- [ ] Tester le calcul des portions
- [ ] Tester le regroupement des quantités
- [ ] Tester les unités différentes et les portions invalides
- [ ] Vérifier que les recettes sources restent inchangées

**Jalon :** une liste de courses peut être générée à partir de recettes.

## Semaine 5 — 26/10 au 01/11 : tâches et droits métier

### Lundi — TodoList et TodoItem

- [ ] TodoList : id, name, owner, createdAt, updatedAt
- [ ] TodoItem : id, todoList, title, completed
- [ ] Ajouter les relations, validations et migrations
- [ ] Garder ce modèle indépendant des ingrédients

### Mardi — CRUD des tâches

- [ ] Administrer une liste et ses tâches dans EasyAdmin
- [ ] Ajouter / modifier / supprimer une tâche
- [ ] Marquer une tâche comme terminée

### Mercredi — Définir les règles d'accès

- [ ] Documenter qui peut lire / modifier / supprimer une recette
- [ ] Proposition MVP : recettes publiées consultables ; brouillons réservés à l'auteur et aux admins
- [ ] Réserver listes de courses et listes de tâches à leur propriétaire
- [ ] Définir les droits administrateur explicitement

### Jeudi — Appliquer les droits

- [ ] Centraliser les vérifications nécessaires dans des voters / services adaptés
- [ ] Préparer le filtrage des listes de résultats par utilisateur
- [ ] Ne pas considérer le masquage d'un bouton comme une protection

### Vendredi — Repos / rattrapage

### Samedi — Tests avec deux utilisateurs

- [ ] Vérifier qu'A ne peut pas lire ou modifier les listes de B
- [ ] Vérifier les droits auteur / autre utilisateur / administrateur sur les recettes
- [ ] Vérifier le statut brouillon / publié

### Dimanche — Consolidation backend

- [ ] Vérifier migrations, fixtures et contraintes
- [ ] Garder les règles métier communes au BO et à l'API
- [ ] Décrire le modèle et les règles dans le README

**Jalon :** le modèle principal et ses règles d'accès sont prêts pour l'API.

## Semaine 6 — 02/11 au 08/11 : première API

### Lundi — Choisir l'approche API

- [ ] Choisir API Platform ou des contrôleurs Symfony JSON ; conserver une approche cohérente
- [ ] Si API Platform est retenu, l'intégrer maintenant
- [ ] Définir les endpoints, données exposées et erreurs attendues
- [ ] Garder EasyAdmin comme interface BO distincte

### Mardi — Lecture des recettes

- [ ] Liste paginée des recettes accessibles
- [ ] Détail d'une recette avec auteur, ingrédients, quantités et unités
- [ ] Éviter les références circulaires dans le JSON
- [ ] Ne jamais exposer password / hash / données privées inutiles

### Mercredi — Authentification API

- [ ] Choisir une stratégie cohérente avec les domaines du front et de l'API
- [ ] Définir connexion, utilisateur courant et déconnexion
- [ ] Prévoir CSRF si la stratégie utilise des cookies de session
- [ ] Configurer CORS uniquement si les origines diffèrent
- [ ] Garder le fonctionnement du BO vérifiable

### Jeudi — Connexion testable

- [ ] Implémenter le contrat d'authentification choisi
- [ ] Tester bonnes / mauvaises informations de connexion
- [ ] Tester utilisateur courant et déconnexion
- [ ] Vérifier les réponses pour une requête non authentifiée

### Vendredi — Repos / rattrapage

### Samedi — Écriture des recettes

- [ ] Créer / modifier / supprimer une recette via l'API
- [ ] Définir l'auteur côté serveur pour un utilisateur ordinaire
- [ ] Refuser les modifications de propriété non autorisées
- [ ] Gérer les lignes RecipeIngredient
- [ ] Renvoyer des erreurs de validation utilisables par un formulaire

### Dimanche — Vérifications API

- [ ] Tester les droits avec deux comptes et un administrateur
- [ ] Tester une recette invalide, une ressource absente et un accès interdit
- [ ] Vérifier timestamps et invariance de createdAt
- [ ] Documenter quelques requêtes et réponses types

**Jalon :** l'API recettes et l'authentification sont utilisables sans frontend.

## Semaine 7 — 09/11 au 15/11 : API des listes et préparation Angular

### Lundi — API listes de courses

- [ ] Lister / créer / modifier / supprimer ses propres listes
- [ ] Lire les lignes d'une liste
- [ ] Paginer les collections qui peuvent grossir

### Mardi — API articles et génération

- [ ] Ajouter / modifier / supprimer / cocher un article
- [ ] Exposer l'ajout d'une recette à une liste via le service métier
- [ ] Valider portions et propriété de la liste cible

### Mercredi — API listes de tâches

- [ ] CRUD de ses propres listes et tâches
- [ ] Modification de l'état completed
- [ ] Vérifier les droits sur les tâches elles-mêmes

### Jeudi — Contrat prêt pour le frontend

- [ ] Harmoniser pagination et erreurs
- [ ] Préparer quelques données de démonstration
- [ ] Vérifier les principaux endpoints et les accès interdits
- [ ] Ajouter les tests fonctionnels essentiels de sécurité

### Vendredi — Repos / rattrapage

### Samedi — TypeScript utile au projet

- [ ] Types primitifs, tableaux, objets et propriétés optionnelles
- [ ] Interfaces / types pour Recipe, Ingredient et User
- [ ] Unions, null et undefined
- [ ] Comprendre les génériques utilisés dans les appels HTTP
- [ ] Exercices courts à partir des réponses de ton API

### Dimanche — Créer Angular

- [ ] Créer le projet avec la version stable choisie au moment de commencer
- [ ] Lancer l'application localement
- [ ] Identifier composant, template, service et injection de dépendances
- [ ] Configurer l'URL API / un proxy de développement
- [ ] Effectuer un premier appel de lecture

**Jalon :** backend MVP exposé ; Angular démarre et communique avec Symfony.

## Semaine 8 — 16/11 au 22/11 : Angular, consultation des recettes

### Lundi — Structure et routing

- [ ] Organiser les fonctionnalités recipes, auth, shopping-lists et todos
- [ ] Créer le layout, la navigation et une page introuvable
- [ ] Ajouter les routes liste et détail des recettes
- [ ] Comprendre paramètres de route et liens de navigation

### Mardi — Service HTTP typé

- [ ] Créer RecipeService et les types de réponse
- [ ] Lire la liste et le détail via HttpClient
- [ ] Comprendre Observable et les opérateurs nécessaires à ces appels
- [ ] Éviter les abonnements imbriqués

### Mercredi — Liste des recettes

- [ ] Afficher les vraies données API
- [ ] Afficher chargement, erreur et liste vide
- [ ] Créer un composant de carte réutilisable si utile
- [ ] Ajouter les liens vers les détails

### Jeudi — Détail d'une recette

- [ ] Lire l'identifiant de route
- [ ] Afficher portions, durées et ingrédients
- [ ] Gérer une recette introuvable / inaccessible
- [ ] Choisir le rendu de description ; si HTML, prévoir un traitement sûr côté serveur et côté affichage

### Vendredi — Repos / rattrapage

### Samedi — Pagination et filtre simple

- [ ] Brancher la pagination API
- [ ] Ajouter une recherche par nom côté serveur si elle existe dans le contrat
- [ ] Comprendre la temporisation et l'annulation des recherches précédentes
- [ ] Vérifier navigation et états de chargement

### Dimanche — TypeScript et consolidation

- [ ] Remplacer les `any` inutiles par des types explicites
- [ ] Comprendre les erreurs de compilation rencontrées
- [ ] Vérifier le parcours liste → détail → retour
- [ ] Corriger les problèmes de navigation ou d'affichage

**Jalon Angular 1/4 :** consultation des recettes fonctionnelle.

## Semaine 9 — 23/11 au 29/11 : Angular, authentification

### Lundi — Formulaire de connexion

- [ ] Apprendre les formulaires réactifs sur un cas simple
- [ ] Créer les champs email / mot de passe et leurs validations
- [ ] Afficher des messages compréhensibles

### Mardi — AuthService

- [ ] Brancher la connexion sur Symfony
- [ ] Charger l'utilisateur courant
- [ ] Gérer l'état connecté / non connecté
- [ ] Ne pas confondre données utilisateur et preuve d'authentification

### Mercredi — Session et requêtes

- [ ] Configurer les appels selon la stratégie d'authentification choisie
- [ ] Ajouter un interceptor seulement pour un comportement partagé utile
- [ ] Gérer une session expirée / une réponse 401
- [ ] Vérifier un rechargement complet de page

### Jeudi — Routes et navigation

- [ ] Protéger les routes privées avec un guard
- [ ] Adapter la navigation à l'état de connexion
- [ ] Implémenter la déconnexion effective côté serveur et front
- [ ] Garder les contrôles de sécurité côté API

### Vendredi — Repos / rattrapage

### Samedi — Mes recettes

- [ ] Afficher les recettes de l'utilisateur connecté
- [ ] Ajouter le filtre auteur côté serveur si nécessaire
- [ ] Afficher brouillon / publié selon les droits
- [ ] Préparer les liens créer / modifier

### Dimanche — Vérifications du parcours

- [ ] Tester connexion erronée / valide, déconnexion et rechargement
- [ ] Tester l'accès direct à une URL privée
- [ ] Vérifier le comportement après expiration de l'authentification
- [ ] Ajouter un test pertinent sur le comportement du service ou du formulaire

**Jalon Angular 2/4 :** espace utilisateur et consultation privée fonctionnels.

## Semaine 10 — 30/11 au 06/12 : Angular, création de recettes

### Lundi — Formulaire Recipe

- [ ] Créer le formulaire réactif : nom, description, portions, durées, statut
- [ ] Reproduire les validations utiles à l'interface
- [ ] Garder Symfony comme validation de référence

### Mardi — FormArray d'ingrédients

- [ ] Comprendre FormGroup / FormArray sur une ligne d'ingrédient
- [ ] Ajouter et retirer des lignes
- [ ] Saisir ingrédient, quantité et unité
- [ ] Charger les choix depuis l'API ; prévoir une recherche si le catalogue devient important

### Mercredi — Enregistrement

- [ ] Construire le payload attendu par l'API
- [ ] Créer la recette et rediriger vers son détail
- [ ] Désactiver les envois multiples pendant la requête
- [ ] Afficher les erreurs de validation du serveur sur les champs concernés

### Jeudi — Édition

- [ ] Charger une recette existante dans le formulaire
- [ ] Charger et modifier ses lignes d'ingrédients
- [ ] Réutiliser les éléments communs à la création
- [ ] Gérer le refus d'accès côté API

### Vendredi — Repos / rattrapage

### Samedi — Suppression et finitions

- [ ] Demander confirmation avant suppression
- [ ] Brancher la suppression et le retour à la liste
- [ ] Afficher le résultat de la sauvegarde
- [ ] Vérifier l'interface sur un petit écran

### Dimanche — Parcours de bout en bout

- [ ] Se connecter, créer une recette, ajouter ses ingrédients, sauvegarder
- [ ] Recharger, modifier les quantités et retirer une ligne
- [ ] Vérifier les données dans EasyAdmin
- [ ] Tester erreur serveur / réseau et données invalides
- [ ] Vérifier qu'un autre utilisateur ne peut pas modifier cette recette
- [ ] Documenter le lancement local du front et du backend

**Jalon Angular 3/4 :** consultation, authentification et gestion des recettes fonctionnelles. Ce jalon couvre au moins la moitié du périmètre frontend MVP prévu ; il ne constitue pas une estimation de 50 % du temps total.

## Semaine 11 — 07/12 au 13/12 : marge et bilan

- [ ] Terminer les tâches restantes des semaines Angular précédentes
- [ ] Rejouer les parcours essentiels après corrections
- [ ] Nettoyer les composants trop volumineux et les types dupliqués
- [ ] Ajouter uniquement les tests couvrant un risque ou une règle réelle
- [ ] Préparer une petite démonstration du parcours recettes
- [ ] Mettre à jour ce TODO avec ce qui fonctionne réellement

**Pas de nouvelle grosse fonctionnalité cette semaine.** Si le rythme réel est plus lent, utiliser cette marge et décaler les dates.

## Suite Angular — dernier quart du périmètre MVP

### Listes de courses

- [ ] Pages liste / détail / création / édition
- [ ] Ajout manuel d'articles et modification des quantités
- [ ] Cases à cocher persistées
- [ ] Action « Ajouter cette recette à une liste de courses »
- [ ] Choix de la liste cible et du nombre de portions
- [ ] Affichage du résultat du regroupement

### Listes de tâches

- [ ] Pages liste / détail
- [ ] Ajouter / modifier / supprimer une tâche
- [ ] Cocher une tâche et persister son état
- [ ] Filtrer terminées / non terminées si utile

### Stabilisation du MVP

- [ ] Vérifier les quatre parcours : recettes, connexion, courses, tâches
- [ ] Vérifier l'accessibilité de base et les petits écrans
- [ ] Vérifier les erreurs réseau et les accès interdits
- [ ] Préparer le déploiement et une démonstration

## Règles de travail

### Une session = un résultat observable

Exemple : « Je peux modifier une recette avec trois ingrédients et retrouver les bonnes quantités après rechargement. »

- Session normale : 60 à 90 minutes selon ton énergie ; une session de 30 minutes reste utile.
- Une seule tâche principale par soirée. Les sous-tâches peuvent déborder.
- Vendredi réservé au repos ou au rattrapage léger.
- Le week-end peut accueillir une session plus longue, sans obligation.
- Ne pas démarrer l'étape suivante si le comportement qui lui sert de base ne fonctionne pas.
- Fin de session : noter fait / blocage / prochaine action, puis commit d'un état cohérent.
- Utiliser l'IA et la documentation pour débloquer un point, puis expliquer le code avec tes propres mots avant de le conserver.
- Apprendre TypeScript et RxJS au fil des besoins Angular, après les bases prévues en semaine 7.

### Note de session à copier

```markdown
### Session du JJ/MM

Fait :

- ...

Vérifié :

- ...

Blocage :

- ...

Prochaine action précise :

- ...
```

## Backlog — après le MVP

- [ ] SSR Angular : à étudier après stabilisation des premiers parcours
- [ ] Inscription publique et récupération de mot de passe
- [ ] Publication avancée / modération des recettes
- [ ] Images de recettes et gestion des fichiers
- [ ] Étapes de préparation structurées
- [ ] Favoris et partage de listes
- [ ] Conversion g ↔ kg et ml ↔ l lors du regroupement
- [ ] Ne pas convertir automatiquement masse ↔ volume sans données spécifiques
- [ ] Ingrédients libres, synonymes et normalisation avancée
- [ ] Réorganisation des tâches / articles
- [ ] Application mobile / React Native
- [ ] Stripe et abonnements
- [ ] Notifications / emails métier
- [ ] Messenger avancé
- [ ] Optimisation et cache guidés par des mesures
- [ ] CI/CD et déploiement production

React / Next.js ne sont pas prévus pour ce frontend : la direction retenue est Angular. API Platform est un choix à trancher au démarrage de l'API, plutôt qu'une fonctionnalité à repousser automatiquement après le frontend.
