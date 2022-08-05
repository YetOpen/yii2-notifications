<?php

use yii\helpers\Html;
use kartik\grid\GridView;
Use kartik\editable\Editable;
use webzop\notifications\model\NotificationType;
use kartik\daterange\DateRangePicker;
use webzop\notifications\dictionaries\Read;
use webzop\notifications\dictionaries\Managed;

/* @var $this yii\web\View */
/* @var $searchModel webzop\notifications\model\NotificationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('modules/notifications', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
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
                        '0' => '<svg style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45zm-183.72-142l-39.3-30.38A94.75 94.75 0 0 0 416 256a94.76 94.76 0 0 0-121.31-92.21A47.65 47.65 0 0 1 304 192a46.64 46.64 0 0 1-1.54 10l-73.61-56.89A142.31 142.31 0 0 1 320 112a143.92 143.92 0 0 1 144 144c0 21.63-5.29 41.79-13.9 60.11z"/></svg>',
                        '1' => '<svg style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"/></svg>',                       
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
                                '0' => '<svg style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"/></svg>',
                                '1' => '<svg style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg>',                   
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
                                '0' => '<svg style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"/></svg>',
                                '1' => '<svg style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"/></svg>',                    
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