<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?= Yii::t('app', 'modal.add_users_to_role')?></h4>
</div>

<?php /* \yii\widgets\Pjax::begin(['timeout' => 10000, 'clientOptions' => ['container' => 'modal-body'], 'id'=>'pjax-job-gridview']); */?>

<div class="modal-body">
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $users,
        'columns' => [
            ['content' => function ($model, $key, $index, $column) {
                return '<input type="checkbox" value="' . $key . '" class="users_add_list" id="add_' . $model->username . '" />';
            }],
            ['contentOptions' => ['class' => 'username_cell'], 'value' => 'username'],
            ['contentOptions' => ['class' => 'email_cell'], 'value' => 'email'],
        ],
        'tableOptions' => ['class' => 'table table-striped table-bordered modal_users_list']
    ]); ?>
</div>

<?php /* \yii\widgets\Pjax::end(); */ ?>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'hgigoc.close')?></button>
    <button type="button" class="btn btn-primary" id="modal_add_users" data-role="<?= Yii::$app->request->get('id')?>">
        <?= Yii::t('app', 'rbac.add_users')?>
    </button>
</div>