<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
use yii\helpers\Console;

return [
    'tasks'=>[
        /*'clone'=>function($servers, $services, $tasks){
            $git = $services->get('git');
            $git->server = $servers->get('local');
            $commit = $git->getRemoteLastCommit();
            $git->cloneTo('/tmp/'.$commit);
        },*/
        'pull'=>function($servers, $services, $tasks){
            $git = $services->get('git');
            $git->server = $servers->get('local');
            Console::output('Latest commit is: '.$git->getRemoteLastCommit());
            $git->reset();
            $git->pull();
        },
        'composer'=>function($servers, $services, $tasks){
            $composer = $services->get('composer');
            $composer->server = $servers->get('local');
            $composer->download();
            $composer->install();
        },
        /*'migrate'=>function($servers, $services, $tasks){
            $yii = $services->get('yii');
            $yii->server = $servers->get('local');
            $yii->migrate();
        },*/
    ],
    'services'=>[
        'git'=>[
            'class'=>\trntv\deploy\service\Git::className(),
            'repositoryUrl'=>'https://github.com/trntv/yii2-deploy.git',
            'repositoryPath'=>'/tmp/yii2-deploy',
        ],
        'composer'=>[
            'class'=>\trntv\deploy\service\Composer::className(),
            'path'=>'/tmp/yii2-deploy',
            'composer'=>'composer.phar',
        ],
        'yii'=>[
            'class'=>\trntv\deploy\service\Yii::className(),
            'yii'=>'//tmp/yii2-deploy/yii'
        ]
    ]
];
