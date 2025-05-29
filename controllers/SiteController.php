<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
<<<<<<< HEAD
use app\models\Product;
use app\models\Cart;
use app\models\CartItem;
use app\models\CartSearch;
use app\models\Order;
use Error;
use yii\data\ActiveDataProvider;


=======
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

<<<<<<< HEAD


    public function actionCheckout()
    {
        $orderModel = new \app\models\Order();
        $userId = Yii::$app->user->id;

        if ($orderModel->load(Yii::$app->request->post())) {
            $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

            if ($cart && !empty($cart->cartItems)) {
                $orderModel->user_id = $userId;
                $orderModel->total_amount = array_sum(array_map(function ($item) {
                    return $item->Quantity * $item->product->Price;
                }, $cart->cartItems));

                if ($orderModel->save()) {
                    $cart->Status = 'checked_out';
                    $cart->save();

                    $newCart = Cart::createNewCart($userId);

                    if (!$newCart) {
                        Yii::$app->session->setFlash('error', 'Failed to create a new cart.');
                    }


                    return $this->redirect(['site/shop', 'id' => $orderModel->id]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Your cart is empty.');
            }
        }

        return $this->render('checkout', [
            'orderModel' => $orderModel,
            'cart' => Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one(),
            'userId' => $userId,
            // 'orderModel' => $orderModel,
        ]);
    }

    /**
     * Process the checkout.
     */
    public function actionProcessCheckout()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = Yii::$app->user->id;

        // Fetch the user's cart
        $cart = Cart::find()->where(['UserID' => $userId, 'Status' => 'open'])->with('cartItems.product')->one();

        if (!$cart || empty($cart->cartItems)) {
            return ['success' => false, 'message' => 'Your cart is empty.'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Validate stock availability and calculate total
            $totalAmount = 0;
            foreach ($cart->cartItems as $item) {
                $product = $item->product;

                if (!$product || $product->StockQuantity < $item->Quantity) {
                    throw new \Exception("Product '{$product->Name}' is out of stock or insufficient quantity.");
                }

                $totalAmount += $item->Quantity * $product->Price;

                // Reduce stock for the product
                $product->StockQuantity -= $item->Quantity;
                if (!$product->save()) {
                    throw new \Exception('Failed to update product stock.');
                }
            }

            // Mark cart as checked out
            $cart->Status = 'checked_out';
            if (!$cart->save()) {
                throw new \Exception('Failed to update cart status.');
            }

            $transaction->commit();

            // Success response
            return [
                'success' => true,
                'message' => 'Checkout successful! Thank you for your order.',
                'totalAmount' => $totalAmount,
            ];
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error('Checkout error: ' . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

=======
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
<<<<<<< HEAD

    public function actionSignup()
    {
        $model = new \app\models\SignupForm();

        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registering. You can now login.');
            return $this->redirect(['login']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }
    public function actionShop()
    {
        return $this->render('shop');
    }

    public function actionOrderSuccess()
    {
        return $this->render('order-success');
    }
    public function actionSingleProduct()
    {
        // This action can be used to display a single product's details
        // You can pass the product ID as a parameter and fetch the product from the database
        // For now, we'll just render a placeholder view
        return $this->render('single-product');
    }



    public function actionAddToCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post('productId');
            $quantity = Yii::$app->request->post('quantity', 1);

            try {
                // Get or create cart for the current user/session
                $cart = $this->getOrCreateCart();
                if (!$cart) {
                    throw new \Exception('Failed to create or retrieve the cart.');
                }

                // Check if the product exists
                $product = Product::findOne($productId);
                if (!$product) {
                    return ['success' => false, 'message' => 'Product not found.'];
                }

                // Check if item already exists in the cart
                $cartItem = CartItem::find()
                    ->where(['CartID' => $cart->CartID, 'ProductID' => $productId])
                    ->one();

                if ($cartItem) {
                    // Update existing item quantity
                    $cartItem->Quantity += $quantity;
                } else {
                    // Create new cart item
                    $cartItem = new CartItem();
                    $cartItem->CartID = $cart->CartID;
                    $cartItem->ProductID = $productId;
                    $cartItem->Quantity = $quantity;
                    $cartItem->Price = $product->Price;
                }

                if (!$cartItem->save()) {
                    throw new \Exception('Failed to save cart item: ' . json_encode($cartItem->errors));
                }

                return [
                    'success' => true,
                    'message' => 'Item added to cart successfully!',
                    'cartCount' => $this->getCartItemCount($cart->CartID)
                ];
            } catch (\Exception $e) {
                Yii::error('Add to cart error: ' . $e->getMessage(), __METHOD__);
                return ['success' => false, 'message' => $e->getMessage()];
            }
        }

        return ['success' => false, 'message' => 'Invalid request.'];
    }

    /**
     * Update cart item quantity
     */
    public function actionUpdateCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post('productId');
            $quantity = Yii::$app->request->post('quantity');

            try {
                $cart = $this->getOrCreateCart();

                $cartItem = CartItem::find()
                    ->where(['CartID' => $cart->CartID, 'ProductID' => $productId])
                    ->one();

                if ($cartItem) {
                    if ($quantity > 0) {
                        $cartItem->Quantity = $quantity;
                        $cartItem->save();
                    } else {
                        $cartItem->delete();
                    }

                    return [
                        'success' => true,
                        'message' => 'Cart updated successfully!',
                        'cartCount' => $this->getCartItemCount($cart->CartID)
                    ];
                }

                return ['success' => false, 'message' => 'Item not found in cart.'];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Failed to update cart.'];
            }
        }

        return ['success' => false, 'message' => 'Invalid request.'];
    }

    /**
     * Remove item from cart
     */
    public function actionRemoveFromCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post('productId');

            try {
                $cart = $this->getOrCreateCart();

                $cartItem = CartItem::find()
                    ->where(['CartID' => $cart->CartID, 'ProductID' => $productId])
                    ->one();

                if ($cartItem) {
                    $cartItem->delete();

                    return [
                        'success' => true,
                        'message' => 'Item removed from cart!',
                        'cartCount' => $this->getCartItemCount($cart->CartID)
                    ];
                }

                return ['success' => false, 'message' => 'Item not found in cart.'];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Failed to remove item.'];
            }
        }

        return ['success' => false, 'message' => 'Invalid request.'];
    }

    public function actionMyOrders()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()
                ->where(['user_id' => Yii::$app->user->id])
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('my-orders', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewOrder($id)
    {
        $order = Order::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if (!$order) {
            throw new Error('Order not found or you do not have permission to view it.');
        }

        return $this->render('view-order', [
            'order' => $order,
        ]);
    }

    /**
     * Get cart item count for AJAX updates
     */
    public function actionCartCount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $cart = $this->getOrCreateCart();
            $count = $this->getCartItemCount($cart->CartID);

            return ['success' => true, 'count' => $count];
        } catch (\Exception $e) {
            return ['success' => false, 'count' => 0];
        }
    }

    /**
     * Helper method to get or create cart
     */
    private function getOrCreateCart()
    {
        $session = Yii::$app->session;

        if (!Yii::$app->user->isGuest) {
            $cart = Cart::find()
                ->where(['UserID' => Yii::$app->user->id, 'Status' => Cart::STATUS_OPEN])
                ->one();
        } else {
            $cartId = $session->get('cart_id');
            if ($cartId) {
                $cart = Cart::findOne($cartId);
                if ($cart && $cart->Status !== Cart::STATUS_OPEN) {
                    $cart = null;
                    $session->remove('cart_id');
                }
            } else {
                $cart = null;
            }
        }

        if (!$cart) {
            $cart = Cart::createNewCart(!Yii::$app->user->isGuest ? Yii::$app->user->id : null);
            if (!$cart) {
                throw new \Exception('Unable to create a new cart.');
            }
            if (Yii::$app->user->isGuest) {
                $session->set('cart_id', $cart->CartID);
            }
        }

        return $cart;
    }


    /**
     * Helper method to get cart item count
     */
    private function getCartItemCount($cartId)
    {
        return CartItem::find()
            ->where(['CartID' => $cartId])
            ->sum('Quantity') ?: 0;
    }
=======
>>>>>>> afb9eb7f10e78b3a3c8b3f3442ae42b124538ca2
}
