<?php
ini_set('memory_limit', '5024M');

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

use backend\models\SysPensionersVerificationsLogs;
use yii\helpers\Url;
use yii\grid\ActionColumn;


/** @var yii\web\View $this */
/** @var backend\models\SysPensionersVerificationsLogsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pensioners Verifications Logs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clerk-deni-index" style="padding-top: 10px">

    <?php $this->render('_search', ['model' => $searchModel]); ?>
    <?php
    $pdfHeader = [
        'L' => [
            'content' => Yii::t('yii', 'logs REPORT'),
        ],
        'C' => [
            'content' => Html::a(Html::img('../images/login.png', ['alt' => 'image', 'class' => 'image', 'height' => '80'])) . '<br>' . 'COMPLAINTS REPORT' . '<br>',
            //  'content' => 'ZSSF REPORT FOR MEMBER ' . $member_name . '(' . $member_number . ')',
            'font-size' => 10,
            'font-style' => 'B',
            'font-family' => 'arial',
            'height' => 119,
            'color' => '#333333'
        ],

        'R' => [
            'content' => Yii::t('yii', 'Date:') . date('Y-m-d'),
        ],
        'line' => true,
    ];

    $pdfFooter = [
        'L' => [
            'content' => '&copy; ZSSF',
            'font-size' => 10,
            'color' => '#333333',
            'font-family' => 'arial',
        ],
        'C' => [
            'content' => '',
        ],
        'R' => [
            //'content' => 'RIGHT CONTENT (FOOTER)',
            'font-size' => 10,
            'color' => '#333333',
            'font-family' => 'arial',
        ],
        'line' => true,
    ];
    ?>
    <?php $gridColumns = [
        [
            'class' => 'kartik\grid\SerialColumn',
            'contentOptions' => ['class' => 'kartik-sheet-style'],
            'width' => '36px',
            'headerOptions' => ['class' => 'kartik-sheet-style']
        ],


        //'id',
        'date_time',
        //'uid',
        'pension_number',
        'full_names',
        'verification_code',
        //'code_expiry_date',
        //'sent_date_time',
        'batch_code',
        //'kin_mobile_number',
        //'verified_by_uid',
        'verified_date_time',
        'verification_status',
        'checker_remarks:ntext',
        'date_renewed',
        'start_date',
        'expiry_date',
        'verified_location',
        'verified_by',

    ];

    echo \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
         'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return ['data-id' => $model->id];
        },
        'columns' => $gridColumns,
        'id' => 'grid',
        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
        'beforeHeader' => [
            [
                'options' => ['class' => 'skip-export'] // remove this row from export
            ]
        ],

        'pjax' => true,
        'bordered' => true,
        'striped' => true,
        'condensed' => true,
        'responsive' => true,
        'hover' => true,
        'floatHeader' => false,
        'floatHeaderOptions' => ['scrollingTop' => true],
        'showPageSummary' => true,
        'panel' => [
            'heading' => '<i class="fa fa-bars"></i>' . Yii::t('yii', 'Pensioners Verifications Logs'),
            'type' => GridView::TYPE_SUCCESS,
           // 'before' => '<span class="text text-primary">' . $this->render('_search', ['model' => $searchModel]) . '</span>',
        ],

        'exportConfig' => [
            GridView::PDF => [
                'label' => Yii::t('yii', 'Export All Data'),
                'showHeader' => true,
                'showPageSummary' => true,
                'showFooter' => true,
                'filename' => Yii::t('yii', 'logs REPORTS'),
                'alertMsg' => Yii::t('yii', 'The PDF export file will be generated for download.'),
                'options' => ['title' => Yii::t('yii', 'Portable Document Format')],
                'config' => [
                    'mode' => 'c',
                    'marginTop' => 50,
                    'marginBottom' => 50,
                    'cssInline' => '.kv-wrap{padding:20px;}' .
                        '.kv-align-center{text-align:center;}' .
                        '.kv-align-left{text-align:left;}' .
                        '.kv-align-right{text-align:right;}' .
                        '.kv-align-top{vertical-align:top!important;}' .
                        '.kv-align-bottom{vertical-align:bottom!important;}' .
                        '.kv-align-middle{vertical-align:middle!important;}' .
                        '.kv-page-summary{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-footer{border-top:4px double #ddd;font-weight: bold;}' .
                        '.kv-table-caption{font-size:1.5em;padding:8px;border:1px solid #ddd;border-bottom:none;}',

                    'methods' => [
                        'SetHeader' => [
                            ['odd' => $pdfHeader, 'even' => $pdfHeader]
                        ],
                        'SetFooter' => [
                            ['odd' => $pdfFooter, 'even' => $pdfFooter]
                        ],
                    ],

                ]
            ],
        ]

    ]);

    ?>
</div>
<style>
    .truncate {
        max-width: 150px !important;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .truncate:hover {
        overflow: visible;
        white-space: normal;
        width: auto;
    }

    .truncate1 {
        max-width: 350px !important;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .truncate1:hover {
        overflow: visible;
        white-space: normal;
        width: auto;
    }
</style>
