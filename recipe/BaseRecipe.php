<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\recipe;

use trntv\deploy\controllers\DeployController;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
class BaseRecipe extends \yii\base\Object{

    /**
     * @var \trntv\deploy\controllers\DeployController
     */
    public $controller;

    /**
     * @return array trntv\task\BaseTask
     */
    public function tasks()
    {
        return [];
    }

    public function run($task = false){
        $tasksMap = $this->tasks();
        $stack = [];
        if($task && isset($tasksMap[$task]) ){
            $stack[] = \Yii::createObject($tasksMap[$task]);
        } else {
            foreach($tasksMap as $taskConfig){
                $stack[] = \Yii::createObject($taskConfig);
            }
        }
        foreach($stack as $task){
            if($task->run() === false){
                return Controller::EXIT_CODE_ERROR;
            };
        }
        return Controller::EXIT_CODE_NORMAL;
    }
} 