<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use common\classes\RoHelper;
use kartik\icons\Icon;
use yii\web\View;

Icon::map($this);  
AppAsset::register($this);

$this->registerJs("
    $(document).on('pjax:send', function() {
        $('#loading').show();
    });
    $(document).on('pjax:complete', function() {
      $('#loading').hide();
    });
", View::POS_READY);

$this->registerCss("
    #loading{
        position: fixed;
        left: 50%;
        top: 40%;
        z-index: 100;
        display:none;
    }
");


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta name="stats-in-th" content="a066" />
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web') ?>/images/favicon.ico" type="image/x-icon" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="
    background: url('<?= Yii::getAlias('@web') ?>/images/background-ragnarok-online-2.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size:cover;
">
<div id="loading"><img src="<?= Yii::getAlias('@web') ?>/images/loading.gif" /></div>
<div id="fb-root"></div>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Icon::show('cloud'). 'RO108',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        // ['label' => 'Home', 'url' => Yii::$app->homeUrl],
        
        ['label' => Icon::show('server'). 'Thor', 'url' => ['/thor/market'], 'options' => Yii::$app->request->get('server') == 'thor' ? ['class' => 'active'] : []],
        ['label' => Icon::show('server'). 'Loki', 'url' => ['/loki/market'], 'options' => Yii::$app->request->get('server') == 'loki' ? ['class' => 'active'] : []],
        ['label' => Icon::show('server'). 'Odin', 'url' => ['/odin/market'], 'options' => Yii::$app->request->get('server') == 'odin' ? ['class' => 'active'] : []],
        ['label' => Icon::show('server'). 'Eden', 'url' => ['/eden/market'], 'options' => Yii::$app->request->get('server') == 'eden' ? ['class' => 'active'] : []],

        // ['label' => 'Setting', 'url' => ['/shop/index'], 'visible' => !Yii::$app->user->isGuest],
        ['label' => Icon::show('user-plus'). Yii::t('app', 'Register'), 'url' => ['/user/registration/register'], 'visible' => Yii::$app->user->isGuest],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Icon::show('sign-in'). Yii::t('app', 'Sign in'), 'url' => ['/user/security/login']];
        $menuItems[] = ['label' => Icon::show('facebook-square'). Yii::t('app', 'Facebook Login'), 'url' => ['/user/security/auth?authclient=facebook']];
    } else {
        $menuItems[] = ['label' => Icon::show('sign-out'). Yii::t('app', 'Logout ({user_name})', ['user_name' => Yii::$app->user->identity->username]), 'url' => ['/user/security/logout'], 'linkOptions' => ['data-confirm' => Yii::t('app', 'Are you sure to logout RO108?'), 'data-method' => 'post']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
        'encodeLabels' => false,
    ]);
    NavBar::end();
    ?>

    <div class="container" style="background: #fff">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>      
    </div>

    <div class="container" style="background: #f1f1f1">
        <div class="row">
            <div class="col-md-offset-2 col-md-4">
                <div class="fb-page" data-href="https://www.facebook.com/ro108th/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/ro108shop/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/ro108shop/">RO108</a></blockquote></div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">ข่าวสาร</h3>
                    </div>
                    <div class="panel-body">
                        <h2>เปิดใช้งานแล้ว ^^</h2>
                        <h3> ฝากกด Like Page ด้วยนะ</h3>
                        <p>กำลังจะมีระบบบัญชีดำด้วยนะ..</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <p class="pull-left">&copy; Ro108 Beta <?= date('Y') ?></p>
            </div>
            <div class="col-md-3">
                <p class="pull-right">
                    <!-- Histats.com  (div with counter) --><div id="histats_counter" style="position: absolute;top: 18px;left: 40px;"></div>
                    <!-- Histats.com  START  (aync)-->
                    <script type="text/javascript">var _Hasync= _Hasync|| [];
                    _Hasync.push(['Histats.start', '1,3516688,4,1032,150,25,00011111']);
                    _Hasync.push(['Histats.fasi', '1']);
                    _Hasync.push(['Histats.track_hits', '']);
                    (function() {
                    var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
                    hs.src = ('//s10.histats.com/js15_as.js');
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
                    })();</script>
                    <noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?3516688&101" alt="" border="0"></a></noscript>
                    <div style="position: absolute;top: 12px;">
                        <script type="text/javascript" language="javascript1.1" src="http://tracker.stats.in.th/tracker.php?sid=68679"></script>
                        <noscript><a target="_blank"href="http://www.stats.in.th/">www.Stats.in.th</a></noscript>
                    </div>
                </p>
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>