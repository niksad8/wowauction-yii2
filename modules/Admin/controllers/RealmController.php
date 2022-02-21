<?php

namespace app\modules\Admin\controllers;

use app\models\Factions;
use app\models\ProcessQueue;
use Yii;
use app\models\Realm;
use app\models\RealmSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RealmController implements the CRUD actions for Realm model.
 */
class RealmController extends Controller
{
    public $enableCsrfValidation = false;
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
    public function actionPosttobackend(){
        Yii::$app->response->format = Response::FORMAT_JSON;
        $filename = Yii::$app->request->post("filename");
        $realmid = Yii::$app->request->post("realm");
        $factionid = Yii::$app->request->post("faction");
        $realm =Realm::findOne(['id'=>$realmid]);
        if($realm == NULL){
            return ['status'=>'error','message'=>'Realm not found!'];
        }
        $faction = Factions::findOne(['id'=>$factionid]);
        if($realm == NULL){
            return ['status'=>'error','message'=>'Faction Not Found'];
        }
        $check = ProcessQueue::findOne(['filename'=>$filename,'realm_id'=>$realmid,'faction_id'=>$factionid,'completed'=>0]);
        if($check != NULL){
            return ['status'=>'error','message'=>'Request already posted for this file'];
        }
        else {
            $process = new ProcessQueue();
            $process->completed = 0;
            $process->datetime_posted = date('Y-m-d H:i:s');
            $process->faction_id = $factionid;
            $process->filename = $filename;
            $process->realm_id = $realmid;
            $process->save();
            print_r($process->getErrors());
            return ['status'=>'success','message'=>'done'];
        }
    }
    public function actionUploadauctiondata(){
        $message = "";
        if(isset($_FILES['auction_data']['tmp_name']) && $_FILES['auction_data']['tmp_name'] != ""){
            $str = date('Y_m_d_His');
            move_uploaded_file($_FILES['auction_data']['tmp_name'],Yii::$app->basePath.DIRECTORY_SEPARATOR."auc-data".DIRECTORY_SEPARATOR.$str);
            $message = "Files Uploaded successfully";
        }
        return $this->render("upload",['message'=>$message]);
    }
    /**
     * Lists all Realm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RealmSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionBase(){
        $id = Yii::$app->request->get("id");
        $factionid = Yii::$app->request->get("faction");
        $faction = Factions::findOne(['id'=>$factionid]);
        $realm = Realm::findOne(['id'=>$id]);
        if($realm == NULL){
            return "";
        }
        \Yii::$app->session->set("realm",$id);
        if($faction != null){
            \Yii::$app->session->set("faction",$factionid);
        }
        return $this->render("base",['realm'=>$realm,'selected_faction'=>$faction]);
    }
    /**
     * Displays a single Realm model.
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
     * Creates a new Realm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Realm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Realm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Realm model.
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
     * Finds the Realm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Realm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Realm::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
