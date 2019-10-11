<?php
/**
 * Created by PhpStorm.
 * User: peter.georgiev
 * Date: 21/11/2018
 * Time: 13:42
 */

?>

<div>
    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => \Yii::createObject([
            'class' => 'yii\data\ActiveDataProvider',
            'query' => \common\modules\petites\models\PetiteStepsAssigned::find()->where(['auth_role' => $auth_role])
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'workflow_step_id',
                'value' => 'step.name'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{remove-ps-status-assigned}',
                'buttons' => [
                    'remove-ps-status-assigned' => function($url, $model) {
                        return \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-remove"></span>', \common\helpers\Url::to(['remove-ps-status-assigned', 'id' => $model->id]), [
                            'title' => Yii::t('app', 'Remove status assigned'),
                            'class' => 'remove-status-assigned remove-item ajax-run',
                        ]);
                    }
                ]
            ]
        ]
    ]); ?>
    <hr />
    <?php echo \yii\helpers\Html::a(\Yii::t('app', 'Add configuration'), \yii\helpers\Url::to(['add-ps-role-configuration-form', 'role' => $auth_role]), ['class' => 'btn btn-default ajax-run']); ?>
</div>