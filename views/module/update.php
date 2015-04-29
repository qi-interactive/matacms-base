<?php

use yii\helpers\Html;
use mata\arhistory\behaviors\HistoryBehavior;

$this->title = 'Update ' . $model->getModelLabel() . ': ' . ' ' . $model->getLabel();
$this->params['breadcrumbs'][] = ['label' => 'Content Blocks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->getLabel(), 'url' => ['view', 'id' => $model->getPrimaryKey()]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div><?= Html::a("Back to list view", sprintf("/mata-cms/%s/%s", $this->context->module->id, $this->context->id), ['id' => 'back-to-list-view']);?></div>

<?php

echo Html::a("Versions", sprintf("history?documentId=%s&returnURI=%s", $model->getDocumentId()->getId(), Yii::$app->request->url), ['id' => 'versions-link']);

?>
<div class="content-block-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render("_form", [
        'model' => $model,
    ]) ?>

</div>
