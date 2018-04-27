<?php
//用法测试
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

//分组统计,过滤，分组，排序，限制
$list = $loger->aggregate([
	['$match' => ['goods_sn' => '199341601']],
    ['$group' => ['_id' => '$username', 'count' => ['$sum' => 1]]],
    ['$sort' => ['count' => -1]],
    ['$limit' => 100],
]);

var_dump($list);
exit();

//时间条件区间查询
$where = ['addtime' => ['$gt' => '1524565523','$lt' => '1524566533'] ] ;
//查询指定的字段
$projection = ['goods_sn' => 1,'info' => 1,'username' => 1,'addtime' => 1];
//按照指定的字段排序
$sort = ['addtime' => 1 ,'goods_sn' => 1];


$list = $loger->where($where)
->skip(0)
->sort($sort)
->projection($projection)
				->limit(10)
				->find();
var_dump($list);
exit();


//模糊查询
$list = $GoodsOnSaleLog->skip($filter['start'])
                                ->limit($filter['page_size'])
                                ->findByRegex([
                                    'field' => 'goods_sn',
                                    'pattern' => $goods_sn,
                                    'mode' => 'i'
                                ]);

?>