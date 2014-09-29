<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\base;

use yii\base\Object;

class Task extends Object{
    public $id;
    public $closure;

    public function run($servers, $services, $tasks){
        return call_user_func($this->closure, $servers, $services, $tasks, $this);
    }
}