<?php

use yii\helpers\Html;

\maniakalen\auth\assets\AuthAsset::register($this);
/* @var $this yii\web\View */
/* @var $model maniakalen\auth\models\AuthItem */

$this->title = Yii::t('rbac', 'rbac.create_auth_item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac', 'rbac.auth_items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'permissions' => $permissions,
        'rules' => $rules,
        'children' => $children
    ]) ?>

</div>
