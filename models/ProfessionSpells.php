<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profession_spells".
 *
 * @property int $spell_id
 * @property int $profession_id
 * @property int $result_item_id
 */
class ProfessionSpells extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profession_spells';
    }
    public function getExpansion(){
        return $this->hasOne(Expansion::class,['expansion_id'=>'id']);
    }
    public function getSpell(){
        return $this->hasOne(SpellMaster::class,['id'=>'spell_id']);
    }
    public function getItem(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'result_item_id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['spell_id', 'profession_id', 'result_item_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'spell_id' => 'Spell ID',
            'profession_id' => 'Profession ID',
            'result_item_id' => 'Result Item ID',
        ];
    }
}
