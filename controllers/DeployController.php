<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
namespace trntv\deploy\controllers;

use yii\base\InvalidParamException;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class DeployController extends Controller{
    /**
     * @var \trntv\deploy\recipe\BaseRecipe[]
     */
    public $recipes;

    public function actionRecipe($recipe = false, $task = false){
        if(!$recipe){
            $recipe = $this->select('Choose a recipe to run:', array_keys($this->recipes));
        }
        $recipe = ArrayHelper::getValue($this->recipes, $recipe);
        if(!$recipe) throw new InvalidParamException('Unknown recipe');
        $recipe = \Yii::createObject([$recipe]);
        return $recipe->run($task);
    }
} 