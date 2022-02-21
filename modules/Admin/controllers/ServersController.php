<?php

namespace app\modules\Admin\controllers;

use app\models\Realm;
use Yii;
use app\models\Servers;
use app\models\ServersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServersController implements the CRUD actions for Servers model.
 */
class ServersController extends Controller
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
     * Lists all Servers models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servers model.
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
     * Creates a new Servers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Servers();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(isset($_FILES['server_logo']) && $_FILES['server_logo']['tmp_name'] != ""){
                move_uploaded_file($_FILES['server_logo']['tmp_name'],Yii::$app->getBasePath().DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."servers".DIRECTORY_SEPARATOR.$model->id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    public function actionBase(){
        $id = Yii::$app->request->get("id");
        $realms = Realm::findAll(['server_id'=>$id]);
        $server =Servers::findOne(['id'=>$id]);
        if($server == null )
            return "";
        Yii::$app->session->set("server",$id);
        return $this->render("base",['realms'=>$realms,'server'=>$server]);
    }
    /**
     * Updates an existing Servers model.
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
                move_uploaded_file($_FILES['server_logo']['tmp_name'],Yii::$app->getBasePath().DIRECTORY_SEPARATOR."web".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."servers".DIRECTORY_SEPARATOR.$model->id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Servers model.
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
     * Finds the Servers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Servers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servers::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
