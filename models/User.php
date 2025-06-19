<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $role
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $phone
 * @property string $first_name
 * @property string $last_name
 * @property string|null $middle_name
 * @property string|null $profile_photo
 * @property string|null $birth_date
 * @property string|null $created_at
 *
 * @property Appointment[] $appointments
 * @property Appointment[] $appointments0
 * @property MasterSchedule[] $masterSchedules
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $confirm_password;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['login', 'password', 'phone', 'first_name'], 'required'],

            [['role', 'description'], 'string'],
            // [['birth_date', 'created_at'], 'safe'],
            // [['birth_date'], 'required'],

            [['birth_date'], 'date', 'format' => 'php:Y-m-d'],            
            
            [['login'], 'match', 'pattern' => '/^[a-z]{4,}$/i', 'message' => 'Логин должен содержать не менее 5 символов, латиницей'],
            [['password'], 'match', 'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', 'message' => 'Минимум 8 символов: Aa, цифры, !@#$%'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            [['confirm_password'], 'required'],

            [['first_name', 'last_name', 'middle_name'], 'match', 'pattern' => '/^[А-Яа-яЁё\s\-]+$/u', 'message' => 'Только русские буквы'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'min' => 2],
            // [['login'], 'unique', 'targetClass' => User::class, 'message' => 'Этот логин уже занят'],

            [['login', 'first_name', 'last_name', 'middle_name'], 'string', 'max' => 50],
            [['password', 'profile_photo'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 20],
            // [['phone'], 'match', 'pattern' => '/^\+7\s?\(?\d{3}\)?\s?\d{3}-?\d{2}-?\d{2}$/', 'message' => 'Телефон должен быть в формате +7 (999) 999-99-99'],
            
            [['login'], 'required'],
            [['login'], 'unique'],
            [['email'], 'email'],
            [['email'], 'unique'],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'role' => 'Роль',
            'login' => 'Логин',
            'password' => 'Пароль',
            'confirm_password' => 'Повторите пароль',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'middle_name' => 'Отчество',
            'description' => 'О себе',
            'profile_photo' => 'Фото профиля',
            'birth_date' => 'Дата рождения',
            'created_at' => 'Дата регистрации',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }


    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }

public function validatePassword($password)
{
    try {
        return Yii::$app->security->validatePassword($password, $this->password);
    } catch (\yii\base\InvalidArgumentException $e) {
        return $this->password === $password;
    }
}

    
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return ;
    }

    public function validateAuthKey($authKey)
    { 
        return;
    }

        public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Хэшируем пароль, если он был изменён
            if ($this->isAttributeChanged('password')) {
                $this->password = Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[Appointments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::class, ['client_id' => 'id']);
    }

    /**
     * Gets query for [[Appointments0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments0()
    {
        return $this->hasMany(Appointment::class, ['master_id' => 'id']);
    }

    /**
     * Gets query for [[MasterSchedules]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMasterSchedules()
    {
        return $this->hasMany(MasterSchedule::class, ['master_id' => 'id']);
    }

    public function getFullName()
{
    return trim($this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name);
}

public function getZapis()
{
    return $this->hasMany(Zapis::className(), ['master_id' => 'id']);
}

    public function getWorkingDays()
{
    return $this->hasOne(MasterWorkingDays::className(), ['master_id' => 'id']);
}

public function getBreaks()
{
    return $this->hasMany(MasterNotWorking::className(), ['master_id' => 'id'])
        ->with('timeSlot'); 
}


}
