<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Shop */

$this->title = Yii::t('app', 'Update Shop'). ' : ' . $shop_model->shop_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'My Shop'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $shop_model->shop_name];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update Shop');
?>
<div class="shop-update">

    <?= $this->render('_form', [
        'shop_model' => $shop_model,
        'shop_item_model' => $shop_item_model,
        'item_model' => $item_model,
    ]) ?>

</div>
