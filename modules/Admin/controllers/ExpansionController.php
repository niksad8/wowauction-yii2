<?php

namespace app\modules\Admin\controllers;

use Yii;
use app\models\Expansion;
use app\models\ExpansionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExpansionController implements the CRUD actions for Expansion model.
 */
class ExpansionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Expansion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpansionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Expansion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Expansion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Expansion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(isset($_FILES['server_logo']) && $_FILES['server_logo']['tmp_name'] != ""){
                move_uploaded_file($_FILES['server_logo']['tmp_name'],Yii::$app->getBasePath().DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."expansions".DIRECTORY_SEPARATOR.$model->id);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Expansion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(isset($_FILES['server_logo']) && $_FILES['server_logo']['tmp_name'] != ""){
                move_uploaded_file($_FILES['server_logo']['tmp_name'],Yii::$app->getBasePath().DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."expansions".DIRECTORY_SEPARATOR.$model->id);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Expansion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Expansion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expansion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expansion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
