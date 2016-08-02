<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\classes\RoHelper;
use common\models\Shop;
use kartik\dropdown\DropdownX;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerJs("
$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
", View::POS_READY);

$this->title = Yii::t('app', 'My Shop');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="shop-index">
  <div class="row">  
        <div class="col-md-6">
            <p>
                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> '. Yii::t('app', 'Create Shop'), ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <div class="col-md-6 text-right">
            <?= Html::a('<span class="glyphicon glyphicon-list-alt"></span> '. Yii::t('app', 'Table View'), ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>


    <div class="row">
        <?php foreach ($dataProvider->getModels() as $model) { ?>
                        
                        <div class="col-sm-4">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title ellipsis" style="max-width: 250px !important;">
                                       <?= $model->status ? '<span class="glyphicon glyphicon-ok text-green"></span>' : '<span class="glyphicon glyphicon-remove text-red"></span>' ?> <?= '#'. $model->id ?> <?= $model->shop_name ?> <small><?= $model->map ?> (<?= $model->location ?>)</small></h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-hover">
                                        <thead><tr><th>Items</th><th class="text-right">Price</th><th></th></tr></thead>
                                        <tbody>
                                        <?php foreach (range(0, 11) as $slot) { 
                                                $item_img = Yii::getAlias('@web'). '/images/item_slot.JPG';
                                                $item_name = '';
                                                $item_price = 0;
                                                if(isset($model->shopItems[$slot])){
                                                    $item_img = Yii::$app->params['item_small_image_url'] . $model->shopItems[$slot]->item->source_id .'.gif';
                                                    $item_name .= ($model->shopItems[$slot]->enhancement ? '+'. $model->shopItems[$slot]->enhancement  : '') .' ';
                                                    $item_name .= $model->shopItems[$slot]->item->nameSlot;
                                                    $item_price = number_format($model->shopItems[$slot]->price);
                                                    $item_status = $model->shopItems[$slot]->status;
                                                }
                                        ?>
                                            <tr style="height:42px; <?= $item_status === 0 ? 'background: #ddd;' : '' ?>">
                                                <td><div class="ellipsis"><small><?= Html::img($item_img) .' '. $item_name ?></small></div></td>
                                                <td class="text-right"><small><?= $item_name ? $item_price. ' zeny' : '' ?></small></td>
                                                <td class="text-right">
                                                    <?php if(!empty($item_name)){ ?>
                                                    <div class="dropdown">
                                                    <?= Html::a('<span class="glyphicon glyphicon-option-horizontal"></span>', [''], ['data-toggle'=>'dropdown']) ?>
                                                    <?= DropdownX::widget([
                                                        'items' => [
                                                            // ['label' => '100 reports'],
                                                            // '<li class="divider"></li>',
                                                            ($item_status === 10 ? 
                                                                ['label' => Yii::t('app', 'Close Item'), 'url' => ['shop-item/close', 'id' => $model->shopItems[$slot]->id]] :
                                                                ['label' => Yii::t('app', 'Open Item'), 'url' => ['shop-item/open', 'id' => $model->shopItems[$slot]->id]])
                                                        ],
                                                    ]) ?>
                                                    </div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span> '. Yii::t('app', 'Update Shop'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-xs']) ?>
                                    <?= $model->status ? Html::a('<span class="glyphicon glyphicon-ban-circle"></span> '. Yii::t('app', 'Close Shop'), ['close', 'id' => $model->id], ['class' => 'btn btn-danger btn-xs']) : Html::a('<span class="glyphicon glyphicon-bullhorn"></span> Open', ['open', 'id' => $model->id], ['class' => 'btn btn-success btn-xs'])?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
    </div>
</div>