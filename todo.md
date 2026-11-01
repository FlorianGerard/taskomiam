# Taskomiam — TODO / Roadmap

> Révision : 22 octobre 2026  
> Cap : **Symfony / API Platform → Angular**, puis fonctionnalités interactives.  
> Rythme : **5 à 7 h/semaine**, environ 4 sessions de 45 à 90 minutes. Planning flexible : ne pas sacrifier la compréhension pour tenir une date.

## Périmètre et état actuel

### Considéré comme réalisé (fonctionnel côté administration)

- [x] Utilisateurs et connexion administrateur avec EasyAdmin
- [x] CRUD Recette : nom, description, portions, temps, auteur, statut
- [x] CRUD Ingrédient et association RecipeIngredient (quantité, unité)
- [x] Catégories et association ManyToMany Recette ↔ Catégorie
- [x] Mots-clés et association ManyToMany Recette ↔ Keywords
- [x] Administration des recettes et de leurs ingrédients dans EasyAdmin

**Important :** « fonctionnel » ne signifie pas encore couvert par des tests automatisés. Aucun système API Platform ou Angular opérationnel n'est confirmé dans les fichiers transmis.

### Hors scope immédiat

- Liste de courses, todo list, calcul intelligent des quantités
- Traductions des contenus recette en plusieurs langues
- SSR, PWA, application mobile, notifications, intégration assistants vocaux
- Création / édition de recettes dans Angular tant que le parcours de consultation n'est pas stable
- Design system complexe et optimisations prématurées

## Semaine 1 — 26/10 au 01/11 : première intégration bout en bout

**Livrable :** une page Angular affiche plusieurs recettes réellement lues depuis PostgreSQL via l'API.

- [x] Vérifier `composer.json`, la version Symfony/PHP et si API Platform est déjà installé
- [ ] Vérifier l'état de Doctrine (schéma, migrations, relations et contraintes)
- [ ] Préparer 5 à 10 recettes représentatives, avec auteurs, statuts, catégories et ingrédients (fixtures simples ou saisie EasyAdmin)
- [ ] Installer / configurer API Platform si nécessaire et vérifier la documentation API
- [ ] Exposer **en lecture seule** la collection de recettes et le détail d'une recette
- [ ] Définir le minimum de champs publics : id, nom, description courte, portions, temps, statut selon visibilité retenue
- [ ] Ne retourner dans la consultation publique que les recettes `published` ; tester explicitement le cas `draft`
- [ ] Vérifier les réponses HTTP, le JSON, la pagination et le cas d'un identifiant inexistant
- [ ] Initialiser le projet Angular (dans un dossier distinct du backend) et vérifier la configuration locale d'accès à l'API
- [ ] Créer un service Angular de lecture des recettes, puis afficher leur nom dans une liste

**Validation :** plusieurs recettes s'affichent dans Angular ; un changement de nom depuis EasyAdmin est visible au rafraîchissement ; aucune recette brouillon n'apparaît dans l'accès public.

## Semaine 2 — 02/11 au 08/11 : consulter une recette complète

**Livrable :** liste + fiche détail navigable et compréhensible.

- [ ] Définir précisément les données nécessaires à la fiche : description, portions, temps, catégories, mots-clés, ingrédients, quantité, unité
- [ ] Régler les groupes de sérialisation / la forme des relations sans exposer l'intégralité des entités
- [ ] Contrôler les risques de références circulaires et de requêtes excessives
- [ ] Afficher la fiche détail Angular avec une route paramétrée
- [ ] Afficher les ingrédients avec quantité et unité ; conserver les valeurs techniques des enums côté API
- [ ] Afficher les catégories et mots-clés
- [ ] Prévoir une page « recette introuvable »
- [ ] Faire un test manuel de bout en bout sur des données variées

**Validation :** une recette ajoutée intégralement dans EasyAdmin s'affiche correctement dans sa page Angular sans duplication ni champ sensible.

## Semaine 3 — 09/11 au 15/11 : qualité de consultation

**Livrable :** parcours de recherche/filtrage fiable.

- [ ] Ajouter un filtrage par catégorie et une recherche simple par nom (API puis Angular)
- [ ] Ajouter une pagination utilisable dans Angular
- [ ] Gérer chargement, résultat vide et erreur HTTP
- [ ] Clarifier les responsabilités Angular : composants, services, modèles/types et routes
- [ ] Ajouter quelques tests API sur publication, collection, détail et filtrage
- [ ] Ajouter au moins un test Angular sur le service ou le composant de liste
- [ ] Vérifier la configuration CORS / proxy selon l'environnement utilisé

