<?php
/**
 * MongoDb
 * 
 * 用来存储日志
 * 
 * @author Python Luo <laifaluo@126.com>
 * 
 * */
 
namespace RgMongodb\Orm;
use RgMongodb\Orm\ChainTrait as ChainTrait;
use MongoDB; 

class MongoBase 
{   
    use ChainTrait;
    
    public $database;
    
    public $collectName;
    
    public $unixUri;
    
    public $collection;
    
    public function __construct() 
    {
        $mongodbConfig = unserialize(MONGODB_CONFIG);
        
        $this->database = $this->database ? $this->database : $mongodbConfig['database'];
        $this->collectName = $this->collectName ? $this->collectName : $mongodbConfig['collectName'];
        $this->unixUrl = isset($mongodbConfig['uri']) ? $mongodbConfig['uri'] : '';
        $this->collection = (new MongoDB\Client($this->unixUrl))->{($this->database)}->{($this->collectName)};
    }
    
    public function selectDb($db)
    {
        $this->database = $db;
        $this->collection = (new MongoDB\Client($this->unixUrl))->{($this->database)}->{($this->collectName)};
    }
    
    
    public function selectCollection($collectName)
    {
        $this->collectName = $collectName;
        $this->collection = (new MongoDB\Client($this->unixUrl))->{($this->database)}->{($this->collectName)};
    }
    
    
    public function createIndex($index)
    {
        
        $indexName = $this->collection->createIndex(
            $index
        );
        return $indexName;
    }
    
    public function insertOne($data)
    {
        $insertOneResult = $this->collection->insertOne($data);
        $successCount = $insertOneResult->getInsertedCount();
        $insertId = $insertOneResult->getInsertedId();
        return $insertId;
    }
    
    public function insertMany($data)
    {
        $insertManyResult = $this->collection->insertMany($data);
        $successCount = $insertManyResult->getInsertedCount();
        $insertIds = $insertManyResult->getInsertedIds();
        return $insertIds;
    }
    
    
    public function findOne()
    {
        $document = $this->collection->findOne($this->options['where'],$this->options);
        $document = array ($document);
     
        $return = [];
        foreach ($document as $key=>$value) {
            $singleRecord = get_object_vars($value);
            $return[] = $singleRecord;
        }
        return $return[0];
    }
    
    
    
    public function find()
    {
        $Cursor = $this->collection->find($this->options['where'],$this->options);
        
        $return = [];
        foreach ($Cursor as $document) {
            $singleRecord = get_object_vars($document);
            $return[] = $singleRecord;
        }
        return $return;
    }
    
    public function updateOne($data = [])
    {
        $updateResult = $this->collection->updateOne(
            $this->options['where'],
            ['$set' => $data],
            $this->options
        );
        $successCount = $updateResult->getModifiedCount();
        return $successCount;
    }
    
    public function updateMany($data = [])
    {
        $updateResult = $this->collection->updateMany(
            $this->options['where'],
            ['$set' => $data],
            $this->options
        );
        $successCount = $updateResult->getModifiedCount();
        return $successCount;
    }
    
    
    public function deleteMany()
    {
        $deleteResult = $this->collection->deleteMany($this->options['where']);
        return $deleteResult->getDeletedCount();
    }
    
    
    public function deleteOne()
    {
        $deleteResult = $this->collection->deleteOne($this->options['where']);
        return $deleteResult->getDeletedCount();
    }
    
    
    public function findByRegex($regex = [])
    {
        $cursor = $this->collection->find([
            $regex['field'] => new MongoDB\BSON\Regex($regex['pattern'], $regex['mode']),
        ],$this->options);
        
        $return = [];
        foreach ($cursor as $document) {
            $singleRecord = get_object_vars($document);
            $return[] = $singleRecord;
        }
        return $return;
    }
    
    
    public function countByRegex($regex)
    {
        $cursor = $this->collection->count([
            $regex['field'] => new MongoDB\BSON\Regex($regex['pattern'], $regex['mode']),
        ]);
       
        return $cursor;
    }
    
    
    public function count()
    {
        
        $cursor = $this->collection->count($this->options['where']);
        return $cursor;
    }
    
    
    public function dropCollection()
    {
        $result = $this->collection->drop();
        return $result;
    }
    
    public function findOneAndDelete() 
    {
        $deletedRestaurant = $this->collection->findOneAndDelete(
            $this->options['where'] ,
            $this->options
        );
        return $deletedRestaurant;
    }
    
    
    public function findOneAndUpdate($data)
    {
        $updateResult = $this->collection->findOneAndUpdate(
            $this->options['where'],
            ['$set' => $data],
            $this->options
        );
        $successCount = $updateResult->getModifiedCount();
        return $successCount;
    }
    
    public function __call($method, $args)
    {
        $method = strtolower($method);
        // 链式操作
        if (in_array($method, $this->chainMethods)) {
            $this->options[$method] = isset($args[0]) ? $args[0] : null;
        } else {
            throw new \Exception(sprintf('模型“%s”的方法“%s”不存在', get_class($this), $method));
        }
        return $this;
    } 
    

}