<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title"><?= Yii::t($inter['category'], $inter['key'])?></h4>
</div>
<div class="modal-body">
    <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'assign_rule', 'options' => ['class' => 'ajax-submit']]); ?>
    <?= $form->field($model, 'rule_name')->dropDownList($rulesList) ?>
    <?php $form->end(); ?>
</div>
<div class="modal-footer">
    <?php if (isset($close)): ?>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= isset($close['intel'])?Yii::t($close['intel']['category'], $close['intel']['key']):Yii::t('app', 'hgigoc.close')?></button>
    <?php endif ?>
    <button type="button" data-form-id="assign_rule" class="btn btn-primary ajax-submit" id="<?=isset($confirm['id'])?$confirm['id']:'modal_confirm_action'?>" data-role="<?= Yii::$app->request->get('id')?>">
        <?= isset($confirm['inter'])?Yii::t($confirm['inter']['category'], $confirm['inter']['key']):Yii::t('yii', 'Yes')?>
    </button>
</div>