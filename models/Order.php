<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $billing_first_name
 * @property string $billing_last_name
 * @property string $billing_email
 * @property string $billing_phone
 * @property string $billing_address
 * @property string $billing_city
 * @property string $billing_postal_code
 * @property string|null $order_notes
 * @property int $user_id
 * @property float $total_amount
 * @property string $status
 * @property string $created_at
 */
class Order extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%orders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone', 'billing_address', 'billing_city', 'billing_postal_code', 'user_id', 'total_amount'], 'required'],
            [['order_notes'], 'string'],
            [['total_amount'], 'number'],
            [['created_at'], 'safe'],
            [['user_id'], 'integer'],
            [['billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone', 'billing_address', 'billing_city', 'billing_postal_code', 'status'], 'string', 'max' => 255],
            [['billing_email'], 'email'],
        ];
    }

    /**
     * Links the order to its user.
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
