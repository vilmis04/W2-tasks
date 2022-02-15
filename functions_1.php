<?php
declare(strict_types=1);

function transformUserInputToArray (string $input): array {
    $inputArray = explode(' ', $input);
    $shoppingList = [];
    foreach ($inputArray as $row) {
        $item = explode(':', $row);
        $shoppingList[] = $item;
    }
    return $shoppingList;
}


function printBill(array $stock, array $list, bool $categoriesExists = false): void {
    $itemsToPrint = getPrintableList($stock, $list, $categoriesExists);
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

function printItem (string $item, float $price, int $quantity): void {
    echo '*****' . PHP_EOL;
    echo $item . PHP_EOL;
    echo $price . ' * ' . $quantity . ' = ' . $price*$quantity;
    echo PHP_EOL;   
}

function getPrintableList (array $stock, array $list, bool $categoriesExists): array {
    if ($categoriesExists) {
        return getListFromCategories($stock, $list);
    } else {
        return getListNoCategories($stock, $list);
    }
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

function printTotalFromCategories(int $totalCount, float $totalCost): void {
    echo 'Total count: '.$totalCount.PHP_EOL.'Total cost: '.$totalCost.PHP_EOL;
}

function getCountFromCategories(array $categories): int {
    $totalCount = 0;
    foreach ($categories as $category) {
        $totalCount += countItems($category);
    }
    return $totalCount;
}

function countItems(array $list): int {
    $count = 0;
    foreach ($list as $key => $value) {
        if ($key === 'items') {
            foreach ($value as $item) {
                $count += $item['count'];
            }
        } elseif ($key === 'categories') {
            $count += getCountFromCategories($value);
        }
    }
    return $count;
}

function getCostFromCategories(array $categories): float {
    $totalCost = 0;
    foreach ($categories as $category) {
        $totalCost += costItems($category);
    }
    return $totalCost;
}

function costItems(array $list): float {
    $cost = 0;
    foreach ($list as $key => $value) {
        if ($key === 'items') {
            foreach ($value as $item) {
                $cost += $item['count']*$item['price'];
            }
        } elseif ($key === 'categories') {
            $cost += getCostFromCategories($value);
        }
    }
    return $cost;
}

function getListNoCategories(array $stock, array $list): array {
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

function getListFromCategories(array $stock, array $list): array {
    $result = [];
    $existingItems = [];
    $lowStockItems = [];

    foreach($list as $row) {
        // echo 'NEW ITEM -----------------------------------------------'.PHP_EOL;
        $item = $row[0];
        // echo $item.PHP_EOL;
        $quantity = intval($row[1]);
        $itemExists = false;
        $stored = NULL;
        foreach ($stock as $key => $category) {
            // echo 'NEW CATEGORY -----------------------------------------------'.PHP_EOL;
            // echo $key.PHP_EOL;

            $result = lookUpItemInCategory($category, $item);
            // var_dump($result);
            if ($result['itemExists']) {
                $itemExists = true;
                $data = $result['itemData'];
                if ($data['stock'] >= $quantity) {
                    $existingItems[] = [
                        'item' => $item,
                        'price' => $data['price'],
                        'quantity' => $quantity
                    ];
                } else {
                    $stored = 'We only have '.$data['stock'].' '.$item.', '.'you asked for '.$quantity.' '.$item;
                }
            }
        }
        if ($itemExists && $stored != NULL) {
            $lowStockItems[] = $stored;
        } elseif (!$itemExists) {
            $lowStockItems[] = 'Item '.$item.' not found in the shop!';
        }
    }


    $result[] = count($lowStockItems) === 0 ? ['inStock' => true] : ['inStock' => false];
    $result[] = count($lowStockItems) === 0 ? $existingItems : $lowStockItems;
    return $result;
}

function lookUpItemInCategory(array $category, string $item): array {
    $result = [
        'itemExists' => false,
        'itemData' => []
    ];
    $itemFound = false;
    foreach ($category as $key => $value) {
        if ($key === 'items') {
            foreach ($value as $product => $data) {
                if ($product === $item) {
                    $itemFound = true;
                    $result['itemExists'] = true;
                    $result['itemData'] = [
                        'price' => $data['price'],
                        'stock' => $data['count'],
                    ];
                }
            }
        }
        if (!$itemFound && $key === 'categories') {
            foreach ($value as $subcategory) {
                $result = lookUpItemInCategory($subcategory, $item);
            }
        }
    }
    return $result;
}

// function getItemDataFromSingleCategory(array $category, array $list): array {
//     foreach($list as $row) {
//         $item = $row[0];
//         $quantity = intval($row[1]);

//         if (array_key_exists($item, $category['items'])) {
//             $remainingStock = $stock[$item]['count'];
//             if ($quantity <= $remainingStock) {
//                 $existingItems[] = [
//                     'item' => $item,
//                     'price' => $stock[$item]['price'],
//                     'quantity' => $quantity
//                 ];
//             } else {
//                 $lowStockItems[] = 'We only have '.$remainingStock.' '.$item.', '.'you asked for '.$quantity.' '.$item;
//             }
//         } else {
//             $lowStockItems[] = 'Item '.$item.' not found in the shop!';
//         }
//     }

//     return [];
// }