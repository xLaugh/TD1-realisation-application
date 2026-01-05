# Slim 4 API

Simple API using Slim v4 MySQL

## Run

- Create `.env` from `.env.exemple`
- Update environement variable

Pour lancer cet exercice, il faut :
- Créer un fichier .env en reprenant celui de l'exemple
- Remplir ce fichier .env avec les valeur que vous voulez
- Vous pouvez ensuite faire un `docker compose up -d`
- Il faut ensuite faire la commande `docker exec {Id du containeur php} composer install`
- Il faut ensuite faire la commande `docker exec {Id du containeur php} composer update`
- Il faut ensuite faire la commande `docker exec {Id du containeur php} composer require lukasoppermann/http-status`
- Il faut ensuite faire la commande `docker exec {Id du containeur php} composer dump-autoload`