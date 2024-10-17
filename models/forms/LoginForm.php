<?php
namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public bool $rememberMe = true;

    private $_user = false;

    public function rules(): array {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login(): bool {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }

        return false;
    }

    public function getUser(): ?User {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}