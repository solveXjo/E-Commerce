<?php

namespace app\models;

use Yii;
use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $password;
    public $confirmPassword;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'confirmPassword'], 'required'],
            ['username', 'string', 'min' => 2, 'max' => 16],
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
            ['confirmPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs up a user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);

            $user->generateAuthKey();
            return $user->save() ? $user : null;
        }
        return null;
    }
}
