<?php

namespace app\controllers;

use app\models\ItemTemplate;
use app\models\Servers;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\widgets\LinkPager;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
    public function actionWallet(){
        return $this->render("wallet");
    }
    public function actionSearch(){
        $realm_id = Yii::$app->request->post('realm');
        $faction = Yii::$app->request->post('faction');

        if($realm_id == "")
            $realm_id = Yii::$app->session->get('realm');
        else {
            
        }
        if($faction == "")
            $faction = Yii::$app->session->get('faction');
        $query = Yii::$app->request->get("query");
        return $this->render("search",['query'=>$query]);
    }

    public function actionRegister(){
        return $this->render('register');
    }
    public function actionDiscord(){
        return $this->render('discord');
    }

    public function actionSearchonly(){
        $query = Yii::$app->request->get("query");
        $class = Yii::$app->request->get("class");
        $sub_class = Yii::$app->request->get("sub_class");
        $lvl_from = Yii::$app->request->get("lvl_from");
        $lvl_to = Yii::$app->request->get("lvl_to");
        $ilvl_from = Yii::$app->request->get("ilvl_from");
        $ilvl_to = Yii::$app->request->get("ilvl_to");
        $proff = Yii::$app->request->get("profession");
        $page = Yii::$app->request->get("page",1);
        $quality = Yii::$app->request->get("quality");
        $item_query = ItemTemplate::find();
        if($query != ""){
            $item_query->andwhere("name LIKE CONCAT('%',:query,'%')",[':query'=>$query]);
        }
        if($class != ""){
            $item_query->andwhere(['=','class',$class]);
        }
        if($sub_class != ""){
            $item_query->andwhere(['=','subclass',$sub_class]);
        }
        if($lvl_from != ""){
            $item_query->andwhere(['>=','RequiredLevel',$lvl_from]);
        }
        if($lvl_to != ""){
            $item_query->andwhere(['<=','RequiredLevel',$lvl_to]);
        }
        if($ilvl_from != ""){
            $item_query->andwhere(['>=','ItemLevel',$ilvl_from]);
        }
        if($ilvl_to != ""){
            $item_query->andwhere(['<=','ItemLevel',$ilvl_to]);
        }
        if($proff != ""){
            $item_query->andwhere('entry in (select result_item_id from profession_spells where profession_id=:proff)',[':proff'=>$proff]);
        }
        if($quality != ""){
            $item_query->andWhere(['=','Quality',$quality]);
        }

        $count = $items = $item_query->count();
        $pagination = new Pagination(['totalCount' => $count]);
        $pagination->route = 'site/searchonly';
        $items = $item_query->offset($pagination->offset)->limit(100)->all();
        $pagerlinks = LinkPager::widget([
            'pagination'=>$pagination,
            'linkOptions'=>['class'=>'page-link','onclick'=>'return changepage(this)'],
            'pageCssClass'=>'page-item',
            'prevPageCssClass'=>'page-item',
        ]);
        $str = "";
        $item_body = "";
        foreach($items as $item) {
            $price_row = $item->itemprice;

            $row = "<tr><td>".Yii::$app->wowutil->printItem($item->entry)."</td>";
            $row .= "<td>".Yii::$app->wowutil->printCurrency($item->SellPrice)."</td>";
            $row .= "<td>".$item->itemclass->name."</td>";
            $row .= "<td>".$item->itemsubclass->name."</td>";
            $row .= "<td>".$item->RequiredLevel."</td>";
            $row .= "<td>".$item->ItemLevel."</td>";
            if($price_row != null) {
                $row .= "<td>" . $price_row->quantity . "</td>";
                $row .= "<td>" . Yii::$app->wowutil->printCurrency($price_row->cost_price) . "</td>";
                $row .= "<td>" . Yii::$app->wowutil->printCurrency($price_row->bid_median) . "</td>";
                $row .= "<td>" . Yii::$app->wowutil->printCurrency($price_row->buyout_median) . "</td>";
                $row .= "<td>" . Yii::$app->wowutil->printCurrency($price_row->bid_median - $price_row->cost_price) . " / " . Yii::$app->wowutil->printCurrency($price_row->buyout_median - $price_row->cost_price) . "</td>";

            }
            else {
                $row .= "<td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>";
            }
            $row .= "</tr>";
            $item_body .= $row;
        }
        $str .= "<div class='card'>
            <div class='card-header'>
                <h1>Results </h1>
            </div>
            <div class='card-body'>
                <table class='table table-bordered table-striped'>
                    <thead>
                           <tr>
                            <th>Item Name</th>
                            <th>Vendor Price</th>
                            <th>Class</th>
                            <th>Sub Class</th>
                            <th>Required Level</th>
                            <th>Item Level</th>
                            <th>AH Quantity</th>
                            <th>AH Cost Price</th>
                            <th>AH Median Bid</th>
                            <th>AH Median Buyout</th>
                            <th>Profit From Bid / Buyout</th>
                           </tr>                    
                    </thead>     
                    <tbody>
                    ".$item_body."
                    </tbody>
                </table>
            </div>
            <div class='card-footer'>
            ".$pagerlinks."
            </div>
        </div>
        ";
        return $str;
    }
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $site = Yii::$app->session->get("site");
        $realm = Yii::$app->session->get("realm");
        $faction  =Yii::$app->session->get("faction");
        $sites = Servers::find()->all();
        return $this->render('index',array('servers'=>$sites));
    }
    public function actionFaq(){
        return $this->render("faq");
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
