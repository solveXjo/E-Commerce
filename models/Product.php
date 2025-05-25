<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "products".
 *
 * @property int $ProductID
 * @property string $Name
 * @property float $Price
 * @property string|null $Description
 * @property string|null $Category
 * @property int|null $StockQuantity
 * @property string|null $ImageURL
 * @property string|null $CreatedAt
 */
class Product extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'Products';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Description', 'Category', 'ImageURL'], 'default', 'value' => null],
            [['StockQuantity'], 'default', 'value' => 0],
            [['Name', 'Price'], 'required'],
            [['Price'], 'number'],
            [['Description'], 'string'],
            [['StockQuantity'], 'integer'],
            [['CreatedAt'], 'safe'],
            [['Name', 'ImageURL'], 'string', 'max' => 255],
            [['Category'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ProductID' => 'Product ID',
            'Name' => 'Name',
            'Price' => 'Price',
            'Description' => 'Description',
            'Category' => 'Category',
            'StockQuantity' => 'Stock Quantity',
            'ImageURL' => 'Image Url',
            'CreatedAt' => 'Created At',
        ];
    }
}
