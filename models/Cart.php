<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "Cart".
 *
 * @property int $CartID
 * @property int $UserID
 * @property string|null $CreatedAt
 * @property string|null $Status
 *
 * @property CartItem[] $cartItems
 * @property User $user
 */
class Cart extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const STATUS_OPEN = 'open';
    const STATUS_CHECKED_OUT = 'checked_out';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Status'], 'default', 'value' => 'open'],
            [['UserID'], 'required'],
            [['UserID'], 'integer'],
            [['CreatedAt'], 'safe'],
            [['Status'], 'string'],
            ['Status', 'in', 'range' => array_keys(self::optsStatus())],
            [['UserID'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['UserID' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'CartID' => 'Cart ID',
            'UserID' => 'User ID',
            'CreatedAt' => 'Created At',
            'Status' => 'Status',
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCartItems()
    {
        return $this->hasMany(CartItem::class, ['CartID' => 'CartID']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'UserID']);
    }


    /**
     * column Status ENUM value labels
     * @return string[]
     */
    public static function optsStatus()
    {
        return [
            self::STATUS_OPEN => 'open',
            self::STATUS_CHECKED_OUT => 'checked_out',
        ];
    }

    /**
     * @return string
     */
    public function displayStatus()
    {
        return self::optsStatus()[$this->Status];
    }

    /**
     * @return bool
     */
    public function isStatusOpen()
    {
        return $this->Status === self::STATUS_OPEN;
    }

    public function setStatusToOpen()
    {
        $this->Status = self::STATUS_OPEN;
    }

    /**
     * @return bool
     */
    public function isStatusCheckedout()
    {
        return $this->Status === self::STATUS_CHECKED_OUT;
    }

    public function setStatusToCheckedout()
    {
        $this->Status = self::STATUS_CHECKED_OUT;
    }
}