**Validation :** liste filtrable, paginée, états d'erreur et cas vides gérés ; tests principaux exécutables.

## Semaine 4 — 16/11 au 22/11 : préparer les comptes utilisateurs

**Livrable :** authentification et frontière claire entre recettes publiques / privées.

- [ ] Décider du parcours métier : visiteur, utilisateur connecté, auteur, administrateur
- [ ] Décider quelles recettes sont publiques et à qui appartiennent les brouillons
- [ ] Examiner l'authentification Symfony actuelle et choisir le mécanisme adapté à Angular (session/cookie ou token selon l'architecture)
- [ ] Mettre en place l'authentification retenue côté API et Angular
- [ ] Séparer les permissions de lecture et d'écriture ; tester les refus 401/403
- [ ] Vérifier qu'un utilisateur ne peut pas lire/modifier les brouillons d'autrui
- [ ] Préserver le fonctionnement du login EasyAdmin

**Validation :** un visiteur consulte le public ; un utilisateur authentifié est identifié ; les restrictions sont vérifiées par tests.

## Semaine 5 — 23/11 au 29/11 : premier parcours d'écriture

**Livrable :** une première action sur les recettes propres à l'utilisateur.

- [ ] Choisir une **seule** opération initiale : création de brouillon recommandée
- [ ] Définir les champs modifiables et les validations côté API
- [ ] Lier automatiquement l'auteur au compte authentifié, sans faire confiance à un auteur fourni par le client
- [ ] Implémenter le formulaire Angular minimal de l'opération choisie
- [ ] Gérer messages de validation et erreurs réseau
- [ ] Tester qu'un utilisateur ne peut pas créer/modifier une recette au nom d'un autre
- [ ] Vérifier que les recettes administrées dans EasyAdmin restent cohérentes

**Validation :** premier parcours d'écriture effectif, autorisé, et testé. L'édition des ingrédients depuis Angular reste optionnelle.

## Semaine 6 — 30/11 au 06/12 : stabilisation et suite

**Livrable :** MVP Recettes consultable et première écriture maîtrisée.

- [ ] Corriger les problèmes révélés par l'usage réel
- [ ] Compléter les tests métier réellement critiques
- [ ] Documenter comment démarrer Symfony/PostgreSQL/Angular et exécuter les tests
- [ ] Identifier les besoins qui justifient réellement de modifier le modèle Recette
- [ ] Définir le prochain vertical slice : édition d'une recette ou première liste de courses (choisir selon la valeur utilisateur)
- [ ] Découper cette fonctionnalité en petites tâches avant toute implémentation

**Validation :** environnement reproductible, MVP démontrable, backlog priorisé pour la suite.

## Vérifications techniques à garder en vue (sans bloquer Angular)

- [ ] **Category :** `description` est nullable en Doctrine mais `NotBlank` dans la validation ; décider de la règle métier voulue.
- [ ] **Category :** vérifier le comportement des deux côtés de l'association ManyToMany et des méthodes add/remove.
- [ ] **Recipe :** confirmer que le statut `PUBLISHED` par défaut correspond à la politique de publication souhaitée.
- [ ] **RecipeIngredient :** assurer la validation de la quantité `DECIMAL` et le traitement de son type `string` côté JSON / Angular.
- [ ] **User :** protéger les champs sensibles (password, hash, rôles, email selon le contexte) lors de la sérialisation.
- [ ] **Données de test :** rendre les données suffisamment reproductibles pour les tests d'API.
- [ ] Vérifier les fichiers non fournis : configuration sécurité, repositories, fixtures, tests, composer.json et éventuel code API existant.

## Discipline de travail

1. Une seule fonctionnalité verticale en cours.
2. Pour chaque tâche : comprendre l'objectif, implémenter, tester, puis cocher.
3. Ne pas ajouter une nouvelle entité tant qu'elle ne sert pas au livrable courant.
4. Si une semaine déborde, reporter les tâches ; ne pas élargir le scope.
5. Garder EasyAdmin comme outil de saisie des données tant que les formulaires Angular ne sont pas nécessaires.

## Prochaine session — ordre précis

- [ ] Vérifier les dépendances déjà installées et l'état de l'application
- [ ] Écrire en une phrase le contrat attendu de la première route `GET /api/recipes`
- [ ] Exposer une première recette publiée en lecture seule
- [ ] Examiner le résultat JSON obtenu avant de toucher à Angular
