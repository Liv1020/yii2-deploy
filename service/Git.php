<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\service;

use yii\base\Object;
use yii\helpers\Console;

class Git extends Object{
    /**
     * @var \trntv\deploy\base\Server
     */
    public $server;

    public $repository;
    public $remote = 'origin';
    public $branch = 'master';


    public function __construct(\trntv\deploy\base\Server $server){
        $this->server = $server;
    }

    public function cloneTo($to = false)
    {
        echo 'Executing Git::cloneTo';
        return $this->server->execute('git clone :repository :to', [
            ':repository'=>$this->repository,
            ':to'=>$to
        ]);
    }

    public function getLastCommit()
    {
        return $this->server->execute('git ls-remote :repository :branch | head -n 1', [
            ':repository'=>$this->repository,
            ':branch'=>$this->branch
        ]);
    }
} 