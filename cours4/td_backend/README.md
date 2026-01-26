# Projet Vide
## Installation

```bash
docker compose run --rm php composer install
```

## Demarage du projet
```bash
docker compose up
```
=> http://localhost:8080

## Initialisation de la base de données + Peuplement

Pour lancer l'inialisation de la base de données, il faut lancer les commande dans le container docker :

```bash
docker exec -it [id du container] bash
```

Puis, une fois à l'intérieur il faut effectuer ces 2 commandes :
```bash
php bin/console doctrine:migration:migrate
````

Pour cette deuxième commande, il y aura une question sur la purge des données. On doit répondre "yes"
```bash
php bin/console doctrine:fixtures:load
```

## Accès aux vues :
Lien pour voir la liste des réalisateurs :  
http://localhost:8080/realisateur  
Lien pour voir la liste des films :  
http://localhost:8080/film
---



# Note si problemes de docker UNIQUEMENT :
Certain on des problème de performance/lenteur avec docker, vous pourrez utiliser votre composer/php local en gardant bien en tête que ce n'est pas une bonne pratique.

Sur la configuration docker ; vous verez une ligne "user" pour le service php. Elle sert a préciser quel user écrira sur la machine hote, par defaut l'identifiant du user et du groupe est 1000.  
Vous trouverez votre valeur avec la commande suivante, et changer si cela est necessaire.
```bash
echo "UID: ${UID}"
```

Il faut respecter ces conditions:
- `php8.2` avec les extension php`CType`, `iconv`, `session`, `simpleXML` et `Tokenizer`. Et bien sur `composer`

```bash
composer install
```

```bash
php -S 0.0.0.0:8080 -t public
```



