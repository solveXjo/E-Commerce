<?php

namespace app\controllers;

use app\models\Product;
use app\Models\ProductSearch;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;


/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->username === 'admin';
                        },
                    ],
                    [
                        'allow' => false,
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Lists all Product models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
     * @param int $ProductID Product ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($ProductID)
    {
        return $this->render('view', [
            'model' => $this->findModel($ProductID),
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionUploadImage($id)
    {
        $model = Product::findOne($id);

        if (Yii::$app->request->isPost) {
            $model->eventImage = UploadedFile::getInstance($model, 'eventImage');
            if ($model->upload()) {
                return $this->redirect(['view', 'id' => $model->ProductID]);
            }
        }

        return $this->render('upload-image', ['model' => $model]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $ProductID Product ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCreate()
    {
        $model = new Product();
        $model->CreatedAt = date('Y-m-d H:i:s');

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->eventImage = UploadedFile::getInstance($model, 'eventImage');

            if ($model->validate()) {
                // Handle file upload if image was uploaded
                if ($model->eventImage) {
                    $model->upload();
                }

                if ($model->save(false)) { // false to skip validation as we already validated
                    return $this->redirect(['view', 'ProductID' => $model->ProductID]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($ProductID)
    {
        $model = $this->findModel($ProductID);

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->eventImage = UploadedFile::getInstance($model, 'eventImage');

            if ($model->validate()) {
                // Handle file upload if image was uploaded
                if ($model->eventImage) {
                    $model->upload();
                }

                if ($model->save(false)) { // false to skip validation as we already validated
                    return $this->redirect(['view', 'ProductID' => $model->ProductID]);
                }
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $ProductID Product ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($ProductID)
    {
        $this->findModel($ProductID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $ProductID Product ID
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($ProductID)
    {
        if (($model = Product::findOne(['ProductID' => $ProductID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
