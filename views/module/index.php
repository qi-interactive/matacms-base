<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel mata\contentblock\models\ContentBlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = \Yii::$app->controller->id;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-block-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(sprintf('Create %s', \Yii::$app->controller->id), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php

    $columns = [];
    $columns[] = ['class' => 'yii\grid\SerialColumn'];

    foreach ($searchModel->safeAttributes() as $attribute)
        $columns[] = $attribute;



// print_r(current($dataProvider->getModels())->getRelatedRecords());
    // $columns[] = "value.Value";

    $columns[] = ['class' => 'yii\grid\ActionColumn'];



    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
        ]); ?>

    </div>
