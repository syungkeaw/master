<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\classes\ItemHelper;
use common\models\Item;
use yii\web\View;
use yii\helpers\ArrayHelper;

$label = [
    '',
    ['label' => 'danger', 'icon' => '994'],
    ['label' => 'info', 'icon' => '995'],
    ['label' => 'success', 'icon' => '996'],
    ['label' => 'default', 'icon' => '997'],
];
$elements = Item::getElements();
$very = Item::getVeries();

$this->registerJs("
", View::POS_READY);

$this->registerCss("
.wrap > .container {
    padding: 0px;
}
.detail-view td, .detail-view th { line-height: 0.846 !important; }
.detail-view th { width:200px; }
");

$this->title = $model->item->nameSlot;
?>
<div class="shop-item-view">

    <h3><?= 
    	Html::img(Yii::$app->params['item_large_image_url']. $model->item->source_id .'.gif'). ' '.
    	($model->enhancement ? '+'. $model->enhancement.' ' : '').
    	Html::encode($this->title)
    ?></h3>

    <?php 
    $attributes = [];

    array_push($attributes, [
        'label' => Yii::t('app', 'Price'),
        'value' => '<h4>'.number_format($model->price).' zeny</h4>',
        'format' => 'raw',
    ]);

    if($model->shop['map'] && $model->shop['location']){
        array_push($attributes, [
            'label' => Yii::t('app', 'Location'),
            'value' => Html::img(Yii::$app->request->hostInfo. '/tool/shop-location?map='. $model->shop['map']. '.gif&location='. $model->shop['location'], ['style' => 'width: 200px; height:200px;']),
            'format' => 'raw',
        ]);
    }

    if($model->card_1 || $model->card_2 || $model->card_3 || $model->card_4){
        array_push($attributes, [
            'label' => Yii::t('app', 'Installed Cards'),
            'value' => ($model->card_1 ? Html::img(Yii::$app->params['item_large_image_url']. $model->itemCard1->source_id.'.gif') : ''). ' '.
                        ($model->card_2 ? Html::img(Yii::$app->params['item_large_image_url']. $model->itemCard2->source_id.'.gif') : ''). ' '.
                        ($model->card_3 ? Html::img(Yii::$app->params['item_large_image_url']. $model->itemCard3->source_id.'.gif') : ''). ' '.
                        ($model->card_4 ? Html::img(Yii::$app->params['item_large_image_url']. $model->itemCard4->source_id.'.gif') : ''),
            'format' => 'raw',
        ]);
    }

    if($model->element){
        array_push($attributes, [
            'label' => Yii::t('app', 'Element'),
            'value' => ($model->very ? $very[$model->very] : '').' '.($label[$model->element] ? Html::img(Yii::$app->params['item_small_image_url']. $label[$model->element]['icon'].'.gif').' <span class="label label-'.$label[$model->element]['label'].'">' .$elements[$model->element]. '</span>' : ''),
            'format' => 'raw',
        ]);
    }

    if($model->shop->information){
        array_push($attributes, [
            'label' => Yii::t('app', 'More Information'),
            'value' => nl2br(htmlspecialchars($model->shop->information)),
            'format' => 'raw',
        ]);
    }

    array_push($attributes,             
        'amount',
        'shop.shop_name',
        'shop.character',
        'created_at:datetime',
        'updated_at:datetime');
    ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

    <?php 
        $attributes = [];

        if($model->item->item_attack){
            array_push($attributes, [
                'label' => Yii::t('app', 'Attack'),
                'value' => $model->item->item_attack,
            ]);
        }

        if($model->item->item_defense){
            array_push($attributes, [
                'label' => Yii::t('app', 'Defanse'),
                'value' => $model->item->item_defense,
            ]);
        }

        if(!empty($model->item->jobs)){
            array_push($attributes, [
                'label' => Yii::t('app', 'Job'),
                'value' => implode(', ', ArrayHelper::getColumn($model->item->jobs, 'job_name')),
            ]);
        }

        if($model->item->item_class){
            array_push($attributes, [
                'label' => Yii::t('app', 'Type'),
                'value' => $model->item->item_class,
            ]);
        }

        if($model->item->item_required_lvl){
            array_push($attributes, [
                'label' => Yii::t('app', 'Required Lv'),
                'value' => $model->item->item_required_lvl. '+',
            ]);
        }

        if($model->item->item_weapon_lvl){
            array_push($attributes, [
                'label' => Yii::t('app', 'Weapon Lv'),
                'value' => $model->item->item_weapon_lvl,
            ]);
        }

        if($model->item->item_description){
            array_push($attributes, [
                'label' => Yii::t('app', 'Item Description'),
                'value' => $model->item->item_description,
            ]);
        }
    ?>

    <hr>
    <h3><?= Yii::t('app', 'Item Infomation') ?> <?= Html::img(Yii::$app->params['item_small_image_url']. ItemHelper::getImgFileName($model->item)) ?></h3> 

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $attributes,
    ]) ?>

</div>

<div class="row">
    <div class="col-md-12">
        <div class="fb-comments" data-numposts="5" data-width="680"></div>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/th_TH/sdk.js#xfbml=1&version=v2.7&appId=591767224330218";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
    </div>
</div>