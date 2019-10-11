<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
\maniakalen\auth\assets\AuthAsset::register($this);
/* @var $this yii\web\View */
/* @var $model common\modules\auth\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac', 'rbac.auth_items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>

        <?= Yii::$app->user->can('auth/rbac/update')?
            Html::a(Yii::t('rbac', 'rbac.update'), ['update', 'id' => $model->name], ['class' => 'btn btn-primary']):'' ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'description:ntext',
            'rule_name',
            'data:ntext',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
    <div><h3><?= Yii::t('rbac', 'rbac.child_roles')?></h3></div>
    <?= GridView::widget([
        'dataProvider' => $rolesDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
        ],
    ]); ?>

    <div><h3><?= Yii::t('rbac', 'rbac.permissions_assigned')?></h3></div>
    <?= GridView::widget([
        'dataProvider' => $permDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description:ntext',
        ],
    ]); ?>
    <div><h3><?= Yii::t('rbac', 'rbac.users_related')?></h3></div>
    <?= GridView::widget([
        'dataProvider' => $userDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'email',
        ],
    ]); ?>
</div>
