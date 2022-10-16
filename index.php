<?php

require 'vendor/autoload.php';

use SuporteLogico\ApiManager\Controller;

//echo "GERENCIADOR DE APIs \n";

$ctrl = new Controller();

echo $ctrl->getMethod();



