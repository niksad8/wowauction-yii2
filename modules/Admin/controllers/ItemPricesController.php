<?php

namespace app\modules\Admin\controllers;

use Yii;
use app\models\ItemPrices;
use app\models\ItemPricesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemPricesController implements the CRUD actions for ItemPrices model.
 */
class ItemPricesController extends Controller
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
     * Lists all ItemPrices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemPricesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ItemPrices model.
     * @param integer $itemid
     * @param string $datetime
     * @param integer $realm_id
     * @param integer $faction_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($itemid, $datetime, $realm_id, $faction_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($itemid, $datetime, $realm_id, $faction_id),
        ]);
    }

    /**
     * Creates a new ItemPrices model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ItemPrices();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'itemid' => $model->itemid, 'datetime' => $model->datetime, 'realm_id' => $model->realm_id, 'faction_id' => $model->faction_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ItemPrices model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $itemid
     * @param string $datetime
     * @param integer $realm_id
     * @param integer $faction_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($itemid, $datetime, $realm_id, $faction_id)
    {
        $model = $this->findModel($itemid, $datetime, $realm_id, $faction_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'itemid' => $model->itemid, 'datetime' => $model->datetime, 'realm_id' => $model->realm_id, 'faction_id' => $model->faction_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ItemPrices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $itemid
     * @param string $datetime
     * @param integer $realm_id
     * @param integer $faction_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($itemid, $datetime, $realm_id, $faction_id)
    {
        $this->findModel($itemid, $datetime, $realm_id, $faction_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ItemPrices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $itemid
     * @param string $datetime
     * @param integer $realm_id
     * @param integer $faction_id
     * @return ItemPrices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($itemid, $datetime, $realm_id, $faction_id)
    {
        if (($model = ItemPrices::findOne(['itemid' => $itemid, 'datetime' => $datetime, 'realm_id' => $realm_id, 'faction_id' => $faction_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
