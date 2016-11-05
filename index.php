<?php

//test script
//--------------------Tree::orderTreeUpDown--------------------
include 'Tree.php';

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

print_r(Tree::orderTreeUpDown($array));

//--------------------Scroll::flushMessage--------------------
include 'Scroll.php';

$scroll = new Scroll();

$scroll->flushMessage(range(0, 1000));

//--------------------Validator::rules--------------------
include 'Validator.php';

$rules = [
    [['mobile' => '手机号', 'phone' => '移动电话'], Validator::VALIDATOR_MOBILE, '手机号格式不正确'],
    [['createtime' => '时间', 'updatetime'], Validator::FILTER_FILTER, 'method' => 'strtotime',
        'value' => 'now', 'isEmpty' => 'empty'],
];

$formData = [
    'mobile' => '18888888888',
    'phone' => '1322222222',
    'createtime' => '2016-11-05',
    'updatetime' => 0
];
define('DEBUG', true);
$result = Validator::rules($formData, $rules);
var_export($result);
