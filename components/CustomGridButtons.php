<?php
/**
 * Created by PhpStorm.
 * User: nikhil
 * Date: 28-Apr-19
 * Time: 22:21
 */

namespace app\Components;


use yii\bootstrap4\Html;
use yii\grid\ActionColumn;

class CustomGridButtons extends ActionColumn
{
    public function init()
    {
        // do not call parent is then the glyphicons button would be added
        $this->initBs4Icons('view', 'fa-search-plus');
        $this->initBs4Icons('update', 'fa-edit');
        $this->initBs4Icons('delete', 'fa-trash', [
            'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }

    protected function initBs4Icons($name, $iconName, $options = [])
    {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $options) {
                $title = \Yii::t('yii', ucfirst($name));
                $options = array_merge([
                    'title' => $title,
                    'aria-label' => $title,
                    'data-pjax' => '0',
                ], $options, $this->buttonOptions);
                $icon = Html::tag('i', '', ['class' => "fa $iconName", 'aria-hidden' => true]);
                return Html::a($icon, $url, $options);
            };
        }
    }
}