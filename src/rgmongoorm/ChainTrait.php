<?php

/**
 * Chaintrait
 *
 * 模型链式trait
 *
 * @author Python Luo <laifaluo@126.com>
 *
 * */
 
namespace RgMongodb\Orm;


trait ChainTrait
{

    protected $options = [
        'where' => [],
        'limit' => '',
        'projection' => [],
        'skip'  => '',
        'sort'  => [],
        'regex'  => [],
    ];


    protected $chainMethods = [
        'limit',
        'skip',
        'sort',
        'where',
        'projection'

    ];
    
    final protected function projection($projection = [])
    {
        $this->options['projection'] = $projection;
        return $this;
    }
    
    final protected function limit($limit)
    {
        $this->options['limit'] = $limit;
        return $this;
    }
    
    final protected function skip($skip)
    {
        $this->options['skip'] = $skip;
        return $this;
    }


    final protected function sort($sort = [])
    {
        $this->options['sort'] = $sort;
        return $this;
    }
    
    
    final protected function where($where = [])
    {
        $this->options['where'] = $where;
        return $this;
    }

    final protected function resetOptions()
    {
        $this->options = [];
    }
}
