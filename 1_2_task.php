<?php
declare(strict_types=1);

require './functions_1.php';
require './functions_2.php';
require './1_2_input.php';

if ($argv[1] === 'get_total') {
    $totalCount = getCountFromCategories($categories);
    $totalCost = getCostFromCategories($categories);
    printTotalFromCategories($totalCount, $totalCost);
} else {
    $shoppingList = transformUserInputToArray($argv[1]);
    printBill($inventory, $shoppingList);
}

// functions

