<?php
/**
 * Created by PhpStorm.
 * User: peter.georgiev
 * Date: 21/11/2018
 * Time: 12:36
 */

use yii\widgets\ActiveForm;
use common\modules\petites\helpers\PetitesHelper;
use yii\bootstrap\Html;
?>

<?php $form = ActiveForm::begin([
    'id' => 'addAssignedTasks',
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'ajax-submit'],

]); ?>
<?php echo $form->field($model, 'workflow_step_id')->dropDownList(PetitesHelper::getPetitesWorkflowStepsTree(), ['encode' => false]); ?>
<?php echo Html::submitInput(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>

<?php $form::end(); ?>