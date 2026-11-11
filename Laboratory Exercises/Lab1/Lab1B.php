<?php

enum Category: string
{
    case MAIN = "main";
    case DESSERT = "dessert";
    case DRINK = "drink";
}

enum OrderStatus: string
{
    case PENDING = "pending";
    case DELIVERING = "delivering";
    case DELIVERED = "delivered";
}

class MenuItem
{
    private string $name;
    private float $price;
    private Category $category;

    public function __construct(string $name, float $price, Category $category)
    {
        $this->name = $name;
        $this->price = $price;
        $this->category = $category;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }
}

class Order
{
    private int $id;
    private string $customerName;
    private array $items = [];
    public OrderStatus $status = OrderStatus::PENDING;

    public function __construct(int $id, string $customerName)
    {
        $this->id = $id;
        $this->customerName = $customerName;
    }

    public function addItem(MenuItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            echo "Quantity must be greater than 0\n";
            return;
        }
        foreach ($this->items as $i) {
            if ($i['item']->getName() == $item->getName()) {
                $this->items[$i['item']->getName()]['quantity'] += $quantity;
                return;
            }
        }
        $this->items[$item->getName()] = ['item' => $item, 'quantity' => $quantity];
    }

    public function removeItem(string $name): void
    {
        if (isset($this->items[$name])) {
            unset($this->items[$name]);
            return;
        }
        echo "Item $name is not in order " . $this->id . "\n";
    }

    public function calculateTotal(): float
    {
        $result = 0;
        foreach ($this->items as $item) {
            $i = $item['item'];
            $quantity = $item['quantity'];
            $result += $i->getPrice() * $quantity;
        }
        if ($result > 1000) {
            $result *= 0.9;
        }
        return $result;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): void
    {
        $this->status = $status;
    }
}

trait Rateable
{
    private array $ratings = [];

    public function rate(int $stars): void
    {
        if ($stars < 1 || $stars > 5) {
            echo "Rating must be between 1 and 5\n";
            return;
        }
        $this->ratings[] = $stars;
    }

    public function getAverageRating(): float
    {
        if (sizeof($this->ratings) == 0)
            return 0.00;
        return array_sum($this->ratings) / count($this->ratings);
    }
}

abstract class Courier
{
    use Rateable;

    protected string $id;
    public string $name;
    protected array $activeOrders = [];

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    abstract public function getCapacity(): int;

    abstract public function calculateDeliveryFee(float $distanceKm): float;

    public function acceptOrder(Order $order): bool
    {
        if (isset($this->activeOrders[$order->getId()])) {
            echo "Order " . $order->getId() . " is already assigned to " . $this->name . "\n";
            return false;
        }
        if ($this->getCapacity() == sizeof($this->activeOrders)) {
            echo $this->name . " cannot accept more orders\n";
            return false;
        }
        $order->setStatus(OrderStatus::DELIVERING);
        $this->activeOrders[$order->getId()] = $order;
        echo "Order " . $order->getId() . " accepted by " . $this->name . "\n";
        return true;
    }

    public function completeDelivery(int $orderId): void
    {
        if (!isset($this->activeOrders[$orderId])) {
            echo "Order $orderId is not assigned to " . $this->name . "\n";
            return;
        }
        $order = $this->activeOrders[$orderId];
        $order->setStatus(OrderStatus::DELIVERED);
        unset($this->activeOrders[$orderId]);
        echo "Order $orderId delivered by " . $this->name . "\n";
    }

    public function printActiveOrders(): void
    {
        echo $this->name . " active orders:\n";
        if (sizeof($this->activeOrders) == 0) {
            echo "none\n";
            return;
        }
        foreach ($this->activeOrders as $order) {
            echo "- Order #" . $order->getId() . " for " . $order->getCustomerName() . ": " . $order->calculateTotal() . " MKD\n";
        }
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getActiveOrders(): array
    {
        return $this->activeOrders;
    }
}

class BikeCourier extends Courier
{
    public function getCapacity(): int
    {
        return 2;
    }

    public function calculateDeliveryFee(float $distanceKm): float
    {
        return $distanceKm * 10 + 50;
    }
}

class CarCourier extends Courier
{
    public function getCapacity(): int
    {
        return 3;
    }

    public function calculateDeliveryFee(float $distanceKm): float
    {
        return $distanceKm * 15 + 80;
    }
}

class Restaurant
{
    use Rateable;

    public string $name;
    private array $menu = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addMenuItem(MenuItem $item): void
    {
        if (isset($this->menu[$item->getName()])) {
            echo "Item " . $item->getName() . " already exists in the menu\n";
            return;
        }
        $this->menu[$item->getName()] = $item;
    }

    public function findItem(string $name): ?MenuItem
    {
        if (!isset($this->menu[$name])) {
            echo "Item $name not found in menu\n";
            return null;
        }
        return $this->menu[$name];
    }

