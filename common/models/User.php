<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $access_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    
    const SCENARIO_UPDATE = 'update';
    
    public $password;
    public $password_repeat;
    public $role;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
		return [
			'username'   => Yii::t('app', 'Username'),
			'password'   => Yii::t('app', 'Password'),
			'email'      => Yii::t('app', 'Email'),
			'active'     => Yii::t('app', 'Active'),
			'created_at' => Yii::t('app', 'Created at'),
			'updated_at' => Yii::t('app', 'Updated at'),
		];
	}

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios() 
    {
		return array_merge(parent::scenarios(), [
			self::SCENARIO_UPDATE => ['username', 'email', 'password', 'password_repeat'],
		]);
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['username', 'string', 'length' => [4, 255]],
			['email', 'email'],
			[['username', 'email'], 'unique'],
			[['password', 'password_repeat'], 'string', 'length' => [\Yii::$app->params['user.passwordMinLength'], 255]],
			['password', 'compare', 'compareAttribute' => 'password_repeat'],
            ['role', 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    
    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
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
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    
    /**
     * Generates new acess token
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes access token
     */
    public function removeAccessToken()
    {
        $this->access_token = null;
    }
    
    /**
     * Check if is AccessToken expire
     * @param boolean $clearAccessToken
     * @param int $sessionDuration
     * @return boolean
     */
    public function isAccessTokenExpire($clearAccessToken = false, $sessionDuration = null)
    {
		if (is_null($this->access_token))
			return true;

		if (is_null($sessionDuration)) {
			$sessionDuration = 3600;//sec
			
			if (isset(Yii::$app->params['sessionDuration'])) {
				$sessionDuration = (int)Yii::$app->params['sessionDuration']; 
			}
		}
		
		if (($sessionDuration > 0) && preg_match('/_(\\d+)$/', $this->access_token, $matches)) {
			if ($matches[1] + $sessionDuration < time()) {
				if ($clearAccessToken) {
					$this->removeAccessToken();
					$this->save();
				}
				
				return true;
			}
		}
		
		return false;
	}
    
    /**
     * Generate password hash and auth key for user
     */
    public function afterValidate()
	{
		parent::afterValidate();
		
		if ($this->password) {
			$this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
			$this->auth_key = Yii::$app->security->generateRandomString();
		}
	}
	
	/**
	 * Set role
	 * @param bool $flushRoles flush roles before set role
	 */
	public function setRole($flushRoles = false)
	{
		$auth = \Yii::$app->authManager;
		
		if ($flushRoles)
			$auth->revokeAll($this->id);
			
		$role = $auth->getRole($this->role);
		if (isset($role))
			$auth->assign($role, $this->id);
	}
	
	/**
	 * Get user role
	 * @return yii\rbac\Role
	 */
	public function getRole() 
	{
		$auth = \Yii::$app->authManager;
		
		return array_key_first($auth->getRolesByUser($this->id));
	}
	
	/**
	 * Get user name by id
	 * @param int $id
	 * @return string
	 */
	public static function getUsernameById($id)
	{
		$user = User::findOne(['id' => $id]);
		
		return isset($user->username)?$user->username:\Yii::t('app', 'none');
	}
	
	/**
	 * Check if user has specified permission
	 * @param string $permissionName name of the permission to check
	 * @param array $params additional parameters
	 * @param boolean $allowCaching allow chaching result (default true)
	 * @return boolean
	 */
	public function can($permissionName, $params = [], $allowCaching = true)
    {
		throw new \yii\base\NotSupported('not supported');
		return true;
	}
}
