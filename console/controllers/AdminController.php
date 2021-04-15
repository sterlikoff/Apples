<?php

namespace console\controllers;

use common\models\User;
use yii\console\Controller;

class AdminController extends Controller
{

  public function actionIndex() {

    $user = new User();
    $user->username = "admin";
    $user->password = "admin";
    $user->status = User::STATUS_ACTIVE;
    $user->save();

  }

}