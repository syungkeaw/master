<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\BlackList */

$this->title = Yii::t('app', 'Create Black List');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Black Lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="black-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
