<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\Product;
use app\models\ProductSearch;
use app\models\Cart;
use app\models\CartItem;
use app\models\User;
use app\models\Order;
use app\models\CartItemSearch;
use yii\web\UploadedFile;

class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest &&
                                Yii::$app->user->identity->username === 'admin';
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    return $this->redirect(['/site/login']);
                }
            ],
        ];
    }

    /**
     * Admin Dashboard
     */
    public function actionDashboard()
    {
        $this->layout = 'admin';

        // Get statistics
        $totalProducts = Product::find()->count();
        $totalUsers = User::find()->where(['!=', 'username', 'admin'])->count();
        $totalOrders = Order::find()->count();
        $totalRevenue = Order::find()->sum('total_amount') ?: 0;

        // Recent activities
        $recentProducts = Product::find()
            ->orderBy(['CreatedAt' => SORT_DESC])
            ->limit(5)
            ->all();

        $recentOrders = Order::find()
            ->with(['user'])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();

        $lowStockProducts = Product::find()
            ->where(['<=', 'StockQuantity', 10])
            ->orderBy(['StockQuantity' => SORT_ASC])
            ->limit(5)
            ->all();

        $monthlySales = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $sales = Order::find()
                ->where(['like', 'created_at', $month])
                ->sum('total_amount') ?: 0;
            $monthlySales[] = [
                'month' => date('M Y', strtotime("-$i months")),
                'sales' => $sales
            ];
        }

        return $this->render('dashboard', [
            'totalProducts' => $totalProducts,
            'totalUsers' => $totalUsers,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'recentProducts' => $recentProducts,
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'monthlySales' => $monthlySales,
        ]);
    }

    /**
     * Products Management - List all products
     */
    public function actionProducts()
    {
        $this->layout = 'admin';

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('product', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Create new product
     */
    public function actionProductCreate()
    {
        $this->layout = 'admin';

        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            // Handle image upload
            $model->ImageURL = UploadedFile::getInstance($model, 'ImageURL');

            if ($model->ImageURL) {
                $fileName = time() . '_' . $model->ImageURL->baseName . '.' . $model->ImageURL->extension;
                $uploadPath = Yii::getAlias('@webroot/uploads/products/');

                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                if ($model->ImageURL->saveAs($uploadPath . $fileName)) {
                    $model->ImageURL = 'uploads/products/' . $fileName;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Product created successfully.');
                return $this->redirect(['products']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to create product.');
            }
        }

        return $this->render('product-create', [
            'model' => $model,
        ]);
    }

    /**
     * View product details
     */
    public function actionProductView($id)
    {
        $this->layout = 'admin';

        $model = $this->findProductModel($id);

        return $this->render('product-view', [
            'model' => $model,
        ]);
    }

    /**
     * Update product
     */
    public function actionProductUpdate($id)
    {
        $this->layout = 'admin';

        $model = $this->findProductModel($id);
        $oldImageURL = $model->ImageURL;

        if ($model->load(Yii::$app->request->post())) {
            // Handle image upload
            $model->ImageURL = UploadedFile::getInstance($model, 'ImageURL');

            if ($model->ImageURL) {
                $fileName = time() . '_' . $model->ImageURL->baseName . '.' . $model->ImageURL->extension;
                $uploadPath = Yii::getAlias('@webroot/uploads/products/');

                // Create directory if it doesn't exist
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                if ($model->ImageURL->saveAs($uploadPath . $fileName)) {
                    // Delete old image if exists
                    if ($oldImageURL && file_exists(Yii::getAlias('@webroot/') . $oldImageURL)) {
                        unlink(Yii::getAlias('@webroot/') . $oldImageURL);
                    }
                    $model->ImageURL = 'uploads/products/' . $fileName;
                }
            } else {
                // Keep old image if no new image uploaded
                $model->ImageURL = $oldImageURL;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Product updated successfully.');
                return $this->redirect(['product-view', 'id' => $model->ProductID]);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update product.');
            }
        }

        return $this->render('product-update', [
            'model' => $model,
        ]);
    }

    /**
     * Delete product
     */
    public function actionProductDelete($id)
    {
        $model = $this->findProductModel($id);

        // Delete image file if exists
        if ($model->ImageURL && file_exists(Yii::getAlias('@webroot/') . $model->ImageURL)) {
            unlink(Yii::getAlias('@webroot/') . $model->ImageURL);
        }

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Product deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete product.');
        }

        return $this->redirect(['products']);
    }

    /**
     * Find product model by ID
     */
    protected function findProductModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested product does not exist.');
    }

    /**
     * Orders Management
     */
    public function actionOrders()
    {
        $this->layout = 'admin';

        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->with(['user'])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('orders', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * View specific order
     */
    public function actionViewOrder($id)
    {
        $this->layout = 'admin';

        $order = Order::findOne($id);
        if (!$order) {
            throw new NotFoundHttpException('Order not found.');
        }

        return $this->render('view-order', [
            'order' => $order,
        ]);
    }

    /**
     * Update order status
     */
    public function actionUpdateOrderStatus($id)
    {
        $order = Order::findOne($id);
        if (!$order) {
            throw new NotFoundHttpException('Order not found.');
        }

        if (Yii::$app->request->isPost) {
            $status = Yii::$app->request->post('status');
            $order->status = $status;

            if ($order->save()) {
                Yii::$app->session->setFlash('success', 'Order status updated successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update order status.');
            }
        }

        return $this->redirect(['view-order', 'id' => $id]);
    }

    /**
     * Users Management
     */
    public function actionUsers()
    {
        $this->layout = 'admin';

        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where(['!=', 'username', 'admin']),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('users', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Cart Items Management
     */

    public function actionCartItems()
    {
        $this->layout = 'admin';

        $searchModel = new CartItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Ensure $dataProvider->query is an instance of yii\db\ActiveQuery before calling joinWith
        $query = $dataProvider->query;
        if ($query instanceof \yii\db\ActiveQuery) {
            $query->joinWith(['cart', 'cart.user', 'product']);
        }

        return $this->render('cart-items', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Reports
     */
    public function actionReports()
    {
        $this->layout = 'admin';

        // Sales by month
        $salesByMonth = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $sales = Order::find()
                ->where(['like', 'created_at', $month])
                ->sum('total_amount') ?: 0;
            $orders = Order::find()
                ->where(['like', 'created_at', $month])
                ->count();
            $salesByMonth[] = [
                'month' => date('M Y', strtotime("-$i months")),
                'sales' => $sales,
                'orders' => $orders
            ];
        }

        // Top selling products
        $topProducts = Product::find()
            ->select(['products.*', 'SUM(cartitem.Quantity) as total_sold'])
            ->leftJoin('cartitem', 'products.ProductID = cartitem.ProductID')
            ->groupBy('products.ProductID')
            ->orderBy(['total_sold' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('reports', [
            'salesByMonth' => $salesByMonth,
            'topProducts' => $topProducts,
        ]);
    }
}
