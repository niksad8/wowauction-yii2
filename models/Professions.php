<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "professions".
 *
 * @property int $id
 * @property string $name
 * @property string $icon_name
 * @property int $spell_id
 */
class Professions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'professions';
    }
    public function getExpansion(){
        return $this->hasOne(Expansion::class,['id'=>'expansion_id']);
    }
    public function getSpell(){
        return $this->hasOne(SpellMaster::class,['id'=>'spell_id']);
    }
    public function getAHSpells($realm_id = 0, $faction_id=0,$page = 0, $search = ""){
        $page_limit = 100;
        if($realm_id == 0)
            $realm_id = \Yii::$app->session->get('realm');
        if($faction_id == 0)
            $faction_id = \Yii::$app->session->get('faction');
        $realm = Realm::findOne(['id'=>$realm_id]);
        $faction = Factions::findOne(['id'=>$faction_id]);

        if($realm != null && $faction != null) {
            if($search != "")
                return ProfessionSpells::find()->where(['profession_id'=>$this->id ])->andWhere(['expansion_id' =>$realm->expansion_id])->andWhere("result_item_id in (select entry from item_template where `name` like :search)",['search'=>'%'.$search.'%'])->andWhere('result_item_id in (select itemid from item_prices where realm_id=:r and faction_id=:f)',[':r'=>$realm->id,':f'=>$faction->id])->limit($page_limit)->offset($page*$page_limit)->all();
            else
                return ProfessionSpells::find()->where(['profession_id'=>$this->id ])->andWhere(['expansion_id' =>$realm->expansion_id])->andWhere('result_item_id in (select itemid from item_prices where realm_id=:r and faction_id=:f)',[':r'=>$realm->id,':f'=>$faction->id])->limit($page_limit)->offset($page*$page_limit)->all();
        }
        else {
            if($search != "")
                return ProfessionSpells::find()->where(['profession_id' => $this->id])->andWhere("result_item_id in (select entry from item_template where `name` like '%:search%')",['search'=>$search])->limit($page_limit)->offset($page * $page_limit)->all();
            else
                return ProfessionSpells::find()->where(['profession_id' => $this->id])->limit($page_limit)->offset($page * $page_limit)->all();
        }
    }
    public function getSpells($realm_id=0){
        if($realm_id == 0)
            $realm_id = \Yii::$app->session->get('realm');

        $realm = Realm::findOne(['id'=>$realm_id]);
        if($realm != null) {
            return $this->hasMany(ProfessionSpells::class, ['profession_id' => 'id' ])->where(['expansion_id' =>$realm->expansion_id]);
        }
        else
            return $this->hasMany(ProfessionSpells::class, ['profession_id' => 'id']);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'spell_id'], 'integer'],
            [['name'], 'string', 'max' => 150],
            [['icon_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'icon_name' => 'Icon Name',
            'spell_id' => 'Spell ID',
        ];
    }
}
