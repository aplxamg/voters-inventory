<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $user_type
 * @property string $username
 * @property string $password
 * @property string $email_address
 * @property string $status
 * @property string $ins_time
 * @property string $up_time
 *
 * @property UserInfo[] $userInfos
 */
class Users extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_type', 'username', 'password', 'status', 'ins_time', 'up_time'], 'required'],
            [['id'], 'integer'],
            [['user_type', 'status'], 'string'],
            [['ins_time', 'up_time'], 'safe'],
            [['username'], 'string', 'max' => 10],
            [['password', 'email_address'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_type' => 'User Type',
            'username' => 'Username',
            'password' => 'Password',
            'email_address' => 'Email Address',
            'status' => 'Status',
            'ins_time' => 'Ins Time',
            'up_time' => 'Up Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserInfos()
    {
        return $this->hasMany(UserInfo::className(), ['user_id' => 'id']);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    /******************************** Identity Interface ********************************/
    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /******************************** Custom Functions ********************************/

    /**
        @author Anecita M. Gabisan
        @created 2016-06-18
        findByUsername()
        @param  [string]    [username]
    **/
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
        @author Anecita M. Gabisan
        @created 2016-06-18
        validatePassword()
        @param  [string]    [password] - password inputted by the user
        @param  [string]    [hash_password] - hash password fetch from the database
    **/
    public static function validatePassword($password, $hash_password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $hash_password);
    }

    public function saveAccount($accountModel,$value, $id=null){
        if(empty($id)) {
            $accountModel->status    = 'active';
            $accountModel->ins_time  = Yii::$app->formatter->asDatetime('now');
        } else {
            $params = ['id' => $id, 'status' => 'active'];
            $record = self::find()->where($params)->one();
            if(!empty($record)) {
                $accountModel = $record;
            } else {
                return false;
            }
        }

        $accountModel->user_type = $value['user_type'];
        $accountModel->username  = strtolower(trim($value['username']));
        if(isset($value['password'])) {
            $accountModel->password  = Yii::$app->getSecurity()->generatePasswordHash($value['password']);
        }
        $accountModel->up_time   = Yii::$app->formatter->asDatetime('now');

        if($accountModel->save()) {
            return $accountModel->id;
        }
        return false;
    }
}
