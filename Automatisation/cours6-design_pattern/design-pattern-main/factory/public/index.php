<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$vehicle = new \App\Entity\Car(100, 'gasoline');

echo "Coût au km : " . $vehicle->getCostPerKm() . "\n";
echo "Type de carburant : " . $vehicle->getFuelType() . "\n";