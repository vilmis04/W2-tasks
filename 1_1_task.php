<?php
declare(strict_types=1);

$inventory = [
    'apple' => [
        'count' => 5,
        'price' => 0.15,
    ],
    'carrot' => [
        'count' => 100,
        'price' => 0.01,
    ],
    'fish' => [
        'count' => 15,
        'price' => 5.5,
    ],
    'beer_bottle' => [
        'count' => 22,
        'price' => 1.3,
    ],
    'cheese' => [
        'count' => 1,
        'price' => 4.5,
    ],
    'wine_bottle' => [
        'count' => 4,
        'price' => 8,
    ],
    'bread' => [
        'count' => 11,
        'price' => 2.1,
    ],
    'carrot' => [
        'count' => 100,
        'price' => 0.01,
    ],
];

$shoppingList = transformUserInputToArray($argv[1]);
printBill($inventory, $shoppingList);

// functions

function transformUserInputToArray (string $input): array
{
    $inputArray = explode(' ', $input);
    $shoppingList = [];
    foreach ($inputArray as $row) {
        $item = explode(':', $row);
        $shoppingList[] = $item;
    }
    return $shoppingList;
}


function printBill(array $stock, array $list): void
{   
    $itemsToPrint = getPrintableList($stock, $list);
    $isInStock = $itemsToPrint[0]['inStock'];
    $listToPrint = $itemsToPrint[1];
    if ($isInStock) {
        $total = 0;
        foreach ($listToPrint as $item) {
            printItem($item['item'], $item['price'], $item['quantity']);
            $total += $item['price']*$item['quantity'];
        }
        echo '*****' . PHP_EOL;
        echo 'Total: ' . $total . PHP_EOL;
    } else {
        echo 'Error!' . PHP_EOL;
        foreach ($listToPrint as $item) {
            echo $item.PHP_EOL;
        }
    }
}

function printItem (string $item, float $price, int $quantity): void
{
    echo '*****' . PHP_EOL;
    echo $item . PHP_EOL;
    echo $price . ' * ' . $quantity . ' = ' . $price*$quantity;
    echo PHP_EOL;   
}

function getPrintableList (array $stock, array $list): array
{
    $existingItems = [];
    $lowStockItems = [];
    $result = [];
    foreach($list as $row) {
        $item = $row[0];
        $quantity = intval($row[1]);

        if (array_key_exists($item, $stock)) {
            $remainingStock = $stock[$item]['count'];
            if ($quantity <= $remainingStock) {
                $existingItems[] = [
                    'item' => $item,
                    'price' => $stock[$item]['price'],
                    'quantity' => $quantity
                ];
            } else {
                $lowStockItems[] = 'We only have '.$remainingStock.' '.$item.', '.'you asked for '.$quantity.' '.$item;
            }
        } else {
            $lowStockItems[] = 'Item '.$item.' not found in the shop!';
        }
    }
    
    if (count($lowStockItems)>0) {
        $result[] = ['inStock' => false];
        $result[] = $lowStockItems;
    } else {
        $result[] = ['inStock' => true];
        $result[] = $existingItems;
    }
    return $result;
}