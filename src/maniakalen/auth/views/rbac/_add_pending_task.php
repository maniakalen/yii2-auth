<?php
/**
 * Created by PhpStorm.
 * User: peter.georgiev
 * Date: 21/11/2018
 * Time: 11:50
 */

use yii\widgets\ActiveForm;
use common\helpers\ArrayHelper;
use common\models\workflow\WorkflowActivities;
use common\models\workflow\WorkflowEstado;
use yii\bootstrap\Html;
?>

<?php $form = ActiveForm::begin([
    'id' => 'addAssignedTasks',
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'ajax-submit'],

]); ?>
    <?php echo $form->field($model, 'activity_id')->dropDownList(ArrayHelper::map(WorkflowActivities::find()->all(), 'id', 'title')); ?>
    <?php echo $form->field($model, 'status_id')->dropDownList(ArrayHelper::map(WorkflowEstado::find()->all(), 'id', 'name')); ?>
    <?php echo Html::submitInput(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>

<?php $form::end(); ?>
