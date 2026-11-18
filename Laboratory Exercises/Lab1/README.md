# Лабораториска вежба 1А: Систем за управување со паркинг простори

Задолжени сте да изградите едноставен систем за управување со паркинг простори во град. Системот треба да овозможи паркирање на возила и нивно напуштање на паркингот, пресметување на цената за паркирање, како и следење на заработката.

---

## ДЕЛ 1 - задолжителен за сите студенти

### Енумерација `VehicleType`

Да се креира енумерација `VehicleType` која ги има вредностите `CAR`, `MOTORCYCLE` и `TRUCK`.

### Апстрактна класа `Vehicle`

Да се дефинира апстрактна класа `Vehicle` која содржи:

- `plate` - регистарска табличка (string)
- `owner` - име на сопственикот (string)
- `type` - тип на возилото (`VehicleType`)
- `hourlyRate` - цена за еден час паркирање во MKD (float)

Конструкторот на класата е `__construct($plate, $owner, $type, $hourlyRate)`. Класата `Vehicle` треба да има и апстрактен метод:

- `calculateFee(int $hours): float` - ја пресметува цената за паркирање за дадениот број на часови.

### Trait `HourlyBilling`

Да се дефинира trait `HourlyBilling` кој го имплементира методот `calculateFee`, така што цената се пресметува како бројот на часови помножен со `hourlyRate`. Ако бројот на часови е помал од 1, се наплаќа минимум 1 час.

### Класи `Car`, `Motorcycle` и `Truck`

Потребно е да се дефинираат класите `Car`, `Motorcycle` и `Truck` кои ќе наследуваат од класата `Vehicle`. Секоја од нив има фиксен тип на возило и фиксна цена за паркирање по час:

- Класата `Car` има тип `CAR` и цена од 60 MKD на час, а методот `calculateFee` го добива од trait-от `HourlyBilling`. Конструкторот е `__construct($plate, $owner)`.
- Класата `Motorcycle` има тип `MOTORCYCLE` и цена од 30 MKD на час, и исто така го користи trait-от `HourlyBilling`. Конструкторот е `__construct($plate, $owner)`.
- Класата `Truck` има тип `TRUCK` и цена од 120 MKD на час. Покрај основните атрибути, чува и `cargoWeight`, односно тежината на товарот во тони, па затоа конструкторот е `__construct($plate, $owner, $cargoWeight)`.

Класата `Truck` не го користи trait-от, туку самата го имплементира методот `calculateFee`. Цената се пресметува на ист начин како кај другите возила (со минимална наплата од 1 час), но ако товарот е потежок од 10 тони, на цената се додаваат уште 200 MKD.

> **Напомена:** Во конструкторите на наследените класи задолжително повикајте го конструкторот на родителската класа (`parent::__construct`).

### Класа `ParkingSpot`

Да се дефинира класа `ParkingSpot` која претставува едно паркинг место и содржи:

- `number` - број на местото (int)
- `allowedType` - тип на возило кое смее да се паркира на тоа место (`VehicleType`)
- `vehicle` - возилото кое е паркирано на местото, или `null` ако местото е слободно

Конструкторот е `__construct($number, $allowedType)`. Класата треба да има и метод `isFree(): bool` кој враќа `true` ако местото е слободно.

### Класа `ParkingLot`

Да се дефинира класа `ParkingLot` која претставува паркинг со повеќе паркинг места и содржи:

- `name` - име на паркингот (string)
- `spots` - асоцијативна низа на паркинг места, каде клуч е бројот на местото, а вредност е објект од класата `ParkingSpot`
- `revenue` - вкупна заработка на паркингот (почетна вредност 0)

Конструкторот е `__construct($name)`. Во класата `ParkingLot` да се имплементираат следните методи:

- `addSpot(ParkingSpot $spot)` - додава ново место во паркингот. Ако во паркингот веќе постои место со истиот број, се печати `Spot N already exists in LOT_NAME`.
- `findSpotByPlate(string $plate): ?ParkingSpot` - helper функција која го враќа местото на кое е паркирано возилото со дадената табличка. Ако го нема тоа возило во паркингот, враќа `null`.
- `park(Vehicle $vehicle): ?ParkingSpot` - го паркира возилото на првото слободно место чиј `allowedType` одговара на типот на возилото.
    - Ако возилото веќе е паркирано, се печати `Vehicle PLATE is already parked` и се враќа `null`.
    - Ако нема слободно место за тој тип на возило, се печати `No free TYPE spot in LOT_NAME` и се враќа `null`.
    - Во спротивно, возилото се паркира, се печати `Vehicle PLATE parked at spot N in LOT_NAME` и се враќа местото.
- `leave(string $plate, int $hours): ?float` - возилото со дадената табличка го напушта паркингот.
    - Ако возилото не е пронајдено, се печати `Vehicle PLATE not found in LOT_NAME` и се враќа `null`.
    - Ако е пронајдено, цената се пресметува со `calculateFee` и се додава на `revenue`, местото се ослободува, се печати `Vehicle PLATE left spot N. Fee: X MKD` и се враќа цената.
