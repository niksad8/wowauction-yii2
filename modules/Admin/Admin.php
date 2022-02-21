<?php

namespace app\modules\Admin;
use yii\filters\AccessControl;

/**
 * Admin module definition class
 */
class Admin extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\Admin\controllers';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (\Yii::$app->user->identity->isAdmin()) {
                                return true;
                            }
                            return false;
                        }
                    ]
                ],
            ],
        ];
    }
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
