<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Entity\VehicleFactory;

echo "<pre>\n";

function afficherVehicule($vehicle, string $label = ''): void
{
    $nom = (new \ReflectionClass($vehicle))->getShortName();
    echo $label ? "[$label] " : '';
    echo "$nom — coût/km : {$vehicle->getCostPerKm()}, carburant : {$vehicle->getFuelType()}\n";
}

echo "=== 1. createVehicle (par type) ===\n";
afficherVehicule(VehicleFactory::createVehicle('car', 0.50, 'gasoline'), 'car');
afficherVehicule(VehicleFactory::createVehicle('bicycle', 0.05, 'muscle'), 'bicycle');
afficherVehicule(VehicleFactory::createVehicle('truck', 1.20, 'diesel'), 'truck');

echo "\n=== 2. createVehicleByDistanceAndWeight (distance + poids) ===\n";
afficherVehicule(
    VehicleFactory::createVehicleByDistanceAndWeight(10, 15, 0.05, 'muscle'),
    '10 km, 15 kg → vélo attendu'
);
afficherVehicule(
    VehicleFactory::createVehicleByDistanceAndWeight(50, 30, 0.50, 'gasoline'),
    '50 km, 30 kg → voiture attendue'
);
afficherVehicule(
    VehicleFactory::createVehicleByDistanceAndWeight(5, 250, 1.20, 'diesel'),
    '5 km, 250 kg → camion attendu'
);
afficherVehicule(
    VehicleFactory::createVehicleByDistanceAndWeight(15, 25, 0.50, 'gasoline'),
    '15 km, 25 kg (>20) → voiture attendue'
);

echo "</pre>";