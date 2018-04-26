<?php
require 'vendor/autoload.php';
use RgMongodb\Orm\MongoBase;

define('MONGODB_CONFIG'        ,serialize(
    [
        'uri'           =>  'mongodb://rosegal_wr:rosegal@192.168.6.67:27017/rosegal',
        'database'      =>  'rosegal',
        'collectName'      =>  'GoodsOnSale',
        'username'      =>  'rosegal_wr',
        'password'      =>  'rosegal'
    ]
));

$loger = new MongoBase();
$list = $loger->skip(0)
				->limit(10)
				->find();
var_dump($list);
exit();

?>