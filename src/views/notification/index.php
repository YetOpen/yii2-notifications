<?php

use yii\helpers\Html;
use kartik\grid\GridView;
Use kartik\editable\Editable;
use webzop\notifications\model\NotificationType;
use kartik\daterange\DateRangePicker;
use kartik\icons\Icon;
use webzop\notifications\dictionaries\Read;
use webzop\notifications\dictionaries\Managed;


/* @var $this yii\web\View */
/* @var $searchModel webzop\notifications\model\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modules/notifications', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
Icon::map($this, Icon::FA);
?>
<div class="notifications-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            'type' => [
                'attribute' => 'type',
                'filter' => NotificationType::find()->select('name')->indexBy('id')->column(),
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                'value' => function ($model, $key, $index, $column){
                    return $model->notificationType->name;
                },
                'contentOptions' => function ($model, $key, $index, $column) {
                    return ['style' => 'background-color:'.($model->notificationType->color)];
                },               
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width'=>'170px',
            ],
            'message' => [
                'label' => 'Message',
                'attribute' => 'message',
                'hAlign' => 'center',
                'width'=>'200px',
            ],
            'created_at' => [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:d/m/Y H:i'],
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at_filter',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'timePicker'=>true, 
                        'timePickerFormat' => 'H:i',
                        'timePickerIncrement'=>10,
                        'locale' => ['format' => 'd/m/Y H:i']
                    ]
                ]),
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],

            'timeago' => [
                'attribute' => 'timeAgo',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width' => '200'
            ],
            'read' =>  [
                'attribute'=>'read',
                'class' => 'kartik\grid\BooleanColumn',
                'class' => 'kartik\grid\EditableColumn',
                'filter' => [Read::UNREAD => Read::get(Read::UNREAD), Read::READ  => Read::get(Read::READ)],
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                'editableOptions'=> [
                    'asPopover' => true,
                    'format' => Editable::FORMAT_BUTTON,
                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                    'data' => [Read::UNREAD => Read::get(Read::UNREAD), Read::READ  => Read::get(Read::READ)],
                    'options' => ['class'=>'form-control'], 
                    'displayValueConfig'=> [
                        '0' => Icon::show('eye-slash'),
                        '1' => Icon::show('eye'),                       
                    ],
                ],
                'vAlign' => 'middle',
                'hAlign' => 'center',
                
            ],
            'managed' => [
                'attribute'=>'managed', 
                'class' => 'kartik\grid\BooleanColumn',
                'class' => 'kartik\grid\EditableColumn',
                'filter' => [Managed::UNMANAGED => Managed::get(Managed::UNMANAGED), Managed::MANAGED  => Managed::get(Managed::MANAGED)],
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                
                'editableOptions'=> function($model, $key, $index, $column){
                
                    if(($model->notificationType->check_management) == 0){
                        return [
                            'editableButtonOptions' => ['disabled' => true,],
                            'asPopover' => true,
                            'format' => Editable::FORMAT_BUTTON,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => [Managed::UNMANAGED => Managed::get(Managed::UNMANAGED), Managed::MANAGED  => Managed::get(Managed::MANAGED)],
                            'options' => ['class'=>'form-control'], 
                            'displayValueConfig'=> [
                                '0' => Icon::show('times'),
                                '1' => Icon::show('leaf')                    
                            ],
                        ];
                    }

                    if(($model->notificationType->check_management) == 1){
                        return [
                            'editableButtonOptions' => ['disabled' => false,],
                            'asPopover' => true,
                            'format' => Editable::FORMAT_BUTTON,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => [Managed::UNMANAGED => Managed::get(Managed::UNMANAGED), Managed::MANAGED  => Managed::get(Managed::MANAGED)],
                            'options' => ['class'=>'form-control'], 
                            'displayValueConfig'=> [
                                '0' => Icon::show('times'),
                                '1' => Icon::show('check')                     
                            ],
                        ];
                    }
                
                },
                
                'vAlign' => 'middle',
                'hAlign' => 'center',
                
            ],
            // 'id',
            // 'class',
            // 'key',
            // 'channel',
            //'content:ntext',
            //'attachments',
            //'language',
            //'route',
            //'user_id',
            //'sent',
        ],
    ]); ?>
    
</div>