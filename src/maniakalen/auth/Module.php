<?php

namespace maniakalen\auth;

use maniakalen\auth\models\AuthControlManager;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\web\HttpException;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'maniakalen\auth\controllers';

    /**
     * @return void|null
     * @throws HttpException
     */
    public function init()
    {
        parent::init();
        if (!(\Yii::$app->authManager instanceof DbManager)) {
            throw new HttpException("Auth module not supporting current auth configuration");
        }
        \Yii::setAlias('@maniakalen/auth', __DIR__);
        if (!$this->controllerNamespace) {
            $this->controllerNamespace = \Yii::getAlias('@maniakalen/auth/controllers');
        }
        if (isset($config['aliases']) && !empty($config['aliases'])) {
            \Yii::$app->setAliases($config['aliases']);
        }

        return null;
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     *
     * @return null
     */
    public function bootstrap($app)
    {
        $this->registerTranslations();
        if ($app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'maniakalen\auth\console\controllers';
        }
        return null;
    }

    /**
     * Registers the translation file for the module
     *
     * @return null
     */
    public function registerTranslations()
    {
        \Yii::$app->i18n->translations['rbac'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'hashes',
            'basePath' => '@maniakalen/auth/messages'
        ];

        return null;
    }
}
