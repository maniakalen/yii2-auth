<?php
/** $Id: _rolesList.php 2527 2016-03-10 09:03:18Z marcelo.pineiro $ */
/**
 * ---------  Begin Version Control Data----------------------------------------
 * $LastChangedDate: 2016-03-10 10:03:18 +0100 (Thu, 10 Mar 2016) $
 * $Revision: 2527 $
 * $LastChangedBy: marcelo.pineiro $
 * $Author: marcelo.pineiro $
 * ---------  End Version Control Data -----------------------------------------
 */
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?= Yii::t('app', 'modal.add_users_to_role')?></h4>
</div>

<div class="modal-body">

    <?php /* \yii\widgets\Pjax::begin(['timeout' => 10000, 'clientOptions' => ['container' => 'modal-body'], 'id'=>'pjax-job-gridview']); */ ?>
    
    <?= \yii\grid\GridView::widget([
        'dataProvider' => $roles,
        'columns' => [
            ['content' => function ($model, $key) {
                return '<input type="checkbox" value="' . $key . '" class="roles_add_list" id="add_' . $model->name . '" />';
            }],
            ['contentOptions' => ['class' => 'username_cell'], 'value' => 'name'],
            ['contentOptions' => ['class' => 'desc_cell'], 'value' => 'description'],
        ],
        'tableOptions' => ['class' => 'table table-striped table-bordered modal_roles_list']
    ]); ?>
    
    <?php /* \yii\widgets\Pjax::end(); */ ?>
    
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'hgigoc.close')?></button>
    <button type="button" class="btn btn-primary" id="modal_add_roles" data-role="<?= Yii::$app->request->get('id')?>">
        <?= Yii::t('app', 'rbac.add_users')?>
    </button>
</div>
