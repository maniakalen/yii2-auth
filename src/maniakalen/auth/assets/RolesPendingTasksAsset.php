<?php
/**
 * Created by PhpStorm.
 * User: peter.georgiev
 * Date: 21/11/2018
 * Time: 9:12
 */

namespace maniakalen\auth\assets;


use yii\web\AssetBundle;

class RolesPendingTasksAsset extends AssetBundle
{
    public $sourcePath = '@maniakalen/auth/resources';

    public $css = [
        'css/auth.css'
    ];

    public $js = [
        'js/auth.js'
    ];

    public $depends = ['yii\\AppAsset'];
}