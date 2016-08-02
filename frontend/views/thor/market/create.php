<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\ShopItem */

$this->title = 'Create Shop Item';
$this->params['breadcrumbs'][] = ['label' => 'Shop Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shop-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
