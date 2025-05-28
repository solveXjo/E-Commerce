<?php

namespace app\controllers;

use app\models\Cart;
use app\Models\CartSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;
use yii\web\Response;
use yii\filters\AccessControl;

/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Cart models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CartSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $userId = Yii::$app->user->id; // Assuming user is logged in
        $cart = Cart::find()->where([
            'UserID' => $userId,
            'Status' => 'open'
        ])->with('cartItems.product')->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'cart' => $cart,
            'userId' => $userId,
        ]);
    }
    // public function actionIndex()
    // {
    //     

    //     return $this->render('index', [
    //         'cart' => $cart,
    //     ]);
    // }

    /**
     * Displays a single Cart model.
     * @param int $CartID Cart ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($CartID)
    {
        return $this->render('view', [
            'cart' => $CartID,
        ]);
    }


    public function actionUpdateCart()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $productId = Yii::$app->request->post('productId');
        $quantity = Yii::$app->request->post('quantity');

        $session = Yii::$app->session;
        $cart = $session->get('cart', []);

        foreach ($cart as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] = max(1, (int)$quantity); // Prevent 0 or negative quantity
                break;
            }
        }

        $session->set('cart', $cart);
        return ['success' => true];
    }

    public function actionRemoveFromCart()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $productId = Yii::$app->request->post('productId');

        $session = Yii::$app->session;
        $cart = $session->get('cart', []);

        $cart = array_filter($cart, fn($item) => $item['id'] != $productId);

        $session->set('cart', $cart);
        return ['success' => true];
    }

    /**
     * Creates a new Cart model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {


        $model = new Cart();
        $model->CreatedAt = date('Y-m-d H:i:s');


        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'CartID' => $model->CartID]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cart model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $CartID Cart ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($CartID)
    {
        $model = $this->findModel($CartID);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'CartID' => $model->CartID]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cart model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $CartID Cart ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($CartID)
    {
        $this->findModel($CartID)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $CartID Cart ID
     * @return Cart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($CartID)
    {
        if (($model = Cart::findOne(['CartID' => $CartID])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
