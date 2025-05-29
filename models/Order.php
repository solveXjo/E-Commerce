<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property string $order_number
 * @property decimal $total_amount
 * @property string $status
 * @property string $shipping_address
 * @property string $billing_address
 * @property string $payment_method
 * @property string $payment_status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property OrderItem[] $orderItems
 */
class Order extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_FAILED = 'failed';
    const PAYMENT_REFUNDED = 'refunded';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'total_amount', 'shipping_address'], 'required'],
            [['user_id'], 'integer'],
            [['total_amount'], 'number', 'min' => 0],
            [['status'], 'string'],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_PROCESSING,
                self::STATUS_SHIPPED,
                self::STATUS_DELIVERED,
                self::STATUS_CANCELLED
            ]],
            [['payment_status'], 'in', 'range' => [
                self::PAYMENT_PENDING,
                self::PAYMENT_PAID,
                self::PAYMENT_FAILED,
                self::PAYMENT_REFUNDED
            ]],
            [['shipping_address', 'billing_address'], 'string'],
            [['payment_method'], 'string', 'max' => 50],
            [['order_number'], 'string', 'max' => 100],
            [['order_number'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_number' => 'Order Number',
            'total_amount' => 'Total Amount',
            'status' => 'Status',
            'shipping_address' => 'Shipping Address',
            'billing_address' => 'Billing Address',
            'payment_method' => 'Payment Method',
            'payment_status' => 'Payment Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for user relation.
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for order items relation.
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->order_number = $this->generateOrderNumber();
                $this->status = self::STATUS_PENDING;
                $this->payment_status = self::PAYMENT_PENDING;
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }

    /**
     * Generate unique order number
     */
    protected function generateOrderNumber()
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Yii::$app->security->generateRandomString(6));
        } while (self::find()->where(['order_number' => $orderNumber])->exists());

        return $orderNumber;
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_SHIPPED => 'Shipped',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    /**
     * Get payment status options
     */
    public static function getPaymentStatusOptions()
    {
        return [
            self::PAYMENT_PENDING => 'Pending',
            self::PAYMENT_PAID => 'Paid',
            self::PAYMENT_FAILED => 'Failed',
            self::PAYMENT_REFUNDED => 'Refunded',
        ];
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'warning';
            case self::STATUS_PROCESSING:
                return 'info';
            case self::STATUS_SHIPPED:
                return 'primary';
            case self::STATUS_DELIVERED:
                return 'success';
            case self::STATUS_CANCELLED:
                return 'danger';
            default:
                return 'secondary';
        }
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeClass()
    {
        switch ($this->payment_status) {
            case self::PAYMENT_PENDING:
                return 'warning';
            case self::PAYMENT_PAID:
                return 'success';
            case self::PAYMENT_FAILED:
                return 'danger';
            case self::PAYMENT_REFUNDED:
                return 'info';
            default:
                return 'secondary';
        }
    }
}
