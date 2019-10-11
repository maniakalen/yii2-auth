<?php
/**
 * Created by PhpStorm.
 * User: peter.georgiev
 * Date: 21/11/2018
 * Time: 10:12
 */

?>

<div>
    <?php echo \yii\grid\GridView::widget([
        'dataProvider' => \Yii::createObject([
            'class' => 'yii\data\ActiveDataProvider',
            'query' => \common\models\AuthRoleStatusAssigned::find()->where(['auth_role' => $auth_role])
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'activity_id',
                'value' => 'activity.title'
            ],
            [
                'attribute' => 'status_id',
                'value' => 'status.name'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{remove-status-assigned}',
                'buttons' => [
                    'remove-status-assigned' => function($url, $model) {
                        return \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-remove"></span>', \common\helpers\Url::to(['remove-status-assigned', 'id' => $model->id]), [
                            'title' => Yii::t('app', 'Remove status assigned'),
                            'class' => 'remove-status-assigned remove-item ajax-run',
                        ]);
                    }
                ]
            ]
        ]
    ]); ?>
    <hr />
    <?php echo \yii\helpers\Html::a(\Yii::t('app', 'Add configuration'), \yii\helpers\Url::to(['add-role-configuration-form', 'role' => $auth_role]), ['class' => 'btn btn-default ajax-run']); ?>
</div>
