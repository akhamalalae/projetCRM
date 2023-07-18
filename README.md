# CRM Merchandising

Le CRM permet la configuration et la création automatique des enquêtes de merchandising (formulaires).

## Comptes utilisateurs
  1. Gestionnaire
  - Gestion des auditeurs
      - La saisie des informations personnelles.
      - Gestion des rôles d'accès.
      - Gestion des groupes d'auditeurs.
  - Configuration des entreprises et les points de vente.
      - La saisie des informations de l'entreprise.
      - La saisie des informations des points de vente.
      - Configurer l'espace des points de vente en mode graphique.
  - Génération des enquêtes libres.
      - Module de reporting et statistique.
  - Configuration et génération automatique du routing.
      - Attribution des rendez-vous aux auditeurs.
   
2. Auditeur
  - Gestion des rendez-vous
      - Visualisation des rendez-vous dans l'agenda.
      - Remplir et valider les enquêtes.

#### Un rendez-vous est attribué à un auditeur avec une description, une date de début et de fin pour le compte d'une entreprise a un point de vente avec une enquête à remplir.

## Environnement technique

  1. Backend
  - PHP 8
  - Symfony 5 et son écosystème

2. Frontend
  - HTML5
  - CSS3
  - Bootstrap
  - Twig
  - JS
  - Bibliothèque JavaScript jQuery
  - FullCalendar
  - Icons Font Awesome
  -  Plug-in jQuery DataTables

3. Technologie de conteneurisation
  - Docker

4. Dépôt distant
  - Github

5. Dépôt local
  - Git

6. Dépôt distant
  - Github

7. Database
  - MySql

8. API REST
  - API Platform


## Installation

1 : Clonage du dépôt.
  - git clone git@github.com:akhamalalae/ProjetTKT.git

2 : Lancer la stack docker-compose
  - docker-compose build
  - docker-compose create
  - docker-compose start 

3 : Changer le propriétaire du fichier par l’utilisateur courant pour pouvoir modifier facilement
  - sudo chown -R $USER ./

4 : Entrer dans le shell du conteneur “www”
  - docker exec -it www_docker_symfony bash
  - cd project

5 : Créer la base de données.
  - php bin/console doctrine:database:create

6 : Lancer les migrations.
  - php bin/console make:migration
  - php bin/console doctrine:migrations:migrate

7 : Lancé les fixtures.
  - php bin/console doctrine:fixtures:load


## URL

1 : Application
   - http://127.0.0.1:8741/
   - Authentification ("username": "admin@gmail.com", "password": "rootroot")

2 : phpMyAdmin ("username": "root", "password": "")
  - http://127.0.0.1:8080/


3 : Documentation de l'API
  - http://127.0.0.1:8741/api