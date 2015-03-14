<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model mata\contentblock\models\ContentBlock */

$this->title = sprintf('Create %s', \Yii::$app->controller->id);
$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-block-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render(\Yii::$app->controller->findView("_form"), [
        'model' => $model,
    ]) ?>

</div>
