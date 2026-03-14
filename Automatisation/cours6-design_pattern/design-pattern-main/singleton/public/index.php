<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Config;

$config = Config::getInstance();

echo "<pre>\n";
echo "=== Singleton Config ===\n\n";
echo "Valeur 'debug' : " . ($config->get('debug') ? 'true' : 'false') . "\n";
echo "Valeur 'apiKey' : " . $config->get('apiKey') . "\n";
echo "Valeur 'db' : " . json_encode($config->get('db'), JSON_PRETTY_PRINT) . "\n\n";

$config2 = Config::getInstance();
echo "Même instance (\$config === \$config2) : " . ($config === $config2 ? 'oui' : 'non') . "\n";
echo "</pre>";
