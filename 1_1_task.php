<?php
declare(strict_types=1);

require './functions_1.php';

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
    ]
];


if ($argv[1] === 'get_total') {
    echo getTotal($inventory);
} else {
    $shoppingList = transformUserInputToArray($argv[1]);
    printBill($inventory, $shoppingList);
}