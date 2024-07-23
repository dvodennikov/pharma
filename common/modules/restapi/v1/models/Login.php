<?php

namespace common\modules\restapi\v1\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Login form
 */
class Login extends Model
{
    public $username;
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['username', 'password'], 'required'],
            [['password'], 'required'],
            [
				'email', 'required', 
				'message' => 'Either username or email is required',
				'when' => function($model) {
					return empty($model->username);
				}
			],
            [
				'username', 'required', 
				'message' => 'Either username or email is required',
				'when' => function($model) {
					return empty($model->email);
				}
			],
			['email', 'default', 'value' => null],
            ['email', 'email'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = isset($this->email)?$this->getUserByEmail():User::findByUsername($this->username);
            if (!$user || 
				empty($this->password) || 
				!$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username/email or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username/email and password.
     *
     * @return array containing status whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
			$user = isset($this->email)?$this->getUserByEmail():User::findByUsername($this->username);
            
            //if (!isset($user->access_token)) {
            if ($user->access_token === null) {
				$user->generateAccessToken();
				$user->save();
			}
            
            if (Yii::$app->user->login($user, 3600 * 24 * 30)) {
				return [
					'success'  => true,
					'username' => Yii::$app->user->identity->username,
					'token'    => $user->access_token,
					'status'   => 200,
				];
			} else {
				Yii::$app->response->statusCode = 422;

				return [
					'success' => false,
					'errors'  => $this->getErrors(),
					'status'  => 422,
				];
			}
        }
        
		Yii::$app->response->statusCode = 422;
        
        //return false;
        
        //if (!$this->validate() {
	        return [
				'success' => false,
				'errors'  => $this->getErrors(),
				'status'  => 422,
	        ];
		//}
    }
    
    /*
     * Logout user
     * @return array containing status whether the user is logout successfully
     */
    public function logout()
    {
		$token = Yii::$app->request->post('token', null);
		$user  = User::findIdentityByAccessToken($token);
		
		if ($user) {
			$user->removeAccessToken();
			$user->save();
			
			return ['status' => 'success'];
		}
		
		return ['status' => 'failed'];
	}

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUserByEmail()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
