<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Product;
use app\models\Cart;
use app\models\CartItem;
use app\models\CartSearch;


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
    public function actionSingleProduct()
    {
        // This action can be used to display a single product's details
        // You can pass the product ID as a parameter and fetch the product from the database
        // For now, we'll just render a placeholder view
        return $this->render('single-product');
    }

    public function actionCheckout()
    {
        // This action can be used to handle the checkout process
        // You can implement your checkout logic here
        // For now, we'll just render a placeholder view
        return $this->render('checkout');
    }

    public function actionAddToCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->request->isPost) {
            $productId = Yii::$app->request->post('productId');
            $quantity = Yii::$app->request->post('quantity', 1);

            try {
                // Get or create cart for current user/session
                $cart = $this->getOrCreateCart();

                // Check if product exists
                $product = Product::findOne($productId); // Adjust model name as needed
                if (!$product) {
                    return ['success' => false, 'message' => 'Product not found.'];
                }

                // Check if item already exists in cart
                $cartItem = CartItem::find()
                    ->where(['CartID' => $cart->CartID, 'ProductID' => $productId])
                    ->one();

                if ($cartItem) {
                    // Update existing item quantity
                    $cartItem->Quantity += $quantity;
                    $cartItem->save();
                } else {
                    // Create new cart item
                    $cartItem = new CartItem();
                    $cartItem->CartID = $cart->CartID;
                    $cartItem->ProductID = $productId;
                    $cartItem->Quantity = $quantity;
                    $cartItem->Price = $product->Price; // Assuming you have a Price field
                    $cartItem->save();
                }

                return [
                    'success' => true,
                    'message' => 'Item added to cart successfully!',
                    'cartCount' => $this->getCartItemCount($cart->CartID)
                ];
            } catch (\Exception $e) {
                return ['success' => false, 'message' => 'Failed to add item to cart.'];
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

        // For logged-in users, use UserID, otherwise use session
        if (!Yii::$app->user->isGuest) {
            $cart = Cart::find()->where(['UserID' => Yii::$app->user->id])->one();
        } else {
            // For guest users, store cart ID in session
            $cartId = $session->get('cart_id');
            $cart = $cartId ? Cart::findOne($cartId) : null;
        }

        if (!$cart) {
            $cart = new Cart();
            $cart->UserID = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
            $cart->CreatedAt = date('Y-m-d H:i:s');
            $cart->save();

            // Store cart ID in session for guest users
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
}
