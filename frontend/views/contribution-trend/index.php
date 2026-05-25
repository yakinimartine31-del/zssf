<?php

use frontend\models\ContributionTrend;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ContributionTrendSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $salaryData array */
/* @var $lastYearSalary float */
/* @var $lastMonthSalary array */
/* @var $lastMonthYear int */
/* @var $lastMonthNum int */

$this->title = Yii::t('yii', 'Contribution Information');
$this->params['breadcrumbs'][] = $this->title;

if (!Yii::$app->user->isGuest) {
    $id = Yii::$app->user->identity->getId();
    $member = \frontend\models\Members::find()->where(['uid' => $id])->one();
    $member_number = isset($member['membership_number']) ? $member['membership_number'] : '';
    $member_name = isset($member['full_names']) ? $member['full_names'] : '';
    $member_sys_id = isset($member['member_sys_id']) ? $member['member_sys_id'] : null;
}

// If salaryData is not passed, initialize as empty array
if (!isset($salaryData) || empty($salaryData)) {
    $salaryData = [];
}


// Helper function to get all salaries for a month
function getAllSalaries($salaryData, $year, $month) {
    // Use zero-padded format: 2024_01
    $key = $year . '_' . str_pad($month, 2, '0', STR_PAD_LEFT);
    if (isset($salaryData[$key]) && is_array($salaryData[$key]) && count($salaryData[$key]) > 0) {
        $salaries = $salaryData[$key];
        // Format all salaries as comma-separated values
        $formattedSalaries = array_map(function($s) {
            return number_format((float)$s, 2);
        }, $salaries);
        return implode(', ', $formattedSalaries);
    }
    return null;
}

// Helper function to format salaries array
function formatSalaries($salaries) {
    if (empty($salaries)) return '-';
    $formatted = array_map(function($s) {
        return number_format((float)$s, 2);
    }, $salaries);
    return implode(', ', $formatted);
}

// Helper function to get month name
function getMonthName($monthNum) {
    $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];
    return isset($months[$monthNum]) ? $months[$monthNum] : '';
}

// Prepare last year info
$lastYearInfo = '';
if (isset($lastYearSalary) && $lastYearSalary > 0) {
    $lastYearInfo = '<span class="text-warning" style="margin-left: 15px;"><i class="fa fa-calendar"></i> Last Year Salary: <strong>' . number_format($lastYearSalary, 2) . '</strong></span>';
}

