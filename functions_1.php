<?php

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
        printRequestedItems($listToPrint);
    } else {
        printErrorMessages($listToPrint);
    }
}

function printRequestedItems(array $list): void {
    $total = 0;
    foreach ($list as $item) {
        printItem($item['item'], $item['price'], $item['quantity']);
        $total += $item['price']*$item['quantity'];
    }
    echo '*****' . PHP_EOL;
    echo 'Total: ' . $total . PHP_EOL;
}

function printErrorMessages(array $list): void {
    echo 'Error!' . PHP_EOL;
    foreach ($list as $item) {
        echo $item.PHP_EOL;
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
    
    $result[] = count($lowStockItems) === 0 ? ['inStock' => true] : ['inStock' => false];
    $result[] = count($lowStockItems) === 0 ? $existingItems : $lowStockItems;

    return $result;
}

function getTotal(array $stock): string {
    $totalCost = 0;
    $totalCount = 0;
    foreach ($stock as $item) {
        $totalCount += $item['count'];
        $totalCost += $item['price'] * $item['count'];
    }

    return 'Total count: '.$totalCount.PHP_EOL.'Total cost: '.$totalCost.PHP_EOL;
}