<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\View;
use frontend\assets\Select23Asset;
use common\models\Item;
use common\models\Shop;
use yii\widgets\MaskedInput;
use kartik\icons\Icon;

Icon::map($this);  

Select23Asset::register($this);

/* @var $this yii\web\View */
/* @var $model common\models\Shop */
/* @var $form yii\widgets\ActiveForm */

$maps = '';
foreach(glob('../web/images/maps/*.*') as $filename){
    $filename = str_replace('../web/images/maps/', '', $filename);
    $filename = str_replace('.gif', '', $filename);
    $maps .= '{id: "'.$filename.'",text: "'.$filename.'"},';
 }

$items = '';
foreach($item_model as $item){
    $items .= '{id: "'.$item->source_id.'",text: "'.$item->nameSlot.'",type: "'.$item->item_type_id.'",slot: "'.$item->item_slot.'"},';
}

$this->registerJs("
    $('#shop-map').change(function(){
        var map = $('.map-picker>img');
        map.attr('src', '". Yii::getAlias('@web') ."/images/maps/' + $(this).val() + '.gif');
        $('div.dot').remove();
        $('#shop-location').val('');
    });

    var size = 8;
    var origin = $('.map-picker').offset();
    $('.map-picker').click(function(e){
        $('div.dot').remove();
        $('body').append(
            $('<div class=\"dot\"></div>').css({
                position: 'absolute',
                top: (e.pageY-size/2) + 'px',
                left: (e.pageX-size/2) + 'px',
                width: size + 'px',
                height: size + 'px',
                background: 'red'
            })
        );

        var x = e.pageX - origin.left - 7;
        var y = e.pageY - origin.top - 7;

        $('#shop-location').val(x + ',' + y);

    });

    if($('#shop-location').val() !== ''){
        axis = $('#shop-location').val().split(',');
        $('body').append(
            $('<div class=\"dot\"></div>').css({
                position: 'absolute',
                top: (origin.top + parseInt(axis[1])) + 'px',
                left: (origin.left + parseInt(axis[0])) + 'px',
                width: size + 'px',
                height: size + 'px',
                background: 'red'
            })
        );
    }

    var items = [".$items."];
    var maps = [".$maps."];
    var query = function (q) {
        var pageSize,
            results;
            pageSize = 20;
            results  = _.filter(this.data, function (e) {
                var searchText = q.term;
                if(searchText !== undefined){
                    searchText = searchText.toLowerCase();
                }
                return (searchText === '' || e.text.toLowerCase().indexOf(searchText) >= 0);
            });
        q.callback({
            results: results.slice((q.page - 1) * pageSize, q.page * pageSize),
            more   : results.length >= q.page * pageSize
        });
    }

    $('.select2item').select2({
        data: items,
        formatLoadMore   : 'Loading more...',
        formatResult: repoFormatResult,
        formatSelection: repoFormatSelection,
        escapeMarkup: function (m) { return m; },
        dropdownCssClass: 'bigdrop',
        query            : query,
    });

    $('.select2card').select2({
        data: _.filter(items, function (e) { return e.type == 6; }),
        formatLoadMore   : 'Loading more...',
        formatResult: repoFormatResult,
        formatSelection: repoFormatSelection,
        escapeMarkup: function (m) { return m; },
        dropdownCssClass: 'bigdrop',
        query            : query,
    });

    $('.select2map').select2({
        data: maps,
        formatLoadMore   : 'Loading more...',
        formatResult: getSelectTemplateForMap,
        formatSelection: getSelectTemplateForMap,
        escapeMarkup: function (m) { return m; },
        dropdownCssClass: 'bigdrop',
        query            : query,
    });

    $('.select2item').change(function(){

        var item = $(this).closest('.item');

        $('.weapon, .armor', item).hide();
        $('[name=\"isElement\"]', item).attr('checked', false);
        resetItemElement(item);
        $('.select2card', item).each(function(){ 
            $(this).select2('val', ''); 
            $(this).val('');
            $(this).parent().find('.select2-chosen').html('');
        });
        $('select[id$=\"-enhancement\"]>option:eq(0)', item).prop('selected', true);

        if($(this).select2('data').type == 5){
            $('.weapon.enhancement', item).show();

            if($(this).select2('data').slot == 0){
                $('.weapon.is-element', item).show();
            }else{
                $('.card-slot', item).show();
                for(i = 0; i < $(this).select2('data').slot; i++){
                    $('.card-slot>.slot:eq('+ i +')', item).show();
                }
            }
        }else if($(this).select2('data').type == 4){
            $('.armor.enhancement', item).show();

            if($(this).select2('data').slot > 0){
                $('.card-slot', item).show();
                $('.card-slot>.slot.armor', item).show();
            }
        }
    });

    $('[name=\"isElement\"]').click(function(){
        var item = $(this).closest('.item');
        
        if($(this).is(':checked') == true){
            $('.element', item).show();
        } else{
            $('.element', item).hide();
            resetItemElement(item);
        } 
    });

    function resetItemElement(item){
        $('select[id$=\"-very\"]>option:eq(0)', item).prop('selected', true);
        $('select[id$=\"-element\"]>option:eq(0)', item).prop('selected', true);
    }

    $( 'ul.select2-results' ).bind( 'mousewheel DOMMouseScroll', function ( e ) {
        var e0 = e.originalEvent,
            delta = e0.wheelDelta || -e0.detail;

        this.scrollTop += ( delta < 0 ? 1 : -1 ) * 100;
        e.preventDefault();
    });
    
    $('input.select2item, input.select2card').filter(function() { return $(this).val(); }).each(function(){
        var default_value = $(this).val();
        var item = _.filter(items, function (e) { return e.id == default_value; });
        $(this).parent().find('.select2-chosen').html(getSelectTemplate(item[0]));
    });
  
    function repoFormatResult(item) {
        return getSelectTemplate(item);
    }

    function repoFormatSelection(item) {
        return getSelectTemplate(item);
    }

    function getSelectTemplate(item) {
        var card = 'card';
        var item_img = item.text.toLowerCase().indexOf(card) > -1 ? card : item.id;
        return '<div class=\"span2\"><img src=\"".Yii::$app->params['item_small_image_url']. "' + item_img + '.gif\" /> '+ item.text +'</div>';
    }

    function getSelectTemplateForMap(map) {
        return '<div class=\"span2\"><img src=\"". Yii::getAlias('@web') ."/images/maps/'+ map.text +'.gif\" style=\"width:25px\"/> '+ map.text +'</div>';
    }
", View::POS_READY);

$this->registerCss("
.map-picker {
    height: 400px;
    width: 400px;
}
.map-picker>img {
    height: 100%;
    width: 100%;
}
.weapon, .armor, .card-slot, .slot{ display: none; }
.bigdrop.select2-container .select2-results {max-height: 300px;}
");
?>

<div class="shop-form">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Shop Information') ?></h3>
        </div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($shop_model, 'shop_type')->dropDownList(['s' => Yii::t('app', 'Sale'), 'b' => Yii::t('app', 'Buy')]) ?>
                    <?= $form->field($shop_model, 'shop_name')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($shop_model, 'character')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($shop_model, 'map')->hiddenInput(['class'=> 'select2map', 'style' => 'width: 100%;']) ?>
                    <?= $form->field($shop_model, 'location')->textInput(['maxlength' => true, 'readonly' => true, 'placeholder' => Yii::t('app', 'Click on the map above to fill location.')]) ?>
                    <?= $form->field($shop_model, 'server')->hiddenInput(['value'=> Yii::$app->request->get('server')])->label(false) ?>
                </div>
                <div class="col-md-6">
                    <div class="map-picker">
                    <?= Html::img(Yii::getAlias('@web'). '/images/maps/'. ($shop_model->map ? $shop_model->map : 'alberta') .'.gif') ?>
                    </div>
                    <p><?= Yii::t('app', 'Click on the map above to fill location.') ?></p>
                </div>
                <div class="col-md-12">
                    <?= $form->field($shop_model, 'information')->textArea(['rows' => '3', 'style' => 'resize: vertical;']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?= Yii::t('app', 'Your Goods') ?></h3>
        </div>
    <div class="panel-body">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?= Yii::t('app', 'Item Name') ?></th>
                    <th><?= Yii::t('app', 'Amount') ?></th>
                    <th><?= Yii::t('app', 'Price') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $shop_item_model = is_array($shop_item_model) ? $shop_item_model : [$shop_item_model];
                for($slot = 0; $slot <= 11; $slot++){
                ?>
                    <tr>
                        <td class="col-sm-1"><?= $slot+1 ?></td>
                        <td class="col-sm-7 item">
                            <div class="row">
                                <div class="col-sm-8">
                                    <?= $form->field($shop_item_model[$slot], "[$slot]item_id")
                                        ->hiddenInput(['class'=> 'select2item', 'style' => 'width: 100%;'])
                                        ->label(false) 
                                    ?>
                                </div>
                                <div class="col-sm-2 weapon armor enhancement">
                                    <?= $form->field($shop_item_model[$slot], "[$slot]enhancement")
                                        ->dropDownList(Item::getEnhancements())
                                        ->label(false)
                                    ?>
                                </div>
                                <div class="col-sm-2 weapon is-element">
                                    <?= Html::checkbox('isElement', [], ['label' => 'ธาตุ']) ?>
                                </div>
                            </div>
                            <div class="row weapon armor card-slot">
                                <div class="col-md-3 weapon armor slot">
                                    <?= $form->field($shop_item_model[$slot], "[$slot]card_1")
                                        ->textInput(['class' => 'select2card', 'style' => 'width: 100%'])
                                        ->label(false) ?>
                                </div>
                                <div class="col-md-3 weapon slot">
                                    <?= $form->field($shop_item_model[$slot], "[$slot]card_2")
                                        ->textInput(['class' => 'select2card', 'style' => 'width: 100%'])
                                        ->label(false) ?>
                                </div>
                                <div class="col-md-3 weapon slot">
                                    <?= $form->field($shop_item_model[$slot], "[$slot]card_3")
                                        ->textInput(['class' => 'select2card', 'style' => 'width: 100%'])
                                        ->label(false) ?>
                                </div>
                                <div class="col-md-3 weapon slot">
                                    <?= $form->field($shop_item_model[$slot], "[$slot]card_4")
                                        ->textInput(['class' => 'select2card', 'style' => 'width: 100%'])
                                        ->label(false) ?>
                                </div>
                            </div>

                            <div class="row weapon element">
                                <div class="col-md-3">
                                    <?= $form->field($shop_item_model[$slot], "[$slot]very")
                                        ->dropDownList(Item::getVeries())
                                        ->label(false) ?>
                                </div>
                                <div class="col-md-3">    
                                    <?= $form->field($shop_item_model[$slot], "[$slot]element")
                                        ->dropDownList(Item::getElements())
                                        ->label(false) ?>
                                </div>
                            </div>
                        </td>
                        <td class="col-sm-2"> <?= $form->field($shop_item_model[$slot], "[$slot]amount")->textInput()->label(false) ?> </td>
                        <td class="col-sm-2"> <?= $form->field($shop_item_model[$slot], "[$slot]price")->textInput()->label(false) ?> </td>
                    </tr>

                <?php 
                    $js = "";
                    if($shop_item_model[$slot]->card_1){
                        $js .= "$('.card-slot:eq(".$slot.")').show();";
                        $js .= "$('.card-slot:eq(".$slot.") .slot:eq(0)').show();";
                    }              
                    if($shop_item_model[$slot]->card_2){
                        $js .= "$('.card-slot:eq(".$slot.")').show();";
                        $js .= "$('.card-slot:eq(".$slot.") .slot:eq(1)').show();";
                    }              
                    if($shop_item_model[$slot]->card_3){
                        $js .= "$('.card-slot:eq(".$slot.")').show();";
                        $js .= "$('.card-slot:eq(".$slot.") .slot:eq(2)').show();";
                    }              
                    if($shop_item_model[$slot]->card_4){
                        $js .= "$('.card-slot:eq(".$slot.")').show();";
                        $js .= "$('.card-slot:eq(".$slot.") .slot:eq(3)').show();";
                    }
                    if($shop_item_model[$slot]->enhancement != ''){
                        $js .= "$('.enhancement:eq(".$slot.")').show();";
                    }
                    if($shop_item_model[$slot]->element){
                        $js .= "$('.element:eq(".$slot.")').show();";
                    }
                    if($shop_item_model[$slot]->item['item_slot'] == 0 && $shop_item_model[$slot]->item['item_type_id'] == 5){
                        $js .= "$('.is-element:eq(".$slot.")').show();";
                        if($shop_item_model[$slot]->element)
                            $js .= "$('[name=\"isElement\"]:eq(".$slot.")').prop('checked', true);";
                    }
                    $this->registerJs($js, View::POS_READY);
                } ?>
            <tbody>
        </table>
    </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($shop_model->isNewRecord ? Icon::show('opencart'). Yii::t('app', 'Open Shop') : Icon::show('opencart'). Yii::t('app', 'Update Shop'), ['class' => $shop_model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'width: 100%;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>