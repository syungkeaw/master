<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\icons\Icon;
use yii\web\View;

Icon::map($this);  

/* @var $this yii\web\View */
/* @var $searchModel common\models\BlackListSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJs("
    $('.toogle-tooltip').tooltip();
", View::POS_READY);

$this->title = Yii::t('app', 'Black Lists');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="black-list-index">

    <h1><?= Icon::show('user-secret'). ' ' .Html::encode($this->title) ?> <small>Beta</small></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Black List'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'character_name',
                'value' => function($model){
                    $label = '<div class="ellipsis toogle-tooltip" title="'. $model['character_name'] .'" style="max-width: 300px;">'. $model['character_name'] .'</div>';
                    return Html::a($label, ['view', 'id' => $model['id']]);
                },
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
            ],
            [
                'attribute' => 'reason',
                'value' => function($model){
                    return '<div class="ellipsis toogle-tooltip" title="'. $model['reason'] .'" style="max-width: 500px;">'. $model['reason'] .'</div>';
                },
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-6'
                ],
            ],
            [
                'attribute' => 'bad_point',
                'label' => Yii::t('app', 'Vote'),
                'value' => function($model){
                    $bad_size = $model['bad_point'] > $model['good_point'] ? 'font-size: 25px;' : '';
                    $good_size = $model['good_point'] > $model['bad_point'] ? 'font-size: 25px;' : '';
                    return  Html::a(Icon::show('thumbs-down', ['style' => 'color:#ddd;'. $bad_size]), ['', 'vote' => 'bad', 'id' => $model['id']]). ' ' .number_format($model['bad_point']). '   ' .
                            Html::a(Icon::show('thumbs-up', ['style' => 'color:#ddd;'. $good_size]), ['', 'vote' => 'good', 'id' => $model['id']]). ' ' .number_format($model['good_point']);
                },
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:d M Y H:i:s'],
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
