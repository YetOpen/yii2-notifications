<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use kartik\switchinput\SwitchInput;

/* @var $this yii\web\View */
/* @var $model webzop\notifications\model\NotificationType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="notification-type-form">

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-xs-12 col-sm-4 col-lg-6">
            <?= $form->field($model, 'code')->textInput(['maxlength' => 10]) ?>

        </div>
        
        <div class="col-xs-12 col-sm-4 col-lg-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        </div>

    </div>

    <div class="row">
        <br>
        <div class="col-xs-12 col-sm-4 col-lg-3">
        <?= $form->field($model, 'check_management')->widget(SwitchInput::classname(), ['pluginOptions'=>['onText'=>'Checked', 'offText'=>'Unchecked']]) ?>

        </div>
        
        <div class="col-xs-12 col-sm-4 col-lg-4">
        <?= $form->field($model, 'color')->widget(ColorInput::classname(), ['options' => ['placeholder' => 'Select color'],]);?>

        </div>

        <div class="col-xs-12 col-sm-4 col-lg-5">
        <?= $form->field($model, 'priority')->dropDownList([0 => 'Low', 1 => 'Medium', 2 => 'High']); ?>

        </div>
    </div>
    

    <div class="form-group">
        <br>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
