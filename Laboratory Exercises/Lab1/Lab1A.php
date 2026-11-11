<?php

enum VehicleType: string
{
    case CAR = 'car';
    case MOTORCYCLE = 'motorcycle';
    case TRUCK = 'truck';
}

abstract class Vehicle
{
    protected string $plate;
    protected string $owner;
    protected VehicleType $type;
    protected float $hourlyRate;

    public function __construct(string $plate, string $owner, VehicleType $type, float $hourlyRate)
    {
        $this->plate = $plate;
        $this->owner = $owner;
        $this->type = $type;
        $this->hourlyRate = $hourlyRate;
    }

    public function getPlate(): string
    {
        return $this->plate;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getType(): VehicleType
    {
        return $this->type;
    }

    public function getHourlyRate(): float
    {
        return $this->hourlyRate;
    }
}

trait HourlyBilling
{
    public function calculateFee(int $hours): float
    {
        if ($hours < 1)
            return $this->hourlyRate;
        else
            return $hours * $this->hourlyRate;
    }
}

class Car extends Vehicle
{
    use HourlyBilling;

    public function __construct(string $plate, string $owner)
    {
        parent::__construct($plate, $owner, VehicleType::CAR, 60);
    }
}

class Motorcycle extends Vehicle
{
    use HourlyBilling;

    public function __construct(string $plate, string $owner)
    {
        parent::__construct($plate, $owner, VehicleType::MOTORCYCLE, 30);
    }
}

class Truck extends Vehicle
{
    private float $cargoWeight;

    public function __construct(string $plate, string $owner, float $cargoWeight)
    {
        parent::__construct($plate, $owner, VehicleType::TRUCK, 120);
        $this->cargoWeight = $cargoWeight;
    }

    public function calculateFee(int $hours): float
    {
        $result = 0;
        if ($hours < 1) {
            $result = $this->hourlyRate;
        } else {
            $result = $this->hourlyRate * $hours;
        }

        if ($this->cargoWeight > 10)
            $result += 200;
        return $result;
    }
}

class ParkingSpot
{
    private int $number;
    private VehicleType $allowedType;
    private ?Vehicle $vehicle = null;

    public function __construct(int $number, VehicleType $allowedType)
    {
        $this->number = $number;
        $this->allowedType = $allowedType;
    }

    public function isFree(): bool
    {
        return is_null($this->vehicle);
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getAllowedType(): VehicleType
    {
        return $this->allowedType;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): void
    {
        $this->vehicle = $vehicle;
    }
}

class ParkingLot
{
    public string $name;
    private array $spots = [];
    public float $revenue = 0;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addSpot(ParkingSpot $spot): void
    {
        $number = $spot->getNumber();
        foreach ($this->spots as $s) {
            if (isset($this->spots[$number])) {
                echo "Spot $number already exists in $this->name\n";
                return;
            }
        }
        $this->spots[$number] = $spot;
    }

    public function findSpotByPlate(string $plate): ?ParkingSpot
    {
        foreach ($this->spots as $s) {
            $v = $s->getVehicle();
            if ($v !== null && $v->getPlate() === $plate) {
                return $s;
            }
        }
        return null;
    }

    public function park(Vehicle $vehicle): ?ParkingSpot
    {
        if ($this->findSpotByPlate($vehicle->getPlate()) != null) {
            echo "Vehicle " . $vehicle->getPlate() . " is already parked\n";
            return null;
        }

        foreach ($this->spots as $spot) {
            if ($spot->getAllowedType() === $vehicle->getType() && $spot->isFree()) {

                $spot->setVehicle($vehicle);

                echo "Vehicle " . $vehicle->getPlate() . " parked at spot " . $spot->getNumber() . " in $this->name\n";

                return $spot;
            }
        }

        echo "No free " . $vehicle->getType()->name . " spot in $this->name\n";
        return null;
    }

    public function leave(string $plate, int $hours): ?float
    {
        $spot = $this->findSpotByPlate($plate);
        if ($spot === null) {
            echo "Vehicle $plate not found in $this->name\n";
            return null;
        }
        $vehicle = $spot->getVehicle();
        $fee = $vehicle->calculateFee($hours);
        $this->revenue += $fee;
        $spot->setVehicle(null);
        echo "Vehicle $plate left spot " . $spot->getNumber() . ". Fee: $fee MKD\n";
        return $fee;
    }

