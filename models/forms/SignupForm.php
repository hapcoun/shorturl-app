<?php
namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $email;
    public $username;
    public $password;
    public $confirm_password;

    public function rules()
    {
        return [
            [['username', 'password', 'email', 'confirm_password'], 'required'],
            ['username', 'string', 'min' => 3, 'max' => 255],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This login is already taken.'],
            ['password', 'string', 'min' => 6],
            ['email', 'email'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'The passwords do not match.'],
        ];
    }

    public function signup(): ?User {
        if ($this->validate()) {
            $user = new User();
            $user->email = $this->email;
            $user->username = $this->username;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->auth_key = Yii::$app->security->generateRandomString();
            return $user->save() ? $user : null;
        }

        return null;
    }
}