// Prepare last month info
$lastMonthInfo = '';
if (isset($lastMonthSalary) && !empty($lastMonthSalary) && isset($lastMonthYear) && isset($lastMonthNum)) {
    $monthName = getMonthName($lastMonthNum);
    $lastMonthInfo = '<span class="text-info" style="margin-left: 15px;"><i class="fa fa-calendar-check-o"></i> Last Contribution Month: <strong>' . $monthName . ' ' . $lastMonthYear . '</strong> (Salary: ' . formatSalaries($lastMonthSalary) . ')</span>';
}
?>
<div class="contribution-trend-index">

    <!-- Status Info -->
    <?php if (empty($salaryData)): ?>
        <div class="alert alert-warning">
            <strong>Note:</strong> Salary data is not available for this period.
        </div>
    <?php endif; ?>

    <!-- Simple Export Buttons -->
    <div style="margin-bottom: 20px; text-align: right;">
        <?= Html::a('<i class="fa fa-file-pdf-o"></i> Export PDF',
                ['export', 'type' => 'pdf'],
                [
                        'class' => 'btn btn-danger',
                        'target' => '_blank',
                        'title' => 'Download PDF'
                ]
        ) ?>

        <?= Html::a('<i class="fa fa-file-excel-o"></i> Export Excel',
                ['export', 'type' => 'excel'],
                [
                        'class' => 'btn btn-success',
                        'target' => '_blank',
                        'title' => 'Download Excel'
                ]
        ) ?>

        <?= Html::a('<i class="fa fa-file-text-o"></i> Export CSV',
                ['export', 'type' => 'csv'],
                [
                        'class' => 'btn btn-info',
                        'target' => '_blank',
                        'title' => 'Download CSV'
                ]
        ) ?>
    </div>

    <?php
    $gridColumns = [
            [
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class' => 'kartik-sheet-style'],
                    'width' => '36px',
                    'headerOptions' => ['class' => 'kartik-sheet-style']
            ],
            [
                    'attribute' => 'ContributionYear',
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => ArrayHelper::map(ContributionTrend::find()->where(['member_id' => $member_sys_id])->asArray()->all(), 'ContributionYear', 'ContributionYear'),
                    'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => Yii::t('yii', 'Year')],
            ],
            // January Salary and Contribution
            [
                    'label' => 'January Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 1);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'January Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->JANUARYC) ? $model->JANUARYC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // February Salary and Contribution
            [
                    'label' => 'February Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 2);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'February Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->FEBRUARYC) ? $model->FEBRUARYC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // March Salary and Contribution
            [
                    'label' => 'March Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 3);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'March Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->MARCHC) ? $model->MARCHC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // April Salary and Contribution
            [
                    'label' => 'April Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 4);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'April Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->APRILC) ? $model->APRILC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // May Salary and Contribution
            [
                    'label' => 'May Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 5);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'May Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->MAYC) ? $model->MAYC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // June Salary and Contribution
            [
                    'label' => 'June Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 6);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'June Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->JUNEC) ? $model->JUNEC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // July Salary and Contribution
            [
                    'label' => 'July Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 7);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'July Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->JULYC) ? $model->JULYC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // August Salary and Contribution
            [
                    'label' => 'August Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 8);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'August Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->AUGUSTC) ? $model->AUGUSTC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // September Salary and Contribution
            [
                    'label' => 'September Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 9);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'September Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->SEPTEMBERC) ? $model->SEPTEMBERC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // October Salary and Contribution
            [
                    'label' => 'October Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 10);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'October Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->OCTOBERC) ? $model->OCTOBERC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // November Salary and Contribution
            [
                    'label' => 'November Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 11);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'November Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->NOVEMBERC) ? $model->NOVEMBERC : 0;
                        return number_format($cont, 2);
                    },
            ],
            // December Salary and Contribution
            [
                    'label' => 'December Salary',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#4cb6de'],
                    'value' => function($model) use ($salaryData) {
                        $year = isset($model->ContributionYear) ? $model->ContributionYear : 0;
                        $allSalaries = getAllSalaries($salaryData, $year, 12);
                        return ($allSalaries !== null) ? $allSalaries : '-';
                    },
            ],
            [
                    'label' => 'December Cont.',
                    'format' => 'raw',
                    'headerOptions' => ['style'=>'color:#28a745'],
                    'value' => function($model) {
                        $cont = isset($model->DECEMBERC) ? $model->DECEMBERC : 0;
                        return number_format($cont, 2);
                    },
            ],
    ];

    echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
            'id' => 'grid',
            'toolbar' => [
                    ['content' => '{toggleData}'],
            ],
            'summary' => '',
            'pjax' => true,
            'bordered' => true,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'showPageSummary' => true,
            'panel' => [
                    'heading' => Yii::t('yii', 'MEMBER CONTRIBUTION INFORMATION') . $lastYearInfo . $lastMonthInfo,
                    'before' => '<span class="text text-primary">' . $this->render('_search', ['model' => $searchModel]) . '</span>',
            ],
            'rowOptions' => function ($model) {
                return ['data-id' => $model->id];
            },
    ]);
    ?>
</div>

<?php
// Add JavaScript to handle the export
$this->registerJs('
    $(document).ready(function() {
        // Remove the default export button from GridView
        $(".export-csv, .export-txt, .export-html, .export-excel, .export-pdf").remove();
        
        // Add click handler for our export buttons
        $("a[href*=\'export\']").click(function(e) {
            // Only handle export buttons, not other links
            if ($(this).attr("href").indexOf("type=") !== -1) {
                var button = $(this);
                var originalHtml = button.html();
                
                // Show loading
                button.html(\'<i class="fa fa-spinner fa-spin"></i> Preparing...\');
                button.addClass(\'disabled\');
                
                // Reset after 5 seconds
                setTimeout(function() {
                    button.html(originalHtml);
                    button.removeClass(\'disabled\');
                }, 5000);
            }
        });
    });
');
?>
