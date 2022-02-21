<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "creature_template".
 *
 * @property int $entry
 * @property int $difficulty_entry_1
 * @property int $difficulty_entry_2
 * @property int $difficulty_entry_3
 * @property int $KillCredit1
 * @property int $KillCredit2
 * @property int $modelid1
 * @property int $modelid2
 * @property int $modelid3
 * @property int $modelid4
 * @property string $name
 * @property string $subname
 * @property string $IconName
 * @property int $gossip_menu_id
 * @property int $minlevel
 * @property int $maxlevel
 * @property int $exp
 * @property int $faction
 * @property int $npcflag
 * @property double $speed_walk Result of 2.5/2.5, most common value
 * @property double $speed_run Result of 8.0/7.0, most common value
 * @property double $scale
 * @property int $rank
 * @property int $dmgschool
 * @property int $BaseAttackTime
 * @property int $RangeAttackTime
 * @property double $BaseVariance
 * @property double $RangeVariance
 * @property int $unit_class
 * @property int $unit_flags
 * @property int $unit_flags2
 * @property int $dynamicflags
 * @property int $family
 * @property int $trainer_type
 * @property int $trainer_spell
 * @property int $trainer_class
 * @property int $trainer_race
 * @property int $type
 * @property int $type_flags
 * @property int $lootid
 * @property int $pickpocketloot
 * @property int $skinloot
 * @property int $resistance1
 * @property int $resistance2
 * @property int $resistance3
 * @property int $resistance4
 * @property int $resistance5
 * @property int $resistance6
 * @property int $spell1
 * @property int $spell2
 * @property int $spell3
 * @property int $spell4
 * @property int $spell5
 * @property int $spell6
 * @property int $spell7
 * @property int $spell8
 * @property int $PetSpellDataId
 * @property int $VehicleId
 * @property int $mingold
 * @property int $maxgold
 * @property string $AIName
 * @property int $MovementType
 * @property int $InhabitType
 * @property double $HoverHeight
 * @property double $HealthModifier
 * @property double $ManaModifier
 * @property double $ArmorModifier
 * @property double $DamageModifier
 * @property double $ExperienceModifier
 * @property int $RacialLeader
 * @property int $movementId
 * @property int $RegenHealth
 * @property int $mechanic_immune_mask
 * @property int $flags_extra
 * @property string $ScriptName
 * @property int $VerifiedBuild
 */
class CreatureTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'creature_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entry'], 'required'],
            [['entry', 'difficulty_entry_1', 'difficulty_entry_2', 'difficulty_entry_3', 'KillCredit1', 'KillCredit2', 'modelid1', 'modelid2', 'modelid3', 'modelid4', 'gossip_menu_id', 'exp', 'faction', 'npcflag', 'BaseAttackTime', 'RangeAttackTime', 'unit_flags', 'unit_flags2', 'dynamicflags', 'trainer_spell', 'type_flags', 'lootid', 'pickpocketloot', 'skinloot', 'resistance1', 'resistance2', 'resistance3', 'resistance4', 'resistance5', 'resistance6', 'spell1', 'spell2', 'spell3', 'spell4', 'spell5', 'spell6', 'spell7', 'spell8', 'PetSpellDataId', 'VehicleId', 'mingold', 'maxgold', 'movementId', 'mechanic_immune_mask', 'flags_extra', 'VerifiedBuild'], 'integer'],
            [['speed_walk', 'speed_run', 'scale', 'BaseVariance', 'RangeVariance', 'HoverHeight', 'HealthModifier', 'ManaModifier', 'ArmorModifier', 'DamageModifier', 'ExperienceModifier'], 'number'],
            [['name', 'subname', 'IconName'], 'string', 'max' => 100],
            [['minlevel', 'maxlevel', 'rank', 'unit_class', 'trainer_class', 'trainer_race', 'type', 'MovementType', 'InhabitType', 'RacialLeader', 'RegenHealth'], 'string', 'max' => 3],
            [['dmgschool', 'family', 'trainer_type'], 'string', 'max' => 4],
            [['AIName', 'ScriptName'], 'string', 'max' => 64],
            [['entry'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entry' => 'Entry',
            'difficulty_entry_1' => 'Difficulty Entry 1',
            'difficulty_entry_2' => 'Difficulty Entry 2',
            'difficulty_entry_3' => 'Difficulty Entry 3',
            'KillCredit1' => 'Kill Credit1',
            'KillCredit2' => 'Kill Credit2',
            'modelid1' => 'Modelid1',
            'modelid2' => 'Modelid2',
            'modelid3' => 'Modelid3',
            'modelid4' => 'Modelid4',
            'name' => 'Name',
            'subname' => 'Subname',
            'IconName' => 'Icon Name',
            'gossip_menu_id' => 'Gossip Menu ID',
            'minlevel' => 'Minlevel',
            'maxlevel' => 'Maxlevel',
            'exp' => 'Exp',
            'faction' => 'Faction',
            'npcflag' => 'Npcflag',
            'speed_walk' => 'Speed Walk',
            'speed_run' => 'Speed Run',
            'scale' => 'Scale',
            'rank' => 'Rank',
            'dmgschool' => 'Dmgschool',
            'BaseAttackTime' => 'Base Attack Time',
            'RangeAttackTime' => 'Range Attack Time',
            'BaseVariance' => 'Base Variance',
            'RangeVariance' => 'Range Variance',
            'unit_class' => 'Unit Class',
            'unit_flags' => 'Unit Flags',
            'unit_flags2' => 'Unit Flags2',
            'dynamicflags' => 'Dynamicflags',
            'family' => 'Family',
            'trainer_type' => 'Trainer Type',
            'trainer_spell' => 'Trainer Spell',
            'trainer_class' => 'Trainer Class',
            'trainer_race' => 'Trainer Race',
            'type' => 'Type',
            'type_flags' => 'Type Flags',
            'lootid' => 'Lootid',
            'pickpocketloot' => 'Pickpocketloot',
            'skinloot' => 'Skinloot',
            'resistance1' => 'Resistance1',
            'resistance2' => 'Resistance2',
            'resistance3' => 'Resistance3',
            'resistance4' => 'Resistance4',
            'resistance5' => 'Resistance5',
            'resistance6' => 'Resistance6',
            'spell1' => 'Spell1',
            'spell2' => 'Spell2',
            'spell3' => 'Spell3',
            'spell4' => 'Spell4',
            'spell5' => 'Spell5',
            'spell6' => 'Spell6',
            'spell7' => 'Spell7',
            'spell8' => 'Spell8',
            'PetSpellDataId' => 'Pet Spell Data ID',
            'VehicleId' => 'Vehicle ID',
            'mingold' => 'Mingold',
            'maxgold' => 'Maxgold',
            'AIName' => 'Ainame',
            'MovementType' => 'Movement Type',
            'InhabitType' => 'Inhabit Type',
            'HoverHeight' => 'Hover Height',
            'HealthModifier' => 'Health Modifier',
            'ManaModifier' => 'Mana Modifier',
            'ArmorModifier' => 'Armor Modifier',
            'DamageModifier' => 'Damage Modifier',
            'ExperienceModifier' => 'Experience Modifier',
            'RacialLeader' => 'Racial Leader',
            'movementId' => 'Movement ID',
            'RegenHealth' => 'Regen Health',
            'mechanic_immune_mask' => 'Mechanic Immune Mask',
            'flags_extra' => 'Flags Extra',
            'ScriptName' => 'Script Name',
            'VerifiedBuild' => 'Verified Build',
        ];
    }
}
