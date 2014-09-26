<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */

namespace trntv\deploy\base;

use yii\base\Object;

// todo: errors
class Connection extends Object{
    /**
     * @see Ssh\Configuration or Ssh\SshConfigFileConfiguration
     * @var array
     */
    public $configuration;

    /**
     * @see Ssh\Authentication
     * @var array
     */
    public $authentication;

    private $_session;

    public function init(){
        $configuration = \Yii::createObject($this->configuration);
        $authentification = \Yii::createObject($this->authentication);
        $this->_session = new \Ssh\Session($configuration, $authentification);
    }

    /**
     * @return \Ssh\Session
     */
    public function getSession(){
        return $this->_session;
    }
} 