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
 * @property string|null $UpdatedAt
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
    public function getCartItems()
    {
        return $this->hasMany(CartItem::class, ['CartID' => 'CartID']);
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

    /**
     * Calculate the subtotal of all items in the cart
     *
     * @return float
     */
    public function getSubtotal()
    {
        $subtotal = 0;

        foreach ($this->cartItems as $item) {
            $subtotal += ($item->Price * $item->Quantity);
        }

        return $subtotal;
    }

    /**
     * Alternative method using database aggregation (more efficient for large carts)
     *
     * @return float
     */
    public function getSubtotalFromDb()
    {
        $result = CartItem::find()
            ->where(['CartID' => $this->CartID])
            ->select('SUM(Price * Quantity) as subtotal')
            ->scalar();

        return $result ? (float)$result : 0.00;
    }

    /**
     * Get total number of items in cart
     *
     * @return int
     */
    public function getTotalItems()
    {
        $total = 0;

        foreach ($this->cartItems as $item) {
            $total += $item->Quantity;
        }

        return $total;
    }

    /**
     * Alternative method using database aggregation
     *
     * @return int
     */
    public function getTotalItemsFromDb()
    {
        $result = CartItem::find()
            ->where(['CartID' => $this->CartID])
            ->select('SUM(Quantity) as total')
            ->scalar();

        return $result ? (int)$result : 0;
    }

    /**
     * Get total with tax
     *
     * @param float $taxRate Tax rate (e.g., 0.1 for 10%)
     * @return float
     */
    public function getTotalWithTax($taxRate = 0.1)
    {
        $subtotal = $this->getSubtotal();
        return $subtotal + ($subtotal * $taxRate);
    }

    /**
     * Get tax amount
     *
     * @param float $taxRate Tax rate (e.g., 0.1 for 10%)
     * @return float
     */
    public function getTaxAmount($taxRate = 0.1)
    {
        return $this->getSubtotal() * $taxRate;
    }

    /**
     * Check if cart is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->cartItems) === 0;
    }

    /**
     * Clear all items from cart
     *
     * @return bool
     */
    public function clearCart()
    {
        try {
            CartItem::deleteAll(['CartID' => $this->CartID]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get cart summary data
     *
     * @param float $taxRate
     * @return array
     */
    public function getSummary($taxRate = 0.1)
    {
        $subtotal = $this->getSubtotal();
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'taxRate' => $taxRate,
            'total' => $total,
            'itemCount' => $this->getTotalItems(),
            'isEmpty' => $this->isEmpty()
        ];
    }


    public static function createNewCart($userId)
    {
        $newCart = new self();
        $newCart->UserID = $userId;
        $newCart->Status = 'open';
        $newCart->CreatedAt = date('Y-m-d H:i:s');

        if ($newCart->save()) {
            return $newCart;
        }

        return null;
    }

    /**
     * Before save event
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->CreatedAt = date('Y-m-d H:i:s');
            }
            $this->UpdatedAt = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}
