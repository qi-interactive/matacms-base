<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model mata\contentblock\models\ContentBlock */

$this->title = sprintf('Create %s', \Yii::$app->controller->id);
$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div><?= Html::a("Back to list view", sprintf("/mata-cms/%s/%s", $this->context->module->id, $this->context->id), ['id' => 'back-to-list-view']);?></div>

<div class="content-block-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render("_form", [
        'model' => $model,
    ]) ?>

</div>
