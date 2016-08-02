<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'source_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_slot')->textInput() ?>

    <?= $form->field($model, 'item_slot_spare')->textInput() ?>

    <?= $form->field($model, 'item_num_hand')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_type_id')->textInput() ?>

    <?= $form->field($model, 'item_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_class')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_attack')->textInput() ?>

    <?= $form->field($model, 'item_defense')->textInput() ?>

    <?= $form->field($model, 'item_required_lvl')->textInput() ?>

    <?= $form->field($model, 'item_weapon_lvl')->textInput() ?>

    <?= $form->field($model, 'item_description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
