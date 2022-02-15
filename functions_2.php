<?php
declare(strict_types=1);

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