    public function getItemsByCategory(Category $category): array
    {
        return array_filter($this->menu, function ($item) use ($category) {
            return $item->getCategory() == $category;
        });
    }
}

class DeliveryService
{
    private array $couriers = [];

    public function addCourier(Courier $courier)
    {
        $this->couriers[$courier->getId()] = $courier;
    }

    public function assignOrder(Order $order, float $distanceKm): ?Courier
    {
        $candidates = [];

        foreach ($this->couriers as $courier) {
            if (sizeof($courier->getActiveOrders()) < $courier->getCapacity()) {
                array_push($candidates, $courier);
            }
        }

        if (sizeof($candidates) == 0) {
            echo "No available courier for order " . $order->getId() . "\n";
            return null;
        }

        $result = $candidates[0];
        foreach ($candidates as $candidate) {
            if ($candidate->getAverageRating() > $result->getAverageRating()) {
                $result = $candidate;
            }
        }

        $result->acceptOrder($order);
        $fee = $result->calculateDeliveryFee($distanceKm);
        echo "Delivery fee for order " . $order->getId() . ": $fee MKD\n";
        return $result;
    }
}

echo "<pre>";
// ===== ДЕЛ 1 =====

// Креирање на ставки од менито
$pizza = new MenuItem("Pizza Margherita", 450.0, Category::MAIN);
$burger = new MenuItem("Burger", 380.0, Category::MAIN);
$cake = new MenuItem("Cheesecake", 220.0, Category::DESSERT);
$cola = new MenuItem("Coca-Cola", 90.0, Category::DRINK);

// Креирање на нарачки
$order1 = new Order(1, "Ana");
$order1->addItem($pizza, 2);
$order1->addItem($cola, 2);
$order1->addItem($pizza, 1); // kolicinata stanuva 3
$order1->addItem($cake, 0);  // nevalidna kolicina
echo "Order #1 total: " . $order1->calculateTotal() . " MKD\n"; // (3*450 + 2*90) * 0.9 = 1377

$order2 = new Order(2, "Marko");
$order2->addItem($burger, 1);
$order2->addItem($cola, 1);
$order2->removeItem("Cheesecake"); // ja nema vo narackata
echo "Order #2 total: " . $order2->calculateTotal() . " MKD\n"; // 470

$order3 = new Order(3, "Elena");
$order3->addItem($cake, 2);

// Курири
$bike = new BikeCourier("C01", "Stefan");
$car = new CarCourier("C02", "Jovana");

$bike->acceptOrder($order1);
$bike->acceptOrder($order2);
$bike->acceptOrder($order3); // nema kapacitet
$bike->acceptOrder($order1); // veke e dodelena
$car->acceptOrder($order3);

$bike->printActiveOrders();

echo "Bike fee for 3.5 km: " . $bike->calculateDeliveryFee(3.5) . " MKD\n"; // 85
echo "Car fee for 3.5 km: " . $car->calculateDeliveryFee(3.5) . " MKD\n";   // 132.5

$bike->completeDelivery(1);
$bike->completeDelivery(3); // ne e dodelen na Stefan
echo "Order #1 status: {$order1->status->value}\n";

$bike->rate(5);
$bike->rate(4);
$bike->rate(7); // невалидна оцена
echo "{$bike->name} rating: " . $bike->getAverageRating() . "\n"; // 4.5

$bike->printActiveOrders();

// ===== ДЕЛ 2 =====
echo "\n";

$restaurant = new Restaurant("Kaj Mama");
$restaurant->addMenuItem($pizza);
$restaurant->addMenuItem($burger);
$restaurant->addMenuItem($cake);
$restaurant->addMenuItem($cola);
$restaurant->addMenuItem($pizza); // veke postoi
$restaurant->findItem("Sushi");   // ne postoi

echo "Main dishes:\n";
foreach ($restaurant->getItemsByCategory(Category::MAIN) as $item) {
    echo "- {$item->getName()}: {$item->getPrice()} MKD\n";
}

$restaurant->rate(5);
$restaurant->rate(3);
echo "{$restaurant->name} rating: " . $restaurant->getAverageRating() . "\n"; // 4

$car->rate(5); // Jovana 5.0, Stefan 4.5

$service = new DeliveryService();
$service->addCourier($bike);
$service->addCourier($car);

foreach ([4 => "Petar", 5 => "Iva", 6 => "Nikola", 7 => "Sara"] as $id => $customer) {
    $order = new Order($id, $customer);
    $order->addItem($restaurant->findItem("Burger"), 1);
    $service->assignOrder($order, 4.0);
}
// #4, #5 -> Jovana (podobra ocena), #6 -> Stefan, #7 -> nema sloboden kurir

$car->printActiveOrders();

echo "</pre>";