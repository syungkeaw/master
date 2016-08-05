<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\classes\ItemHelper;
use common\classes\RoHelper;
use kartik\typeahead\Typeahead;
use yii\helpers\ArrayHelper;
use yii\web\View;
use common\models\Item;
use common\models\Shop;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\dropdown\DropdownX;
use kartik\icons\Icon;
use yii\helpers\Url;

Icon::map($this);  

$this->title = Yii::t('app', 'My Shop');
$this->params['breadcrumbs'][] = $this->title;

$elements = Item::getElements();
$very = Item::getVeries();
$label = [
    '',
    ['label' => 'danger', 'icon' => '994'],
    ['label' => 'info', 'icon' => '995'],
    ['label' => 'success', 'icon' => '996'],
    ['label' => 'default', 'icon' => '997'],
];

$this->registerJs("
", View::POS_READY);
?>

<div class="shop-item-index">
<div class="row">  
    <div class="col-md-6">
        <p>
            <?= Html::a(Icon::show('cart-plus'). Yii::t('app', 'Open Shop'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <div class="col-md-6 text-right">
        <?= Html::a('<span class="glyphicon glyphicon-shopping-cart"></span> '. Yii::t('app', 'Cart View'), ['cart'], ['class' => 'btn btn-default']) ?>
    </div>
</div>


<?php Pjax::begin(['timeout' => 15000 ]); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'item.item_name',
                'label' => Yii::t('app', 'Selling Items'),
                'value' => function($model){
                    $item = Html::img(Yii::$app->params['item_small_image_url'].
                        ItemHelper::getImgFileName($model->item)) .' '.
                        Html::a($model->item['nameSlot'], '#', [
                            'class' => 'modalButton',
                            'data-toggle' => 'modal',
                            'data-target' => '#detailModal',
                            'onClick' => '$("#detailModal iframe").attr("src", "'. Url::to([Yii::$app->request->get('server'). '/market/detail', 'id' => $model->id]) .'")',
                        ]);
                    return $item .
                        ($model->shop['created_by'] ? ' '. Icon::show('registered', [
                            'class' => 'text-success',
                            'style' => 'font-size:12px',
                            'data-toggle' => 'tooltip',
                            'title' => Yii::t('app', 'registered'),
                        ]) : '');
                },
                'format' => 'raw',
                'filter' => Typeahead::widget([
                    'name' => 'ShopItemSearch[item.item_name]',
                    'value' => $searchModel['item.item_name'],
                    'dataset' => [
                        [
                            'local' => ArrayHelper::getColumn($items, 'item_name'),
                            'limit' => 10,
                        ],
                    ],
                    'pluginOptions' => ['highlight' => true],
                    'pluginEvents' => [
                        "typeahead:change" => "function() { $(this).change() }",
                        "typeahead:select" => "function() { $(this).change() }",
                    ],

                ]),
            ],
            [
                'attribute' => 'enhancement',
                'label' => Yii::t('app', 'Enhancement'),
                'value' => function($model){
                    return $model->enhancement ? '+'.$model->enhancement : '';
                },
                'filter' => Html::dropDownList(
                    'ShopItemSearch[enhancement]',
                    $searchModel['enhancement'],
                    ['' => ''] + Item::getEnhancements(),
                    ['class' => 'form-control']
                ),
            ],
            [
                'attribute' => 'option',
                'label' => Yii::t('app', 'Option'),
                'value' => function($model) use ($elements, $very, $items, $label){
                    $option = '';

                    foreach(range(1, 4) as $slot){
                        $option .= $model->{'card_'.$slot} ? '['. Html::img(Yii::$app->params['item_small_image_url']. 'card.gif') . $model->{'itemCard'.$slot}['item_name'] . ']<br>' : '';
                    }

                    $option .= $model->very ? ' '. $very[$model->very] : '';
                    $option .= $model->element ? 
                        Html::img(Yii::$app->params['item_small_image_url'] . $label[$model->element]['icon']. '.gif').
                        ' <span class="label label-'. $label[$model->element]['label'] .'">'. $elements[$model->element].'</span>' : '';
                    return $option;
                },
                'format' => 'raw',
                'filter' => Select2::widget([
                    'name' => 'ShopItemSearch[option]',
                    'value' => $searchModel['option'],
                    'data' => ArrayHelper::map($option_item, 'source_id', 'nameSlot'),
                    'options' => ['placeholder' => Yii::t('app', 'Select a card or an element ...')],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'templateResult' => new JsExpression('function format(item) {
                            return \'<img src="'. Yii::$app->params['item_small_image_url'] .'\' + (item.text.toLowerCase().indexOf(\'card\') > -1 ? \'card\' : item.id) + \'.gif"/> \' + item.text;
                        }'),
                        'escapeMarkup' => new JsExpression('function(m) { return m; }'),
                    ],
                ]),
            ],
            [
                'attribute' => 'price',
                'label' => Yii::t('app', 'Price (Zeny)'),
                'value' => function($model){
                    return number_format($model->price);
                },
            ],
            [
                'attribute' => 'shop.shop_name',
                'label' => Yii::t('app', 'Shop Name'),
                // 'filter' => Html::dropDownList(
                //     'ShopItemSearch[shop.shop_name]', 
                //     $searchModel['shop.shop_name'], 
                //     ['' => 'All'] + ArrayHelper::map(Shop::findAll(['created_by' => Yii::$app->user->identity->id]), 'shop_name', 'shop_name'), 
                //     ['class' => 'form-control']
                // ),
                'value' => function($model){
                    return '<div class="ellipsis" title="'. $model->shop->shop_name .'">'. $model->shop->shop_name. '</div>';
                },
                'format' => 'raw',
            ],
            [
                'label' => Yii::t('app', 'Status'),
                'value' => function($model){
                    return $model->shop['status'] == 10 && $model['status'] == 10 ? 
                        '<span class="glyphicon glyphicon-ok text-green"></span>' : 
                        '<span class="glyphicon glyphicon-remove text-red"></span>';
                },
                'filter' => Html::dropDownList(
                    'ShopItemSearch[shop.status]',
                    $searchModel['shop.status'],
                    [
                        '' => Yii::t('app', 'All'),
                        '10' => Yii::t('app', 'Open'),
                        '0' => Yii::t('app', 'Close'),
                    ], 
                ['class' => 'form-control']),
                'format' => 'raw',
            ],
            [
                'attribute' => 'shop.shop_type',
                'label' => Yii::t('app', 'Shop Type'),
                'value' => function($model){
                    return $model->shop['shop_type'] == 's' ? 
                        Icon::show('usd', ['class' => 'text-success', 'title' => Yii::t('app', 'selling'), 'data-toggle' => 'tooltip']) :
                        Icon::show('btc', ['class' => 'text-info', 'title' => Yii::t('app', 'buying'), 'data-toggle' => 'tooltip']);
                },
                'contentOptions' => ['style' => 'text-align:center;'],
                'format' => 'raw',
                'filter' => Html::dropDownList(
                    'ShopItemSearch[shop.shop_type]',
                    $searchModel['shop.shop_type'],
                    [
                        '' => Yii::t('app', 'All'),
                        's' => Yii::t('app', 'Sale'),
                        'b' => Yii::t('app', 'Buy'),
                    ],
                    ['class' => 'form-control']
                ),
            ],
            [
                'label' => Yii::t('app', 'Feedback'),
                'value' => function($model){
                    return Icon::show('thumbs-up'). ' '. $model->like. ' '. Icon::show('thumbs-down') .' ' .$model->report;
                },
                'format' => 'raw',
            ],
            [
                'value' => function($model){
                    $menu = Html::beginTag('div', ['class'=>'dropdown']);
                    $menu .= Html::a('<span class="glyphicon glyphicon-option-horizontal"></span>', [''], ['data-toggle'=>'dropdown']);
                    $menu .= DropdownX::widget([
                        'items' => [
                            ['label' => Yii::t('app', 'Update Shop'), 'url' => ['update', 'id' => $model->shop['id']]],
                            '<li class="divider"></li>',
                            ($model->shop['status'] == 10 ? 
                                ['label' => Yii::t('app', 'Close Shop'), 'url' => ['close', 'id' => $model->shop['id']]] :
                                ['label' => Yii::t('app', 'Open Shop'), 'url' => ['open', 'id' => $model->shop['id']]])
                            ,
                            // ['label' => 'Delete Shop', 'url' => ['delete', 'id' => $model->shop['id']]],
                            ($model['status'] == 10 ? 
                                ['label' => Yii::t('app', 'Close Item'), 'url' => ['shop-item/close', 'id' => $model['id']]] :
                                ['label' => Yii::t('app', 'Open Item'), 'url' => ['shop-item/open', 'id' => $model['id']]])
                        ],
                    ]); 
                    $menu .= Html::endTag('div');
                    return $menu;
                },
                'format' => 'raw',
                'headerOptions' => [
                    'style' => 'width:30px'
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<script type="text/javascript">
    function iframeLoaded() {
        var iFrameID = document.getElementById('iframeDetail');
        if(iFrameID) {
            // here you can make the height, I delete it first, then I make it again
            iFrameID.height = "";
            iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + 280 + "px";
        }   
    }
</script>   
<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel">
  <div class="modal-dialog" role="document" style="width:50%;min-width: 750px;">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <iframe id="iframeDetail" frameborder="0" style="width:100%;" onload="iframeLoaded()"></iframe>
        </div>
    </div>
  </div>
</div>


