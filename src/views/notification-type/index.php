<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use \kartik\grid\GridView;
use kartik\icons\Icon;
use webzop\notifications\dictionaries\Priority;
use webzop\notifications\dictionaries\Manageable;


/* @var $this yii\web\View */
/* @var $searchModel webzop\notifications\model\NotificationTypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modules/notifications', 'Notification Types');
$this->params['breadcrumbs'][] = $this->title;
Icon::map($this, Icon::FA);
?>

<div class="notification-type-index">
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
                'trueLabel' => Manageable::get(Manageable::MANGEABLE), 
                'falseLabel' => Manageable::get(Manageable::UNMANGEABLE),
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
                'filter' => [Priority::LOW => Priority::get(Priority::LOW), Priority::MEDIUM =>Priority::get(Priority::MEDIUM), Priority::HIGH =>Priority::get(Priority::HIGH)],
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                'value'=> function ($model, $key, $index, $column){
                    return Priority::get($model->priority);
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
