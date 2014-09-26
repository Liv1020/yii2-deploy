<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\controllers;

use trntv\deploy\base\Server;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class DeployController extends Controller{
    /**
     * @var \yii\di\Container
     */
    public $container;

    private $_tasks = [];

    public function init(){
        $this->container = new \yii\di\Container();
    }

    public function actionRecipe($recipe, $server = false){
        $recipe = \Yii::getAlias($recipe);
        if(!file_exists($recipe)){
            throw new InvalidParamException('Wrong recipe file path');
        }
        $recipe = require($recipe);
        if(!is_array($recipe) || !isset($recipe['tasks'])){
            throw new InvalidConfigException('Recipe must include tasks');
        }
        $services = ArrayHelper::getValue($recipe, 'services');
        if($services){
            foreach($services as $service => $serviceConfig){
                $this->container->set($service, $serviceConfig);
            }
        }

        if($server !== false) {
            $server = \Yii::getAlias($server);
            if (!file_exists($server)) {
                throw new InvalidParamException('Wrong server file path');
            }
            $serverConfig = require($server);
        } else {
            $serverConfig = [];
        }
        $this->container->setSingleton('server', Server::className(), $serverConfig);

        foreach($recipe['tasks'] as $id  => $task){
            $this->registerTask($id, $task);
            $this->_tasks[] = $id;
        }


        foreach($this->_tasks as $k => $taskId){
            echo sprintf('Running task "%s" (%d/%d)', $taskId, $k, count($this->_tasks));
            if($this->runTask($taskId) === false){
                return Controller::EXIT_CODE_ERROR;
            };
        }
        return Controller::EXIT_CODE_NORMAL;
    }



    public function registerTask($taskId, $task){
        $this->container->set($taskId, [
            'id'=>$taskId,
            'closure'=>$task
        ]);
        return $this;
    }

    protected function runTask($taskId){
        /**
         * @var $task \trntv\deploy\base\Task
         */
        $task = $this->container->get($taskId);
        return $task->run();
    }
} 