- `printStatus()` - ја печати моменталната состојба на паркингот во следниот формат:

```
--- LOT_NAME ---
Spot 1 [CAR]: SK-1234-AB (Petar)
Spot 2 [CAR]: free
```

---

## ДЕЛ 2 - задолжителен за сите студенти освен студентите од ССП

### Метод `countFreeSpots`

Во класата `ParkingLot` да се додаде метод `countFreeSpots(VehicleType $type): int` кој го враќа бројот на слободни места за дадениот тип на возило. За имплементацијата користете ги функциите `array_filter()` и `count()`.

### Класа `ParkingCompany`

Да се дефинира класа `ParkingCompany` која претставува компанија што управува со повеќе паркинзи и содржи:

- `name` - име на компанијата (string)
- `lots` - асоцијативна низа на паркинзи, каде клуч е името на паркингот, а вредност е објект од класата `ParkingLot`

Конструкторот е `__construct($name)`. Во класата `ParkingCompany` да се имплементираат следните методи:

- `addLot(ParkingLot $lot)` - додава паркинг во компанијата.
- `parkAnywhere(Vehicle $vehicle): ?ParkingLot` - го паркира возилото во паркингот кој има најмногу слободни места за неговиот тип.
    - Ако возилото веќе е паркирано во некој од паркинзите, се печати `Vehicle PLATE is already parked in LOT_NAME` и се враќа `null`.
    - Ако ниту еден паркинг нема слободно место за тој тип, се печати `No free TYPE spot in any lot` и се враќа `null`.
    - Во спротивно, возилото се паркира со методот `park` и се враќа паркингот во кој е паркирано.
- `getTotalRevenue(): float` - ја враќа вкупната заработка од сите паркинзи на компанијата. За имплементацијата користете ги функциите `array_map()` и `array_sum()`.

---

## Напомена

- Сите пораки се печатат со `echo` и завршуваат со нов ред (`\n`).
- Во пораките, `PLATE` е регистарската табличка, `N` е бројот на местото, `LOT_NAME` е името на паркингот, `X` е пресметаната цена, а `TYPE` е вредноста на енумерацијата (на пример `CAR`).
- Ако бараната вредност не постои (место, возило или паркинг), методите враќаат `null`.
- Користете type hinting за сите атрибути, параметри и повратни вредности.
- Сите класи може да се сместат во еден фајл `main.php`.

---

## Стартер код за тестирање

```php
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
```

# Лабораториска вежба 1Б: Систем за достава на храна

Задолжени сте да изградите едноставен систем за достава на храна. Системот треба да овозможи креирање на нарачки од мени, доделување на нарачките на курири, следење на доставата, како и оценување на куририте и рестораните.

---

## ДЕЛ 1 - задолжителен за сите студенти

### Енумерации `Category` и `OrderStatus`

Да се креираат две енумерации:

- `Category` ја претставува категоријата на ставка од менито и ги има вредностите `MAIN`, `DESSERT` и `DRINK`.
- `OrderStatus` го претставува статусот на нарачката и ги има вредностите `PENDING`, `DELIVERING` и `DELIVERED`.

### Класа `MenuItem`

Да се дефинира класа `MenuItem` која претставува една ставка од менито и содржи:

- `name` - име на ставката (string)
- `price` - цена во MKD (float)
- `category` - категорија (`Category`)

Конструкторот е `__construct($name, $price, $category)`.

### Класа `Order`

Да се дефинира класа `Order` која претставува нарачка на еден купувач и содржи:

- `id` - идентификатор на нарачката (int)
- `customerName` - име на купувачот (string)
- `items` - асоцијативна низа на ставки, каде клуч е името на ставката, а вредност е запис во формат `['item' => објект од тип MenuItem, 'quantity' => број]`
- `status` - статус на нарачката (`OrderStatus`), со почетна вредност `PENDING`

Конструкторот е `__construct($id, $customerName)`. Во класата `Order` да се имплементираат следните методи:

- `addItem(MenuItem $item, int $quantity)` - додава ставка во нарачката.
    - Ако количината е 0 или помала, се печати `Quantity must be greater than 0`.
    - Ако ставката веќе постои во нарачката, само ѝ се зголемува количината, без да се додава нов запис.
    - Во спротивно, се додава нов запис.
- `removeItem(string $name)` - ја брише ставката со даденото име од нарачката. Ако таква ставка не постои, се печати `Item NAME is not in order #ID`.
- `calculateTotal(): float` - ја враќа вкупната сума на нарачката, односно збирот од `price * quantity` за секоја ставка. Ако вкупната сума е поголема од 1000 MKD, се одобрува попуст од 10%.

### Trait `Rateable`

Да се дефинира trait `Rateable` кој овозможува оценување. Trait-от чува низа `ratings` со оцените (на почеток празна) и ги имплементира методите:

- `rate(int $stars)` - додава нова оцена. Оцената мора да биде од 1 до 5, а во спротивно се печати `Rating must be between 1 and 5`.
- `getAverageRating(): float` - ја враќа просечната оцена заокружена на 2 децимали. Ако сè уште нема оцени, враќа `0`.

