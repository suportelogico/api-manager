<?php

require 'vendor/autoload.php';

use SuporteLogico\ApiManager\ControllerTester;


$ctrl = new ControllerTester();


echo $ctrl->create();

echo " \n";

//print_r( $ctrl->getServerheader() );

