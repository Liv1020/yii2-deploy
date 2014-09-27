<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\controllers;

use trntv\deploy\base\Server;
use trntv\deploy\base\Task;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\console\Controller;
use yii\di\Container;
use yii\di\Instance;
use yii\di\ServiceLocator;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\web\ErrorAction;

class DeployController extends Controller{

    public $services;
    public $tasks;
    public $servers;

    private $_tasks;

    public function init(){
        $this->services = $this->tasks = $this->servers = new ServiceLocator();
    }

    public function actionIndex($recipe, $servers){
        // Load recipe
        $recipe = \Yii::getAlias($recipe);
        if(!file_exists($recipe)){
            throw new InvalidParamException('Wrong recipe file path');
        }

        $recipe = require($recipe);
        if(!is_array($recipe) || !isset($recipe['tasks'])){
            throw new InvalidConfigException('Recipe must include tasks');
        }

        // Set servers
        $serversConfig = require(\Yii::getAlias($servers));
        $this->registerServers($serversConfig);

        // Set services
        $this->registerServices(ArrayHelper::getValue($recipe, 'services'));

        foreach($recipe['tasks'] as $id  => $task){
            $this->registerTask($id, $task);
        }

        if($this->runTasks() === false){
            Console::error('Error!');
            return Controller::EXIT_CODE_ERROR;
        };
        Console::output('Success!');
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * Register servers
     * @param $serversConfig
     */
    protected function registerServers($serversConfig){
        $this->servers->components = $serversConfig;
    }

    public function registerServices($services){
        $this->services->components = $services;
    }

    public function registerTask($taskId, $task){
        $this->tasks->set($taskId, [
            'class'=>Task::className(),
            'id'=>$taskId,
            'closure'=>$task
        ]);
        $this->_tasks[] = $taskId;
        return $this;
    }

    protected function runTasks(){
        foreach($this->_tasks as $k => $taskId){
            Console::output(sprintf('Running task "%s" (%d/%d)', $taskId, $k+1, count($this->_tasks)));
            $result = $this->tasks
                ->get($taskId)
                ->run(
                    $this->servers,
                    $this->services,
                    $this->tasks,
                    $k
                );
            if($result === false){
                Console::error(sprintf('Task "%s" failed.', $taskId));
                return false;
            }
        }
        return true;
    }
}