### Апстрактна класа `Courier`

Да се дефинира апстрактна класа `Courier` која претставува курир што доставува нарачки. Класата го користи trait-от `Rateable` и содржи:

- `id` - идентификатор на курирот (string)
- `name` - име на курирот (string)
- `activeOrders` - асоцијативна низа на активни нарачки, каде клуч е id-то на нарачката

Конструкторот е `__construct($id, $name)`. Класата има два апстрактни методи:

- `getCapacity(): int` - го враќа максималниот број на активни нарачки што курирот може да ги има истовремено.
- `calculateDeliveryFee(float $distanceKm): float` - ја пресметува цената на доставата за дадената далечина.

Покрај нив, во класата `Courier` да се имплементираат и следните методи:

- `acceptOrder(Order $order): bool` - курирот ја прифаќа нарачката.
    - Ако нарачката веќе му е доделена на курирот, се печати `Order #ID is already assigned to NAME` и се враќа `false`.
    - Ако курирот веќе го достигнал својот капацитет, се печати `NAME cannot accept more orders` и се враќа `false`.
    - Во спротивно, нарачката се додава во `activeOrders`, нејзиниот статус се менува во `DELIVERING`, се печати `Order #ID accepted by NAME` и се враќа `true`.
- `completeDelivery(int $orderId)` - курирот ја доставува нарачката.
    - Ако нарачката не му е доделена на курирот, се печати `Order #ID is not assigned to NAME`.
    - Во спротивно, статусот на нарачката се менува во `DELIVERED`, нарачката се брише од `activeOrders` и се печати `Order #ID delivered by NAME`.
- `printActiveOrders()` - ги печати активните нарачки на курирот во следниот формат:

```
NAME active orders:
- Order #1 for Ana: 1377 MKD
```

Ако курирот нема активни нарачки, наместо листата се печати `- none`.

### Класи `BikeCourier` и `CarCourier`

На крај, да се дефинираат класите `BikeCourier` и `CarCourier` кои наследуваат од класата `Courier`.

- `BikeCourier` може да има најмногу 2 активни нарачки, а цената на доставата е 50 MKD плус 10 MKD по километар.
- `CarCourier` може да има најмногу 3 активни нарачки, а цената на доставата е 80 MKD плус 15 MKD по километар.

---

## ДЕЛ 2 - задолжителен за сите студенти освен студентите од ССП

### Класа `Restaurant`

Да се дефинира класа `Restaurant` која претставува ресторан и исто така го користи trait-от `Rateable`. Класата содржи:

- `name` - име на ресторанот (string)
- `menu` - асоцијативна низа на ставки, каде клуч е името на ставката, а вредност е објект од тип `MenuItem`

Конструкторот е `__construct($name)`. Во класата `Restaurant` да се имплементираат следните методи:

- `addMenuItem(MenuItem $item)` - додава ставка во менито. Ако ставка со истото име веќе постои, се печати `Item NAME already exists in the menu`.
- `findItem(string $name): ?MenuItem` - ја враќа ставката со даденото име. Ако таква ставка не постои, се печати `Item NAME not found in menu` и се враќа `null`.
- `getItemsByCategory(Category $category): array` - ги враќа сите ставки од менито кои припаѓаат на дадената категорија. За имплементацијата користете ја функцијата `array_filter()`.

### Класа `DeliveryService`

Да се дефинира класа `DeliveryService` која ги доделува нарачките на куририте. Класата чува асоцијативна низа `couriers`, каде клуч е id-то на курирот, и ги има следните методи:

- `addCourier(Courier $courier)` - додава курир во сервисот.
- `assignOrder(Order $order, float $distanceKm): ?Courier` - ја доделува нарачката на курирот кој има најдобра просечна оцена, а сè уште има слободен капацитет.
    - Ако повеќе курири имаат иста оцена, се избира оној кој е прв додаден.
    - Ако нема ниту еден курир со слободен капацитет, се печати `No available courier for order #ID` и се враќа `null`.
    - Во спротивно, курирот ја прифаќа нарачката (со методот `acceptOrder`), се печати `Delivery fee for order #ID: X MKD` и се враќа курирот.

---

## Напомена

- Сите пораки се печатат со `echo` и завршуваат со нов ред (`\n`).
- Во пораките, `ID` е id-то на нарачката, `X` е цената на доставата, а `NAME` е името на курирот, односно името на ставката кај пораките што почнуваат со `Item`.
- Ако бараната вредност не постои (ставка, нарачка или курир), методите враќаат `null`.
- Користете type hinting за сите атрибути, параметри и повратни вредности.
- При работа со низи користете ги функциите `isset()` за проверка дали постои клуч во низата, `unset()` за бришење на елемент од низата, како и `count()` и `array_sum()` за пресметки врз низите.
- Сите класи може да се сместат во еден фајл `main.php`.

---

## Стартер код за тестирање

```php
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
    echo "- {$item->name}: {$item->price} MKD\n";
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
```