<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model maniakalen\auth\models\AuthItem */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="auth-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label(Yii::t('rbac', 'rbac.name')) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(Yii::t('rbac', 'rbac.description')) ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>
	<?php if (count($rules) > 0): ?>
    <div>
        <h3><?= Yii::t('rbac', 'rbac.rule_assigned')?></h3>
        <div><?= isset($rules[$model->rule_name])?$rules[$model->rule_name]:Yii::t('rbac', 'rbac.no_rule_assigned')?></div>
        <?= Html::a(\Yii::t('rbac', 'rbac.set_rule'), '/auth/rbac/set-role-rule.html?id=' . $model->name, ['class' => 'btn btn-default add-role-rule ajax_get_modal ajax-run', 'id' => 'add_role_rule']) ?>
    </div>
	<?php endif ?>

    <?php if (!$model->isNewRecord && isset($rolesDataProvider)): ?>
        <div><h3><?= Yii::t('rbac', 'rbac.child_roles')?></h3></div>
        <?= \yii\grid\GridView::widget([
            'dataProvider' => $rolesDataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                'description:ntext',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{remove-role-child}',
                        'buttons' => [
                            'remove-role-child' => Yii::$app->user->can('auth/rbac/remove_role_child') ? function($url, $model) {
                                $params['role'] = Yii::$app->request->get('id');
                                return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                                    'title' => Yii::t('rbac', 'rbac.remove'),
                                    'class' => 'remove-role remove-item',
                                    'data-params' => json_encode($params),
                                    'id' => 'remove_role_child_' . $model->name,
                                    'data-hash' => 'roles'
                                ]);
                            } : null,
                        ]
                    ]
            ],
        ]); ?>
        <div style="margin-bottom: 20px;">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', '/auth/rbac/add-role-child.html?id=' . $model->name, ['class' => 'btn btn-default add-role-user ajax-run run-modal-link', 'id' => 'add_user_role', 'data-ajax-callback' => 'usersFilter']) ?>
        </div>
    <?php endif ?>

    <div><h3><?= Yii::t('rbac', 'rbac.permissions_list') ?></h3></div>
    <?php
    foreach ($permissions as $permission) {
        $name = $permission->getAttribute('name');
        $checkboxOptions = [
            'name' => 'permissions[]',
            'value' => $name,
            'label' => $permission->getAttribute('description'),
            'id' => 'checkbox_' . str_replace('/', '_', $name),
            'uncheck' => null
        ];
        $permission->name = in_array($name, $children)?$name:false;
        echo $form->field($permission, 'name', ['options' => ['class' => 'col-md-3']])
            ->checkbox($checkboxOptions);

    }
    ?>
    <?php if (isset($users) && ($users instanceof \yii\data\ActiveDataProvider)): ?>
    <div><h3><?= Yii::t('rbac', 'rbac.users_related')?></h3></div>
    <a name="users"></a>
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $users,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'email',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{remove-role-user}',
                'buttons' => [
                    'remove-role-user' => Yii::$app->user->can('auth/rbac/add_role_user') ? function($url, $model) {
                        $params['role'] = Yii::$app->request->get('id');
                        return Html::a('<span class="glyphicon glyphicon-remove"></span>', $url, [
                            'title' => Yii::t('rbac', 'rbac.remove'),
                            'class' => 'remove-user remove-item',
                            'data-params' => json_encode($params),
                            'id' => 'remove_user_role_' . $model->username,
                            'data-hash' => 'users'
                        ]);
                    } : null,
                ]
            ]
        ],
    ]); ?>
    <?php endif ?>
    <?php if (!$model->isNewRecord && Yii::$app->user->can('auth/rbac/add_role_user')): ?>
        <div style="margin-bottom: 20px;">
            <?= Html::a('<span class="glyphicon glyphicon-plus"></span>', '/auth/rbac/add-role-user.html?id=' . $model->name, ['class' => 'btn btn-default add-role-user ajax-run run-modal-link', 'id' => 'add_user_role', 'data-ajax-callback' => 'usersFilter']) ?>
        </div>
    <?php endif ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rbac', 'rbac.create') : Yii::t('rbac', 'rbac.update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'id' => 'button_submit']) ?>
    </div>
    <?= $form->field($model, 'type')->hiddenInput(['value' => \yii\rbac\Role::TYPE_ROLE])->label('')?>
    <?php if($model->isNewRecord):?>
    <?= $form->field($model, 'created_at')->hiddenInput(['value' => time()] )->label('')?>
    <?php endif;?>
    <?= $form->field($model, 'updated_at')->hiddenInput(['value' => time()] )->label('')?>
    <?php ActiveForm::end(); ?>

</div>
