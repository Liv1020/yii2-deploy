<?php
/**
 * Author: Eugine Terentev <eugine@terentev.net>
 */
return [
    'tasks'=>[
        'clone'=>function($container){
            $git = $container->get('git');
            $commit = $git->getLastCommit;
            $git->cloneTo('/tmp/'.$commit);
        }
    ],
    'services'=>[
        'git'=>[
            'class'=>\trntv\deploy\service\Git::className(),
            'repository'=>'https://trntv:pass904143@bitbucket.org/trntv/ochitos.ru.git'
        ]
    ]
];
