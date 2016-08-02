<?php

/* @var $this \yii\web\View */
/* @var $content string */

use frontend\assets\AppAsset;
use yii\helpers\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<link rel="shortcut icon" href="<?= Yii::getAlias('@web') ?>/images/favicon.png" type="image/x-icon" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body style="background: #f1f1f1">
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="container-fluid" style="background: #fff">
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
