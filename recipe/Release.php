<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */

namespace trntv\deploy\recipe;


class Release extends BaseRecipe{
    public $tasks = [

    ];

    public function run($task = false){
        echo 'Running "Release recipe"';
        parent::run($task);
    }
} 