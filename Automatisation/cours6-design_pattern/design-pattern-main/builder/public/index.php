<?php
require('../vendor/autoload.php');

use App\MySQLQueryBuilder;

$builder = new MySQLQueryBuilder();

// Construction fluide d'une requête
$query = $builder->table('users')
    ->select(['id', 'username', 'email'])
    ->where('age', '>', '18')
    ->build();

echo "Premier test : \n";
echo $query . "\n";

// Autre test : sélection par défaut (*)
$simpleQuery = (new MySQLQueryBuilder())
    ->table('products')
    ->where('price', '<', '50')
    ->build();

echo "Deuxième test : \n";
echo $simpleQuery . "\n";