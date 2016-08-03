<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Shop */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shops', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'shop_name',
            'map',
            'location',
            'character',
            'not_found_count',
            'status',
            'created_by',
            'created_at',
            'updated_by',
            'updated_at',
        ],
    ]) ?>


<?php
$form = ActiveForm::begin();
    foreach ($shopItems as $index => $model) {
        echo '<div class="row item">';
        echo '<div class="col-md-3">'. $form->field($model, "[$index]item_id")->textInput()->label(false). '</div>';
        echo '<div class="col-md-3">'. $form->field($model, "[$index]price")->textInput()->label(false). '</div>';
        echo '<div class="col-md-3">'. $form->field($model, "[$index]amount")->textInput()->label(false). '</div>';   
        echo '</div>';
    }

    echo '<hr />';
    echo Html::submitButton('Open Shop', [
        'class' => 'btn btn-primary'
    ]);
ActiveForm::end();
?>

</div>