    public function printStatus(): void
    {
        echo "--- $this->name ---\n";

        foreach ($this->spots as $spot) {
            $number = $spot->getNumber();
            $type = $spot->getAllowedType()->name;
            $vehicle = $spot->getVehicle();
            if ($vehicle === null) {
                echo "Spot $number [$type]: free\n";
            } else {
                echo "Spot $number [$type]: " . $vehicle->getPlate() . " (" . $vehicle->getOwner() . ")\n";
            }
        }
    }

    public function countFreeSpots(VehicleType $type): int
    {
        $result = array_filter($this->spots, function ($spot) use ($type) {
            return $spot->getAllowedType() === $type && $spot->isFree();
        });
        return count($result);
    }
}

class ParkingCompany
{
    public string $name;
    private array $lots = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addLot(ParkingLot $lot): void
    {
        $this->lots[$lot->name] = $lot;
    }

    public function parkAnywhere(Vehicle $vehicle): ?ParkingLot
    {
        $bestLot = null;
        $maxFree = 0;

        foreach ($this->lots as $lot) {
            if ($lot->findSpotByPlate($vehicle->getPlate()) != null) {
                echo "Vehicle " . $vehicle->getPlate() . " is already parked in " . $lot->name . "\n";
                return null;
            }
        }

        foreach ($this->lots as $lot) {
            $free = $lot->countFreeSpots($vehicle->getType());

            if ($free > $maxFree) {
                $maxFree = $free;
                $bestLot = $lot;
            }
        }

        if ($bestLot === null) {
            echo "No free " . $vehicle->getType()->name . " spot in any lot\n";
            return null;
        }

        $bestLot->park($vehicle);
        return $bestLot;
    }

    public function getTotalRevenue(): float
    {
        return array_sum(array_map(function ($lot) {
            return $lot->revenue;
        }, $this->lots));
    }
}

echo "<pre>";
// ===== ДЕЛ 1 =====

// Креирање на возила
$car1 = new Car("SK-1234-AB", "Petar");
$car2 = new Car("SK-5678-CD", "Ana");
$moto = new Motorcycle("SK-111-MM", "Marko");
$truck = new Truck("SK-9999-TT", "Transport DOO", 12.5);

// Креирање на паркинг со места
$center = new ParkingLot("Centar");
$center->addSpot(new ParkingSpot(1, VehicleType::CAR));
$center->addSpot(new ParkingSpot(2, VehicleType::CAR));
$center->addSpot(new ParkingSpot(3, VehicleType::MOTORCYCLE));
$center->addSpot(new ParkingSpot(4, VehicleType::TRUCK));
$center->addSpot(new ParkingSpot(2, VehicleType::CAR)); // место 2 веќе постои

// Паркирање
$center->park($car1);
$center->park($car2);
$center->park($moto);
$center->park($truck);
$center->park($car1);                          // веќе е паркирано
$center->park(new Car("SK-0000-ZZ", "Elena")); // нема слободно место за CAR

$center->printStatus();

// Напуштање на паркингот
$center->leave("SK-1234-AB", 3); // 3 * 60 = 180
$center->leave("SK-9999-TT", 2); // 2 * 120 + 200 = 440
$center->leave("SK-111-MM", 0);  // минимум 1 час = 30
$center->leave("SK-1234-AB", 1); // возилото не е пронајдено

$center->printStatus();
echo "Revenue {$center->name}: {$center->revenue} MKD\n";

// ===== ДЕЛ 2 =====
echo "\n";

$airport = new ParkingLot("Aerodrom");
$airport->addSpot(new ParkingSpot(1, VehicleType::CAR));
$airport->addSpot(new ParkingSpot(2, VehicleType::CAR));
$airport->addSpot(new ParkingSpot(3, VehicleType::CAR));

echo "Free CAR spots in Centar: " . $center->countFreeSpots(VehicleType::CAR) . "\n";
echo "Free CAR spots in Aerodrom: " . $airport->countFreeSpots(VehicleType::CAR) . "\n";

$company = new ParkingCompany("City Parking");
$company->addLot($center);
$company->addLot($airport);

$company->parkAnywhere(new Car("SK-2222-EE", "Ivana"));            // Aerodrom (повеќе слободни места)
$company->parkAnywhere($car2);                                     // веќе е паркирано во Centar
$company->parkAnywhere(new Truck("SK-3333-TR", "Logistika", 8.0)); // Centar
$company->parkAnywhere(new Truck("SK-4444-TR", "Logistika", 5.0)); // нема слободно место за TRUCK

$airport->leave("SK-2222-EE", 5); // 5 * 60 = 300

echo "Total revenue {$company->name}: {$company->getTotalRevenue()} MKD\n";

echo "</pre>";