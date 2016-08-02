<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'source_id') ?>

    <?= $form->field($model, 'item_name') ?>

    <?= $form->field($model, 'item_slot') ?>

    <?= $form->field($model, 'item_slot_spare') ?>

    <?php // echo $form->field($model, 'item_num_hand') ?>

    <?php // echo $form->field($model, 'item_type_id') ?>

    <?php // echo $form->field($model, 'item_type') ?>

    <?php // echo $form->field($model, 'item_class') ?>

    <?php // echo $form->field($model, 'item_attack') ?>

    <?php // echo $form->field($model, 'item_defense') ?>

    <?php // echo $form->field($model, 'item_required_lvl') ?>

    <?php // echo $form->field($model, 'item_weapon_lvl') ?>

    <?php // echo $form->field($model, 'item_description') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
