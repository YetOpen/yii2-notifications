<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use \kartik\grid\GridView;
use webzop\notifications\model\NotificationType;


/* @var $this yii\web\View */
/* @var $searchModel webzop\notifications\model\NotificationTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Notification Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notification-type-index">
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" crossorigin="anonymous"></script>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id' => [
                'attribute' => 'id',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width'=>'90px',
            ],
            'code' => [
                'attribute' => 'code',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width'=>'120px',
            ],
            'name' => [
                'attribute' => 'name',
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            'check_management'=>[
                'class'=>'kartik\grid\BooleanColumn',
                'attribute'=>'check_management', 
                'vAlign'=>'middle',
                'trueLabel' => 'Manageable', 
                'falseLabel' => 'Unmanageable',
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width'=>'220px',
            ],
            'color'=>[
                'attribute'=>'color',
                'value'=>function ($model, $key, $index, $widget) {
                    return "<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . 
                        $model->color . '</code>';
                },
                'filterType'=>GridView::FILTER_COLOR,
                'vAlign'=>'middle',
                'format'=>'raw',
                'width'=>'200px',
                'noWrap'=>true,
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            'priority' => [
                'attribute' => 'priority',
                'filter' => [0=>'Low', 1=>'Medium', 2=>'High'],
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                'value'=> function ($model, $key, $index, $column){
                    return $model->getPrio($model->priority);
                },
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{update}',
                'urlCreator' => function ($action, webzop\notifications\model\NotificationType $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
