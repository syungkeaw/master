<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use common\models\Blacklist;
use kartik\icons\Icon;

Icon::map($this);  
/* @var $this yii\web\View */
/* @var $model common\models\BlackList */
/* @var $form yii\widgets\ActiveForm */

$blackLists = Blacklist::find()->all();
$blackLists = empty($blackLists) ? [' '] : $blackLists;
?>

<div class="black-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'character_name')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Fill character name..'],
        'pluginOptions' => ['highlight' => true],
        'dataset' => [
            [
                'local' => ArrayHelper::getColumn($blackLists, 'character_name'),
                'limit' => 10
            ]
        ]
    ]) ?>
    <?= $form->field($model, 'reason')->textArea(['rows' => '5', 'style' => 'resize: vertical;']) ?>
    <?= $form->field($model, 'youtube')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'facebook')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'server')->hiddenInput(['value'=> Yii::$app->request->get('server')])->label(false) ?>
    <?= $form->field($model, 'parent_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton(Icon::show('bullhorn'). ' ' . ($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update')), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'width:100%;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
