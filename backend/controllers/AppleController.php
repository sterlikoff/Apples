<?php

namespace backend\controllers;

use backend\models\AppleEatForm;
use common\models\Apple;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors() {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'delete' => ['POST'],
        ],
      ],
    ];
  }

  /**
   * Lists all Apple models.
   *
   * @return mixed
   */
  public function actionIndex() {

    $dataProvider = new ActiveDataProvider([
      'query' => Apple::find()->andFilterWhere([
        "status" => [
          Apple::STATUS_NEW,
          Apple::STATUS_DROPPED,
          Apple::STATUS_CORRUPTED,
        ]
      ]),
    ]);

    return $this->render('index', [
      'dataProvider' => $dataProvider,
    ]);
  }

  public function actionGenerate() {
    Apple::generate();
    $this->redirect(["index"]);
  }

  public function actionClear() {
    Apple::clear();
    $this->redirect(["index"]);
  }

  /**
   * @param int $id
   * @throws NotFoundHttpException
   * @throws Exception
   */
  public function actionDrop($id) {

    $model = $this->findModel($id);

    $model->drop();
    $this->redirect(["index"]);

  }

  /**
   * @param int $id
   * @throws NotFoundHttpException
   * @throws Exception
   */
  public function actionEat($id) {

    $model = new AppleEatForm([], $this->findModel($id));;

    if ($model->load(Yii::$app->request->post()) && $model->eat()) {
      $this->redirect(["index"]);
    }

    return $this->render("eat", [
      "model" => $model,
    ]);

  }

  /**
   * @param int $id
   * @throws NotFoundHttpException
   */
  public function actionDelete($id) {

    $this->findModel($id)->delete();
    $this->redirect(["index"]);

  }

  /**
   * Finds the Apple model based on its primary key value.
   * If the model is not found, a 404 HTTP exception will be thrown.
   *
   * @param integer $id
   * @return Apple the loaded model
   * @throws NotFoundHttpException if the model cannot be found
   */
  protected function findModel($id) {
    if (($model = Apple::findOne($id)) !== null) {
      return $model;
    }

    throw new NotFoundHttpException('The requested page does not exist.');
  }

}
