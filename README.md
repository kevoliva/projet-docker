# projet-docker

# Membres du projet
MARQUE Quentin
OLIVA Kévin

# Présentation
Notre projet est constitué de 4 conteneurs décrits dans le fichier docker-compose.yml
- le conteneur db, qui correspond à la base de données mysql du projet, nommé db_projet_docker
- le conteneur phpmyadmin, nommé phpmyadmin_projet_docker
- le conteneur maildev, nommé maildev_projet_docker permettant de gérer l'envoi et la réception de mails
- le conteneur Apache et Php nommé www_projet_docker

# Installation

```
git clone https://github.com/kevoliva/projet-docker.git
```

```
cd projet-docker
```

```
docker-compose up -d
```

```
docker exec www_projet_docker composer install
```

```
docker exec -it www_projet_docker bash
```

```
bin/console d:d:c
```

```
bin/console d:m:m
```

# Notre mise en place du projet
Vérification du bon fonctionnement des 4 conteneurs
Création d'un projet Symfony
   $ docker exec www_projet_docker composer create-project symfony/website-skeleton project
Création de la base de données depuis le shell du conteneur www
   $docker exec -it www_projet_docker bash
   # cd projet-symfony
   # php bin/console doctrine:database:create
Ajout de l'entité "Mail" et des attributs "Sujet", "Corps" et "Destinataire" dans la base de données
   # php bin/console make:entity
   # php bin/console make:migration
   # php bin/console doctrine:migrations:migrate
Création du contrôleur MailController dans le projet Symfony
   # php bin/console make:controller
Mise en place d'un formulaire type d'envoi de mail

# Vérification du bon fonctionnement
Vous pouvez tester le bon fonctionnement du projet :
Cliquer sur le bouton "Créer un nouveau mail"
Saisir les champs présents dans le formulaire
Vérifier l'envoi de ces données en base en consultant l'historique des mails envoyés depuis l'interface, ou en consultant dans phpmyadmin (http://localhost:8080)
Vérifier la réception des mails dans Maildev (http://localhost:8081)

Les conteneurs communiquent bien entre-eux !

# Problèmes rencontrés
Problème au niveau de la configuration du virtual host : réglé
Problème de réécriture de route (manque de l'extension apache de Symfony) : réglé
