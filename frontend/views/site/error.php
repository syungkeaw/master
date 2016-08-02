<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::img(Yii::getAlias('@web'). '/images/error_evil.gif') ?><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>
    <p>
        โปรดวางใจ! ความผิดพลาดนี้ได้ถูกส่งไปยังทีมงานเรียบร้อยแล้ว
    </p>
    <p>
        ปัญหานี้จะถูดจัดการอย่างเร็วที่สุด ขออภัยในความไม่สะดวกด้วยนะคะ
    </p>

    <p><strong>RO108 Team</strong></p>
</div>
