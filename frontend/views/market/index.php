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
use yii\helpers\Url;
use kartik\icons\Icon;

Icon::map($this);  

/* @var $this yii\web\View */
/* @var $searchModel common\models\ShopItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'RO108 :: Easy to Buy & sell Ragnarok Online\'s Items Server Thor, Loki, Eden');
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
    $('[data-toggle=\'tooltip\']').tooltip();
", View::POS_READY);

if(Yii::$app->user->isGuest){
    echo Html::a(Icon::show('shopping-cart'). Yii::t('app', 'Open Shop'), [Yii::$app->request->get('server').'/shop/create'], ['class' => 'btn btn-success']);
}else{
    echo Html::a(Icon::show('shopping-cart'). Yii::t('app', 'My Shop'), [Yii::$app->request->get('server').'/shop'], ['class' => 'btn btn-success']);
}

?>

<div class="shop-item-index">
<?php Pjax::begin(['timeout' => 15000 ]); ?>
<h3><?= $server ?> <small><?= Html::a('<span class="glyphicon glyphicon-refresh"></span> '. Yii::t('app', 'Clear & Refresh'), ['']) ?></small></h3>
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
                'headerOptions' => [
                    'class' => 'col-md-4'
                ],
            ],
            [
                'attribute' => 'enhancement',
                'value' => function($model){
                    return $model->enhancement ? '+'.$model->enhancement : '';
                },
               'headerOptions' => [
                    'class' => 'col-md-1'
                ],
                'filter' => Html::dropDownList(
                    'ShopItemSearch[enhancement]',
                    $searchModel['enhancement'],
                    ['' => ''] + Item::getEnhancements(),
                    ['class' => 'form-control']
                ),
            ],
            [
                'attribute' => 'option',
                'value' => function($model) use ($elements, $very, $items, $label){
                    $option = '';

                    foreach(range(1, 4) as $slot){
                        $option .= $model->{'card_'.$slot} ? '['. Html::img(Yii::$app->params['item_small_image_url'] . 'card.gif') . $model->{'itemCard'.$slot}['item_name'] . ']<br>' : '';
                    }

                    $option .= $model->very ? ' '. $very[$model->very] : '';
                    $option .= $model->element ? 
                        Html::img(Yii::$app->params['item_small_image_url']. $label[$model->element]['icon'] . '.gif').
                        ' <span class="label label-'. $label[$model->element]['label'] .'">'. $elements[$model->element].'</span>' : '';
                    return $option;
                },
                'headerOptions' => [
                    'class' => 'col-md-2'
                ],
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
               'headerOptions' => [
                    'class' => 'col-md-2'
                ],
            ],
            [
                'attribute' => 'shop.character',
                'label' => Yii::t('app', 'Owner'),
                'value' => function($model){
                    return '<div class="ellipsis" title="'. $model->shop->character .'">'. $model->shop->character. '</div>';
                },
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-1'
                ],
            ],
            [
                'attribute' => 'shop.shop_name',
                'label' => Yii::t('app', 'Shop Name'),
                'value' => function($model){
                    return '<div class="ellipsis" title="'. $model->shop->shop_name .'">'. $model->shop->shop_name. '</div>';
                },
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-1',
                ],
            ],
            [
                'attribute' => 'updated_at',
                'label' => Yii::t('app', 'Latest'),
                'format' => ['date', 'php:d-H:i'],
                'filter' => false,
                'headerOptions' => [
                    'class' => 'col-md-1'
                ],
            ],
            [
                'value' => function($model){
                    $items = [];
                    array_push($items, [
                        'label' => Icon::show('eye-slash'). Yii::t('app', 'Hide'),
                        'url' => ['hide', 'id' => $model->id],
                        'options' => [
                            'data-toggle' => 'tooltip',
                            'title' => Yii::t('app', 'Click to hide it if you are not interested in this item.'),
                        ]
                    ]);

                    array_push($items, [
                        'label' => Icon::show('bug'). Yii::t('app', 'Mark Delete'). '('. $model['delete_count'] .')',
                        'url' => ['mark-delete', 'id' => $model->id],
                        'options' => [
                            'data-toggle' => 'tooltip',
                            'title' => Yii::t('app', 'Report if this item is spam.'),
                        ]
                    ]);

                    if(!Yii::$app->user->isGuest){
                        array_push($items, 
                        '<li class="divider"></li>',
                        [
                            'label' => Icon::show('thumbs-down'). Yii::t('app', 'Dislike'),
                            'url' => ['feedback', 'id' => $model->id, 'feedback_id' => 1],
                            'options' => [
                                'data-toggle' => 'tooltip',
                                'title' => Yii::t('app', 'Click to give owner bad feedback if many bad feedback, This item will be deleted.'),
                            ]
                        ],
                        [
                            'label' => Icon::show('thumbs-up'). Yii::t('app', 'Like'),
                            'url' => ['feedback', 'id' => $model->id, 'feedback_id' => 2],
                            'options' => [
                                'data-toggle' => 'tooltip',
                                'title' => Yii::t('app', 'Click to give owner good feedback. Give them more motivation.'),
                            ]
                        ]
                        );
                    }

                    $menu = Html::beginTag('div', ['class'=>'dropdown']);
                    $menu .= Html::a('<span class="glyphicon glyphicon-option-horizontal"></span>', [''], ['data-toggle'=>'dropdown']);
                    $menu .= DropdownX::widget([
                        'items' => $items,
                        'encodeLabels' => false,
                    ]); 
                    $menu .= Html::endTag('div');
                    return $menu;
                },
                'format' => 'raw',
                'headerOptions' => [
                    'class' => 'col-md-1'
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
            iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
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

