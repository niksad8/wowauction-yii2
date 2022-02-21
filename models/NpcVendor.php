<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "npc_vendor".
 *
 * @property int $entry
 * @property int $slot
 * @property int $item
 * @property int $maxcount
 * @property int $incrtime
 * @property int $ExtendedCost
 * @property int $VerifiedBuild
 */
class NpcVendor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'npc_vendor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry', 'item', 'ExtendedCost'], 'required'],
            [['entry', 'slot', 'item', 'incrtime', 'ExtendedCost', 'VerifiedBuild'], 'integer'],
            [['maxcount'], 'string', 'max' => 3],
            [['entry', 'item', 'ExtendedCost'], 'unique', 'targetAttribute' => ['entry', 'item', 'ExtendedCost']],
        ];
    }
    public function getCreature(){
        return $this->hasOne(CreatureTemplate::class,['entry'=>'entry']);
    }
    public function getItemr(){
        return $this->hasOne(ItemTemplate::class,['entry'=>'item']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entry' => 'Entry',
            'slot' => 'Slot',
            'item' => 'Item',
            'maxcount' => 'Maxcount',
            'incrtime' => 'Incrtime',
            'ExtendedCost' => 'Extended Cost',
            'VerifiedBuild' => 'Verified Build',
        ];
    }
}
