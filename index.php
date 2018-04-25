<?php

use DUMPING\PRINTING\DumpDef;
require 'vendor/autoload.php';


function luolaifa($data) 
{
    return $data;
}

$loger = new DumpDef(['channel' => 'demo', 'logPath' => 'demo.log', 'level' => 'info', 'proccessor' => 'luolaifa']);

$loger->warning('this is a test');

$loger->debug('this is a test');


$loger->info('this is a test');

$loger->notice('this is a test');

$loger->error('this is a test');

?>