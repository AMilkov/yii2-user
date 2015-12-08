<?php

namespace nkostadinov\user\models\forms;

use nkostadinov\user\helpers\Http;
use nkostadinov\user\models\User;
use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'uniqueEmail'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->user->minPasswordLength],
        ];

        if(\Yii::$app->user->requireUsername === true) {
            $rules[] = ['username', 'required'];
            $rules[] =  ['username', 'string', 'min' => 2, 'max' => 255];
            $rules[] =  ['username', 'filter', 'filter' => 'trim'];
            //['username', 'unique', 'targetClass' => 'nkostadinov\user\models\User', 'message' => 'This username has already been taken.'],
        }

        return $rules;
    }

    public function uniqueEmail($attribute)
    {
        $user = User::findByEmail($this->$attribute);
        if ($user && $user->password_hash) {
            $this->addError($attribute, 'This email address has already been taken.');
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = User::findByEmail($this->email);
            if (!$user) {
                $user = Yii::createObject([
                    'class' => Yii::$app->user->identityClass,
                    'scenario' => 'register',
                ]);
                $user->email = $this->email;
                $user->register_ip = Http::getUserIP();
            }
            $user->setPassword($this->password);

            return Yii::$app->user->register($user);
        }

        return false;
    }
}
