<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\service;

use trntv\deploy\base\Service;
use yii\base\Object;
use yii\di\Instance;
use yii\helpers\Console;

class Yii extends Service{
    public $yii;
    public $options = '--interactive=0';

    public function migrate($action = 'up')
    {
        Console::output('Applying migrations...');
        return $this->server->execute(':phpBin :yii migrate/:action :options', [
            ':action'=>$action,
            ':phpBin'=>$this->server->phpBin,
            ':yii'=>$this->yii,
            ':options'=>$this->options
        ]);
    }
} 