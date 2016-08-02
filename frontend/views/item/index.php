<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            // 'source_id',
            'item_name',
            // 'item_slot',
            // 'item_slot_spare',
            'item_num_hand',
            'item_type_id',
            'item_type',
            'item_class',
            'item_attack',
            'item_defense',
            'item_required_lvl',
            'item_weapon_lvl',
            // 'item_description',
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
