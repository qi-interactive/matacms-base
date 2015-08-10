<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="site-error">

    <h2><?= nl2br(Html::encode($message)) ?></h2>
    <p>Please visit our <a href="/mata-cms/site/welcome">HOMEPAGE</a></p>

</div>
