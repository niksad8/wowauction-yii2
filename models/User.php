<?php

namespace app\models;

class User extends \yii\base\BaseObject
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    private $_user = null;
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user =Users::findOne(['id'=>$id]);
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = Users::findOne(['token'=>$token]);
        return $user;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return Users|null
     */
    public static function findByUsername($username)
    {
        $user =Users::findOne(['username'=>$username]);
        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
