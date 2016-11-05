<?php

include 'Tree.php';
//test
//Tree::orderTreeUpDown

$array = [
    ['id' => 1, 'pid' => 0, 'name' => '1'],
    ['id' => 59, 'pid' => 58, 'name' => '8'],
    ['id' => 37, 'pid' => 1, 'name' => '2'],
    ['id' => 54, 'pid' => 1, 'name' => '3'],
    ['id' => 60, 'pid' => 59, 'name' => '10'],
    ['id' => 35, 'pid' => 20, 'name' => '9'],
    ['id' => 58, 'pid' => 1, 'name' => '4'],
    ['id' => 11, 'pid' => 1, 'name' => '5'],
    ['id' => 20, 'pid' => 1, 'name' => '6'],
    ['id' => 57, 'pid' => 54, 'name' => '7'],
];
var_export(Tree::orderTreeUpDown($array));
