<?php

namespace App\Entity;

class VehicleFactory {
    public static function createVehicle($type, $costPerKm, $fuelType) {
        switch ($type) {
            case 'car':
                return new Car($costPerKm, $fuelType);
            case 'bicycle':
                return new Bicycle($costPerKm, $fuelType);
            case 'truck':
                return new Truck($costPerKm, $fuelType);
            default:
                throw new \InvalidArgumentException("Type de véhicule inconnu : $type");
        }
    }
}