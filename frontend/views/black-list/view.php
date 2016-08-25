<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BlackList */

$this->title = $model->character_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Black Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="black-list-view">

    <h1><?= Html::encode($this->title) ?> <small>โดย <?= $model->created_by ? $model->created_by : '-' ?> <?= date('d/m/Y H:i:s', $model->created_at) ?></small></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'reason',
            [
                'label' => Yii::t('app', 'Youtube'),
                'value' => $model->youtube ? '<a href="'. $model->youtube .'" target="_blank">'. $model->youtube .'</a>' : '-',
                'format' => 'raw',
            ],
            [
                'label' => Yii::t('app', 'Facebook'),
                'value' => '<a href="'. $model->facebook .'" target="_blank">'. $model->facebook .'</a>',
                'format' => 'raw',
            ],
        ],
    ]) ?>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="fb-comments" data-numposts="5" data-width="1000"></div>
    </div>
</div>
