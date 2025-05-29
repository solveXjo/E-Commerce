<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'User Management';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/admin/dashboard']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
    .Users-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        buser-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .Users-table {
        background: white;
        buser-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .table thead th {
        background: linear-gradient(90deg, #f8f9fa 0%, #e9ecef 100%);
        buser: none;
        font-weight: 600;
        color: #495057;
        padding: 15px;
    }
    .table tbody td {
        padding: 15px;
        buser-color: #f1f3f4;
        vertical-align: middle;
    }
    .user-number {
        font-weight: bold;
        color: #007bff;
    }
    .status-badge {
        font-size: 0.85em;
        padding: 5px 10px;
        buser-radius: 20px;
    }
    .action-buttons .btn {
        margin: 2px;
        padding: 5px 12px;
        font-size: 0.85em;
    }
');
?>

<div class="Users-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-users"></i> Users Management</h1>
            <p class="mb-0">Track and manage all customer Users</p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-download"></i> Export Users', ['#'], ['class' => 'btn btn-light']) ?>
        </div>
    </div>
</div>

<div class="Users-table">
    <?php Pjax::begin(['id' => 'Users-pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover mb-0'],
        'layout' => '{items}{summary}{pager}',
        'columns' => [
            [
                'attribute' => 'Username',
                'label' => 'Username',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        Html::encode($model->username),
                        ['#'],
                        ['class' => 'user-number text-decoration-none'],
                    );
                },
                'contentOptions' => ['style' => 'font-weight: 500;']
            ],
            [
                'attribute' => 'id',
                'label' => 'User ID',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->id ? Html::encode($model->id) : 'Unknown User';
                }
            ],
        ],
        'pager' => [
            'options' => ['class' => 'pagination justify-content-center mt-4 mb-4'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
        ]
    ]); ?>

    <?php Pjax::end(); ?>
</div>

<!-- user Statistics -->
<div class="row mt-4">

    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">Number of Users</h5>
                <h3 class="text-info"><?= \app\models\User::find()->count() - 1 ?></h3>
            </div>
        </div>
    </div>



</div>

<?php
$this->registerJs("
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
");
?>