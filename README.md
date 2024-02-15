# Mathis CAPART	    DOCUMENTATION TECHNIQUE SUPERMARKIT



# Prérequis:

→ Avant de commencer l’installation, assurez-vous que votre système répond aux exigences suivantes : 
		- PHP : 8.2 ou inférieure
		- Extension ctype et iconv activées
- Composer installé sur votre système.

# Installation: 
Créer un dossier puis installer le projet dedans. Puis ouvré(e) dans votre terminal le dossier puis installer composer avec cette commande :
- composer install

Configurer votre fichier d’environnement .env. Créer le fichier, en reprenant cet exemple :


###> symfony/framework-bundle ###
APP_ENV=prod
APP_SECRET=e2ddd77c37564078fb382b40295d46d7
###< symfony/framework-bundle ###

#DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
#DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
#DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
DATABASE_URL="mysql://root:root@127.0.0.1:3306/market?serverVersion=mariadb-10.6.15"
###< doctrine/doctrine-bundle ###

###> symfony/messenger ###
#Choose one of the transports below
#MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
#MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/messenger ###


###> symfony/mailer ###
#MAILER_DSN=null://null
###< symfony/mailer ###



Pour le DATABASE_URL : il faut auparavant créer votre base de donnée nommé ‘market’ par exemple. Puis migrer les entités du projet dans votre base de donnée, ici nous avons utilisé un fichier docker, que nous vous invitons à utiliser et installer sur votre machine, il rend l’utilisation plus rapide. En reprenant ce fichier nommé compose.yaml:

version: '3'

services:
  db:
    image: mariadb:10.6.15
    ports:
      - "3306:3306"
    environment:
      - MARIADB_DATABASE=market
      - MARIADB_ROOT_PASSWORD=root

  phpmyadmin:
    image: phpmyadmin
    ports:
      - 8080:80
    environment:
      - PMA_ARBITRARY=1

Pour ensuite régler l’URL de la database dans le .env selon le nom de votre DataBase de l’image utilisé du port etc.

Pour la migration de vos entités, il faut donc réaliser ces 2 commandes de suites et accepter quand il vous demande d’executer une migration sur votre database si vous avez correctement configurer votre database url ;) :
- php bin/console make:migration
- php bin/console doctrine:migrations:migrate

Il faut aussi installer ou mettre à jour dans votre dossier votre Vendor avec cette commande :
 - composer install --no-dev --optimize-autoloader

Puis avec cette commande, voir si votre projet est bien toutes les exigences pour exécuter le projet :
 - composer require symfony/requirements-checker

  ! N’hésitez pas aussi à nettoyer votre cache avec cette commande :
	- APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

Puis pour enfin lancer votre application, lancer cette commande :
 - symfony server:start

Le projet se lancera sur l’URL http://localhost/8000 rendez-vous directement dans le navigateur pour avoir accès au projet. Et vous pourriez si vous avez utilisé docker vous rendre sur http://localhost/8080 pour avoir accès à PhpMyAdmin, qui est un gestionnaire de base de donnée.

Puis créez-vous un compte, allez le modifier en admin pour débloquer des accès sur le site web et voir tout sont potentielle il faut donc mettre cette donnée dans rôle de l’utilisateur : ["ROLE_ADMIN"].

Vous venez d’installer votre site e-commerce de vente de produit personnalisable SUPERMARKIT et nous vous remercions ;)
