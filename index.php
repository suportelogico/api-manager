<?php

require 'vendor/autoload.php';

use Src\Controller;

echo "GERENCIADOR DE APIs \n";

$ctrl = new Controller();

echo $ctrl->getMethod();

