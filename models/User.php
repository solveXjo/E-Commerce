<?php

namespace app\models;

<<<<<<< HEAD
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user'; // Matches your database table name
    }
=======
class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    private static $users = [
        '100' => [
            'id' => '100',
            'username' => 'admin',
            'password' => 'admin',
            'authKey' => 'test100key',
            'accessToken' => '100-token',
        ],
        '101' => [
            'id' => '101',
            'username' => 'demo',
            'password' => 'demo',
            'authKey' => 'test101key',
            'accessToken' => '101-token',
        ],
    ];

>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
<<<<<<< HEAD
        return static::findOne($id);
=======
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
<<<<<<< HEAD

        return static::findOne(['accessToken' => $token]);
=======
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
<<<<<<< HEAD
        return static::findOne(['username' => $username]);
=======
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
<<<<<<< HEAD
        return $this->getPrimaryKey();
=======
        return $this->id;
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
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
<<<<<<< HEAD
        // Use Yii's security component to validate hashed passwords
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->authKey = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new access token for API authentication
     */
    public function generateAccessToken()
    {
        $this->accessToken = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Before saving, ensure password is hashed and keys are generated if new record
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                // Generate authKey and accessToken only when creating a new user
                $this->generateAuthKey();
                $this->generateAccessToken();
            }
            return true;
        }
        return false;
=======
        return $this->password === $password;
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
    }
}
