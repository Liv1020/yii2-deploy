<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */

namespace trntv\deploy\base;

use yii\base\Object;

class Server extends Object{
    public $connection = false;

    public $phpBin = '/usr/bin/php';
    private $_session;

    /**
     * @throws \yii\base\InvalidConfigException
     * @return \Ssh\Session
     */
    public function getSession(){
        if($this->connection){
            if(!$this->_session){
                $this->_session = \Yii::createObject($this->connection);
            }
        }
        return $this->_session;
    }

    protected function getExec(){
        if($this->getSession()){
            return function($command){
                return $this->getExec()->run($command);
            };
        } else {
            return function($command){
                return shell_exec($command);
            };
        }
    }

    public function execute($command, $params = []){
        $command = strtr($command, $params);
        return $this->getExec($command);
    }
} 