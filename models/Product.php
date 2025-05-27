<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\Url;
use yii\helpers\FileHelper;


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
 * public $eventImage;


 */
class Product extends \yii\db\ActiveRecord
{

    public $eventImage;



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
            [['eventImage'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
            'eventImage' => 'Event Image',
        ];
    }

    public function actionUpload($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $model->eventImage = UploadedFile::getInstance($model, 'eventImage');
            if ($model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('upload', ['model' => $model]);
    }
    public function upload()
    {
        if ($this->eventImage) {
            // Create directory if it doesn't exist
            $uploadPath = $this->getUploadPath();
            FileHelper::createDirectory($uploadPath);

            // Generate unique filename
            $filename = uniqid() . '.' . $this->eventImage->extension;
            $filePath = $uploadPath . $filename;

            if ($this->eventImage->saveAs($filePath)) {
                // Save relative path to database
                $this->ImageURL = 'uploads/events/' . $filename;
                return $this->save(false); // Skip validation as we're just updating the image
            }
        }
        return false;
    }

    public function getUploadPath()
    {
        return Yii::getAlias('@webroot/uploads/events/');
    }

    public function getImageUrl()
    {
        return Url::to('@web/uploads/events/' . basename($this->ImageURL));
    }
}
