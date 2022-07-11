<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model webzop\notifications\model\NotificationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notifications-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'class') ?>

    <?= $form->field($model, 'key') ?>

    <?= $form->field($model, 'channel') ?>

    <?php // echo $form->field($model, 'message') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'attachments') ?>

    <?php // echo $form->field($model, 'language') ?>

    <?php // echo $form->field($model, 'route') ?>

    <?php //echo $form->field($model, 'seen') ?>

    <?php //echo $form->field($model, 'read') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <?php //echo $form->field($model, 'send_at') ?>

    <?php // echo $form->field($model, 'sent') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'managed') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
