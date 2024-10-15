<?php

/** @var yii\data\ArrayDataProvider $dataProvider */


use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Top 10 Authors for ' . $year . ' year';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'cnt',
        ],
    ]); ?>


</div>

