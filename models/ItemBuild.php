<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "item_build".
 *
 * @property int $id
 * @property int $result_itemid
 * @property int $required_itemid
 * @property int $quantity
 * @property string $source_type
 * @property int $source_id
 */
class ItemBuild extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_build';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['result_itemid', 'required_itemid', 'quantity', 'source_id'], 'integer'],
            [['source_type'], 'string', 'max' => 100],
        ];
    }

    public function getResult(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'result_itemid']);
    }
    public function getRequireditem(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'required_itemid']);
    }
    private $_source = null;
    public function getSource(){
        if($this->_source == NULL){
            if($this->source_type == "vendor")
                $this->_source = CreatureTemplate::findOne(['entry'=>$this->source_id]);
            else {
                $this->_source = SpellMaster::findOne(['id'=>$this->source_id]);
            }
        }
        return $this->_source;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'result_itemid' => 'Result Itemid',
            'required_itemid' => 'Required Itemid',
            'quantity' => 'Quantity',
            'source_type' => 'Source Type',
            'source_id' => 'Source ID',
        ];
    }
}
