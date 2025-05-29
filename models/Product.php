<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "products".
 *
 * @property int $ProductID
 * @property string $Name
 * @property string|null $Description
 * @property float $Price
 * @property int $StockQuantity
 * @property int|null $Category
 * @property string|null $ImageURL
 * @property string $CreatedAt
 * @property string|null $UpdatedAt
 *
 * @property UploadedFile $imageFile
 */
class Product extends ActiveRecord
{
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Name', 'Price', 'StockQuantity'], 'required'],
            [['Description', 'Category'], 'string'],
            [['Price'], 'number', 'min' => 0],
            [['StockQuantity'], 'integer', 'min' => 0],
            [['CreatedAt', 'UpdatedAt'], 'safe'],
            [['Name'], 'string', 'max' => 255],
            [['ImageURL'], 'string', 'max' => 500],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ProductID' => 'Product ID',
            'Name' => 'Product Name',
            'Description' => 'Description',
            'Price' => 'Price ($)',
            'StockQuantity' => 'Stock Quantity',
            'Category' => 'Category',
            'ImageURL' => 'Image URL',
            'CreatedAt' => 'Created At',
            'UpdatedAt' => 'Updated At',
            'imageFile' => 'Product Image',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->CreatedAt = date('Y-m-d H:i:s');
            } else {
                $this->UpdatedAt = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPrice()
    {
        return '$' . number_format($this->Price, 2);
    }

    /**
     * Get stock status
     */
    public function getStockStatus()
    {
        if ($this->StockQuantity > 10) {
            return 'In Stock';
        } elseif ($this->StockQuantity > 0) {
            return 'Low Stock';
        } else {
            return 'Out of Stock';
        }
    }

    /**
     * Get stock status class for badges
     */
    public function getStockStatusClass()
    {
        if ($this->StockQuantity > 10) {
            return 'success';
        } elseif ($this->StockQuantity > 0) {
            return 'warning';
        } else {
            return 'danger';
        }
    }

    /**
     * Get image URL with fallback
     */
    public function getImageUrl()
    {
        if ($this->ImageURL) {
            return Yii::getAlias('@web/') . $this->ImageURL;
        }
        return null;
    }

    /**
     * Get total inventory value
     */
    public function getTotalValue()
    {
        return $this->Price * $this->StockQuantity;
    }
}
