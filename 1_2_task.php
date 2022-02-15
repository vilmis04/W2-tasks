<?php
declare(strict_types=1);

require './functions_1.php';
require './1_2_input.php';

if ($argv[1] === 'get_total') {
    $totalCount = getCountFromCategories($categories);
    $totalCost = getCostFromCategories($categories);
    printTotalFromCategories($totalCount, $totalCost);
} elseif ($argv[1] === 'total') {
    $categoryTarget = findCategoryTarget($categories, $argv[3]);
    if ($categoryTarget === []) {
        echo 'Category does not exist!'.PHP_EOL;
        die;
    }
    $categoryCount = countItems($categoryTarget);
    $categoryCost = costItems($categoryTarget);
    printTotalFromCategories($categoryCount, $categoryCost);
} else {
    $shoppingList = transformUserInputToArray($argv[1]);
    printBill($categories, $shoppingList, true);
}