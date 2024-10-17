<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $auth_key
 * @property string $created_at
 * @property string $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return '{{%users}}';
    }

    public function rules()
    {
        return [
            [['username', 'password', 'auth_key'], 'required'],
            ['username', 'string', 'max' => 255],
            ['username', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return false;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return User::findOne(['username' => $username]);
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
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validatePassword($password): bool {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
