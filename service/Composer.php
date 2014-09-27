<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\service;

use trntv\deploy\base\Service;
use yii\base\Object;
use yii\di\Instance;
use yii\helpers\Console;

class Composer extends Service{
    public $path;
    public $composer;

    public function install()
    {
        Console::output('Installing composer packages...');
        return $this->server->execute('cd :path && :phpBin :composer install --prefer-dist', [
            ':path'=>$this->path,
            ':phpBin'=>$this->server->phpBin,
            ':composer'=>$this->composer
        ]);
    }

    public function update()
    {
        Console::output('Updating composer packages...');
        return $this->server->execute('cd :path && :phpBin :composer update --prefer-dist', [
            ':path'=>$this->path,
            ':phpBin'=>$this->server->phpBin,
            ':composer'=>$this->composer
        ]);
    }

    public function download(){
        Console::output('Downloading composer...');
        return $this->server->execute('cd :path && :phpBin -r "readfile(\'https://getcomposer.org/installer\');" | :phpBin -- --filename=:composer', [
            ':path'=>$this->path,
            ':phpBin'=>$this->server->phpBin,
            ':composer'=>$this->composer
        ]);
    }
} 