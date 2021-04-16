<?php

use common\models\Apple;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Apple */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

  <?php $form = ActiveForm::begin([
    'options' => [
      'enctype' => 'multipart/form-data'
    ]
  ]); ?>

  <?= $form->field($model, 'percent')->textInput(); ?>

  <div class="form-group">
    <?= Html::submitButton('Eat', ['class' => 'btn btn-success']) ?>
  </div>

  <?php ActiveForm::end(); ?>

</div>