<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\base;

use yii\base\Object;

class Task extends Object{
    public $id;
    public $closure;
    public $container;

    public function run(){
        return call_user_func($this->closure, $this->container, $this);
    }
}