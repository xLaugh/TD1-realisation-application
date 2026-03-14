<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Entity\VehicleFactory;
$vehicle = VehicleFactory::createVehicle('car', 100, 'gasoline');
echo "Coût au km : " . $vehicle->getCostPerKm() . "\n";
echo "Type de carburant : " . $vehicle->getFuelType() . "\n";