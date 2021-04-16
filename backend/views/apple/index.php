<?php

use common\models\Apple;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">

  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    <?= Html::a('Создать случайный набор яблок', ['generate'], ['class' => 'btn btn-success']) ?>
  </p>

  <p>
    <?= Html::a('Удалить все яблоки', ['clear'], ['class' => 'btn btn-danger']) ?>
  </p>

  <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
      ['class' => 'yii\grid\SerialColumn'],

      'id',
      [
        'attribute' => 'color',
        'format' => 'raw',
        'value' => function ($data) {
          /** @var Apple $data */

          return Html::tag("span", $data->color, ["style" => "color: {$data->color}"]);
        }
      ],
      'date_created',
      'date_dropped',
      [
        'attribute' => 'status',
        'value' => function ($data) {
          /** @var Apple $data */

          return Apple::$statusTitles[$data->status];
        }
      ],
      [
        'attribute' => 'balance',
        'value' => function ($data) {

          /** @var Apple $data */

          return "{$data->balance}%";
        }
      ],
      [
        'format' => 'raw',
        'value' => function ($data) {
          /** @var Apple $data */

          switch ($data->status) {

            case Apple::STATUS_NEW;
              return Html::a("Уронить", ["drop", "id" => $data->id], [
                "class" => "btn btn-warning",
              ]);

            case Apple::STATUS_DROPPED;
              return Html::a("Съесть", ["eat", "id" => $data->id], [
                "class" => "btn btn-success",
              ]);

            case Apple::STATUS_CORRUPTED;
              return Html::a("Выбросить", ["delete", "id" => $data->id], [
                "class" => "btn btn-danger",
                "data-confirm" => "Выбросить яблоко?",
                "data-method" => "post",
              ]);

          }

          throw new Exception("Unknown status - {$data->status}");

        },
      ],
    ],
  ]); ?>

</div>