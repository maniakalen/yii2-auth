<?php

use yii\helpers\Html;
\maniakalen\auth\assets\AuthAsset::register($this);
/* @var $this yii\web\View */
/* @var $model maniakalen\auth\models\AuthItem */

$this->title = Yii::t('rbac', 'rbac.update_model_class', [
    'modelClass' => Yii::t('rbac', 'rbac.auth_items'),
]) . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac', 'rbac.auth_items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('rbac', 'rbac.update ');
?>
<div class="auth-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'permissions' => $permissions,
        'children' => $children,
        'users' => $userDataProvider,
        'rules' => $rules,
        'rolesDataProvider' => $roles
    ]) ?>

</div>
<?php \yii\bootstrap\Modal::begin(['id' => 'modal_update']); \yii\bootstrap\Modal::end(); ?>