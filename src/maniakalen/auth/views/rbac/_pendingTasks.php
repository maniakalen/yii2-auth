<?php

/**
 * Created by PhpStorm.
 * User: peter.georgiev
 * Date: 21/11/2018
 * Time: 9:48
 */


/** @var \yii\web\View $this */
?>

<div>
    <?php echo \yii\bootstrap\Tabs::widget(['items' => [
        [
            'label' => 'Propuestas Comerciales',
            'content' => $this->render('_pendingTasksNormal', ['auth_role' => $role])
        ],
        [
            'label' => 'Petites Despesas',
            'content' => $this->render('_pendingTasksPd', ['auth_role' => $role])
        ]
    ]]); ?>
</div>
