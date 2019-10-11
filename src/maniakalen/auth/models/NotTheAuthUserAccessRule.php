<?php
/**
 * Created by PhpStorm.
 * User: peter.georgiev
 * Date: 15/10/2015
 * Time: 12:18
 */

namespace maniakalen\auth\models;


use yii\rbac\Item;
use yii\rbac\Rule;
use Yii;

class NotTheAuthUserAccessRule extends Rule
{
    public $name = 'NotTheAuthUserAccessRule';
    /**
     * Executes the rule.
     *
     * @param string|integer $user the user ID. This should be either an integer or a string representing
     * the unique identifier of a user. See [[\yii\web\User::id]].
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to [[ManagerInterface::checkAccess()]].
     * @return boolean a value indicating whether the rule permits the auth item it is associated with.
     */
    public function execute($user, $item, $params)
    {
        return !Yii::$app->user->getIsGuest() && $user != Yii::$app->user->getId();
    }
}