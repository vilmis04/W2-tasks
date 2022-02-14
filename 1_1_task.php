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

// var_dump($shoppingList);


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
        $total = 0;
        foreach($list as $row) {
            $item = $row[0];
            $quantity = $row[1];
            $subtotal = $stock[$item]['price'] * $quantity;
            $total += $subtotal;
            
            echo '*****' . PHP_EOL;
            echo $item . PHP_EOL;
            echo $stock[$item]['price'] . ' * ' . $quantity . ' = ' . $subtotal;
            echo PHP_EOL;
        }
        echo '*****' . PHP_EOL;
        echo 'Total: ' . $total . PHP_EOL;
    }