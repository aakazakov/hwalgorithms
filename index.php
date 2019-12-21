<?php

include 'hw4/hw4_1new.php';

//===========================
$calc = new Calculator();

$calc->insert('(x + 4)^2 + 7*y - z');

//print_r($calc->getResult());

//$calc->insert('1+2+3+4');
print_r($calc->getTree());
