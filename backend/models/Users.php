<?php

namespace backend\models;

use frontend\models\Members;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "nfojm_users".
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property int $block
 * @property int|null $sendEmail
 * @property string|null $registerDate
 * @property string|null $lastvisitDate
 * @property string $activation
 * @property string $params
 * @property string|null $lastResetTime Date of last password reset
 * @property int $resetCount Count of password resets since lastResetTime
 * @property string $otpKey Two factor authentication encrypted keys
 * @property string $otep One time emergency passwords
 * @property int $requireReset Require user to reset password on next login
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $create_at
 * @property string|null $updated_at
 * @property int $status
 * @property int $staff_login
 */
class Users extends \yii\db\ActiveRecord
{
    public $role;
    public $created_at;
    public $password_new;
    public $repassword;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'nfojm_users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['block', 'sendEmail', 'resetCount', 'requireReset', 'status', 'staff_login'], 'integer'],
            [['registerDate', 'lastvisitDate', 'lastResetTime', 'create_at', 'updated_at'], 'safe'],
            [['auth_key', 'password_reset_token', 'verification_token', 'status'], 'required'],
            [['params'], 'string'],
            [['name','role'], 'string', 'max' => 400],
            [['username'], 'string', 'max' => 150],
            [['email', 'password', 'activation'], 'string', 'max' => 100],
            [['otpKey', 'otep'], 'string', 'max' => 1000],
            [['auth_key', 'password_reset_token', 'verification_token'], 'string', 'max' => 255],

            [['password_new','repassword'], 'string', 'min' => 4, 'max' => 30],
            [['username',], 'unique'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            ['repassword', 'compare', 'compareAttribute' => 'password_new'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'block' => 'Block',
            'sendEmail' => 'Send Email',
            'registerDate' => 'Register Date',
            'lastvisitDate' => 'Lastvisit Date',
            'activation' => 'Activation',
            'params' => 'Params',
            'lastResetTime' => 'Last Reset Time',
            'resetCount' => 'Reset Count',
            'otpKey' => 'Otp Key',
            'otep' => 'Otep',
            'requireReset' => 'Require Reset',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'verification_token' => 'Verification Token',
            'create_at' => 'Create At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'staff_login' => 'Staff Login',
        ];
    }


    public function getRole0()
    {
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public static function getRegions()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mobile.zssf.or.tz/api2/public/index.php/regions/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        // curl_close($curl); // Deprecated in PHP 8.5, handles auto-close in PHP 8.0+

        $response=json_decode($response);

        if (empty($response)) {
            return [];
        }

        return ArrayHelper::map($response,'RegionID','RegionName');

       // return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }
    public static function getDistricts()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mobile.zssf.or.tz/api2/public/index.php/districts/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        // curl_close($curl); // Deprecated in PHP 8.5, handles auto-close in PHP 8.0+

        $response=json_decode($response);

        if (empty($response)) {
            return [];
        }

        return ArrayHelper::map($response,'DistrictID','DistrictName');

       // return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }
    public static function getShehias()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mobile.zssf.or.tz/api2/public/index.php/shehias/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        // curl_close($curl); // Deprecated in PHP 8.5, handles auto-close in PHP 8.0+

        $response=json_decode($response);

        if (empty($response)) {
            return [];
        }

        return ArrayHelper::map($response,'ShehiaID','ShehiaName');

       // return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public static function TotalActiveAccounts()
    {

        $model = Users::find()->where(['staff_login'=>1])->count();
        if ($model > 0) {
            return $model;
        } else {
            return 0;
        }
    }


    public static function TotalAllMembers()
    {
        $model = Members::find()->count();
        if ($model > 0) {
            return $model;
        } else {
            return 0;
        }
    }


    public static function getArrayRole()
    {
        // return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'name');

        return ArrayHelper::map(AuthItem::find()->where(['type'=>1])->all(),'name','name');

    }
}
