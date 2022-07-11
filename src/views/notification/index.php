<?php

use yii\helpers\Html;
use kartik\grid\GridView;
Use kartik\editable\Editable;
use webzop\notifications\model\NotificationType;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel webzop\notifications\model\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Notifications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-index">
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" crossorigin="anonymous"></script>
    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
        'columns' => [
            'type' => [
                'attribute' => 'nameType',
                'label' => 'Type',
                'filter' => NotificationType::find()->select('name')->indexBy('id')->column(),
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                'value' => function ($model, $key, $index, $column){
                    return $model->notificationsType->name;
                },
                'contentOptions' => function ($model, $key, $index, $column) {
                    return ['style' => 'background-color:'.($model->notificationsType->color)];
                },               
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width'=>'170px',
            ],
            'message' => [
                'attribute' => 'message',
                'hAlign' => 'center',
                'width'=>'200px',
            ],
            'created_at' => [
                'attribute' => 'created_at',
                'label' => 'Datetime',
                'format' => 'datetime',
                'startAttribute' => 'start',
                'endAttribute' => 'end',
                'filter' => DateRangePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'created_at',
                    'convertFormat' => true,
                    'presetDropdown' => false,
                    'pluginOptions' => [
                        'timePicker'=>true, 
                        'timePickerIncrement'=>10,
                        'locale' => ['format' => 'm-d-Y g:i:s A']
                    ]
                ]),
                'vAlign' => 'middle',
                'hAlign' => 'center',
            ],
            'timeago' => [
                'attribute' => 'timeAgo',
                'value' => function ($model, $key, $index, $column){
                    return $model->timeAgo;
                }, 
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width' => '200'
            ],
            'read' =>  [
                'attribute'=>'read',
                'class' => 'kartik\grid\BooleanColumn',
                'class' => 'kartik\grid\EditableColumn',
                'filter' => [0 => 'Unread', 1 => 'Read'],
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                'editableOptions'=> [
                    'asPopover' => true,
                    'format' => Editable::FORMAT_BUTTON,
                    'inputType' => Editable::INPUT_DROPDOWN_LIST,
                    'data' => [0 => 'Unread', 1 => 'Read'],
                    'options' => ['class'=>'form-control'], 
                    'displayValueConfig'=> [
                        '0' => '<i class="fas fa-eye-slash"></i>',
                        '1' => '<i class="fas fa-eye"></i>',                       
                    ],
                ],
                'vAlign' => 'middle',
                'hAlign' => 'center',
                
            ],
            'managed' => [
                'attribute'=>'managed', 
                'class' => 'kartik\grid\BooleanColumn',
                'class' => 'kartik\grid\EditableColumn',
                'filter' => [0 => 'Unmanaged', 1 => 'Managed'],
                'filterInputOptions' => [
                    'class' => 'form-control',         
                    'prompt' => 'All'
                ],
                
                'editableOptions'=> function($model, $key, $index, $column){
                
                    if(($model->notificationsType->check_management) == 0){
                        return [
                            'editableButtonOptions' => ['disabled' => true,],
                            'asPopover' => true,
                            'format' => Editable::FORMAT_BUTTON,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => [0 => 'Unmanaged', 1 => 'Managed'],
                            'options' => ['class'=>'form-control'], 
                            'displayValueConfig'=> [
                                '0' => '<i class="fas fa-times"></i>',
                                '1' => '<i class="fas fa-check"></i>'                     
                            ],
                        
                        ];
                    }

                    if(($model->notificationsType->check_management) == 1){
                        return [
                            'editableButtonOptions' => ['disabled' => false,],
                            'asPopover' => true,
                            'format' => Editable::FORMAT_BUTTON,
                            'inputType' => Editable::INPUT_DROPDOWN_LIST,
                            'data' => [0 => 'Unmanaged', 1 => 'Managed'],
                            'options' => ['class'=>'form-control'], 
                            'displayValueConfig'=> [
                                '0' => '<i class="fas fa-times"></i>',
                                '1' => '<i class="fas fa-check"></i>'                     
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
