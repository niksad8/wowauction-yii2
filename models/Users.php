<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\base\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $server_id
 * @property int $realm_id
 * @property string $last_login
 * @property string $created_at
 * @property int $enabled
 * @property int $is_admin
 * @property string $email_address
 * @method Users $findByUsername
 * @method $setPassword
 * @method $checkPassword
 *
 */
class Users extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    private $salt = "WOWAUCTIONWOWAUCTION1234567890";
    public function getDailySubStatus($realm,$faction){
        $sub = EmailSubscriptions::findOne(['realmid'=>Yii::$app->user->getId(),'realm_id'=>$realm,'faction'=>$faction]);
        if($sub == NULL)
            return false;
        else
            return true;
    }
    public function setPassword($password){
        $this->password = crypt($password,$this->salt);
    }
    public function checkPassword($password){
        return crypt($password,$this->salt)== $this->password;
    }
    public function isAdmin(){
        return $this->is_admin;
    }
    public function validatePassword($password){
        if($this->password == crypt($password,$this->salt))
            return true;
        else
            return false;
    }
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function getAuthKey()
    {
        return $this->token;
    }
    public function generateAuthKey()
    {
        $this->token = Security::generateRandomKey();
    }
    public function getSetting($name){
        $settings = $this->settings;
        for($i=0; $i < count($settings); $i++){
            if($settings[$i]->option_name == $name){
                return $settings[$i]->value;
            }
        }
        return NULL;
    }
    public function getSettings(){
        return $this->hasMany(UserSettings::class,['user_id'=>'id']);
    }
    public function getAlerts(){
        return $this->hasMany(UserItemAlerts::class,['user_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['server_id', 'enabled','realm_id'], 'integer'],
            [['last_login', 'created_at'], 'safe'],
            [['username'], 'string', 'max' => 145],
            [['password'], 'string', 'max' => 80],
            [['email_address'], 'string', 'max' => 85],
        ];
    }
    public function server(){
        $this->hasOne(Servers::class,['server_id'=>'id']);
    }
    public function realm(){
        $this->hasOne(Realm::class,['realm_id'=>'id']);
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'server_id' => 'Server ID',
            'realm_id' => 'Realm ID',
            'last_login' => 'Last Login',
            'created_at' => 'Creates At',
            'enabled' => 'Enabled',
            'email_address' => 'Email Address',
        ];
    }
}
