Analyse du code :  
1. Languages identifiés :
    - sql
    - php
    - js
    - scss
2. Framework utilisés :  
    - Twig
    - Slim
    - Laravel avec une utilisation de la database de illuminate

3. Identifcation du but général de l'application :
   - Le but général de l'application et de gérer un site d'annonce en France tel que Leboncoin.
4. Estimation pour lancer l'application : 
   - Pour lancer l'application, il faut déjà créer un fichier .env si on veut modifier quelque donnée du docker-compose.yml.. 
   - Après çaje pense qu'il faut faire un docker compose.
   - Et ensuite faire un compose install à l'intérieur du containeur.

Prise en main :  
Pour lancer l'application il suffit de :
   1. Reprendre le fichier .env.exemple et le remplir avec les valeurs souhaités
   2. Faire la commande ```docker-compose up -d --build```
   3. Puis faire la commande ```docker-compose exec php composer install --no-security-blocking```. 
```--no-security-blocking``` permet de lancer composer install sans avoir à mettre à jour les version de certain framework non maintenu.


Préparation de la maintenance :  
1. Framework à mettre à jour :
   - Slim :   
     - version actuel : 2.6.3
     - dernière version : 4.15.1
   - PHP :  
     - version actuel : 7.4 (Version non supporté)
     - dernière version : 8.5

   

2. Dépendance à mettre à jour
   - Illuminate/database :
     - version actuel : 4.2.9
     - dernière version : 12.53.0
   - Twig :
      - version actuel : 1.44.8
      - dernière version : 3.21.1
   - kylekatarnls/update-helper : 
     - Version non mis à jour depuis 6 ans 
   - Symfony/translation :  
     - version actuel : 4.4.47 (Version non supporté)


|         Mettre à jour          | Temps de modification | Impact de la modification |
|:------------------------------:|:---------------------:|:-------------------------:|
|              PHP               |           2           |            10             |
|              Slim              |           9           |             7             |
| Illuminate/database (Eloquent) |           8           |             9             |
|              Twig              |           4           |             6             |

Réaliser la maintenance :  
1. Mise à jour de PHP :  
   - 7.4 -> 8.1
