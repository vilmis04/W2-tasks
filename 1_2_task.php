<?php
declare(strict_types=1);

require './functions_1.php';
require './1_2_input.php';

if ($argv[1] === 'get_total') {
    $totalCount = getCountFromCategories($categories);
    $totalCost = getCostFromCategories($categories);
    printTotalFromCategories($totalCount, $totalCost);
} elseif ($argv[1] === 'total') {
    $categoryTarget = $argv[3];
    $categoryCount = getCountFromCategories($categories[$categoryTarget]);
    $categoryCost = getCostFromCategories($categories[$categoryTarget]);
    printTotalFromCategories($categoryCount, $categoryCost);
} else {
    $shoppingList = transformUserInputToArray($argv[1]);
    printBill($categories, $shoppingList, true);
}

// functions

