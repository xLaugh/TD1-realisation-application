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

    public static function createVehicleByDistanceAndWeight(float $distanceKm, float $weightKg, $costPerKm, $fuelType): VehicleInterface
    {
        if ($weightKg > 200) {
            return new Truck($costPerKm, $fuelType);
        }
        if ($distanceKm < 20 && $weightKg <= 20) {
            return new Bicycle($costPerKm, $fuelType);
        }
        return new Car($costPerKm, $fuelType);
    }
}