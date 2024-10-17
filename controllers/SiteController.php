<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\forms\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

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
                    [
                        'actions' => ['login', 'register'],
                        'allow' => true,
                        'roles' => ['?'],
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
    public function actions(): array {
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

    public function actionSignup()
    {
        $signupForm = new SignupForm();

        if ($signupForm->load(Yii::$app->request->post()) && $signupForm->signup()) {
            Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались. Теперь вы можете войти.');
            return $this->redirect(['site/login']);
        }

        return $this->render('signup', ['signupForm' => $signupForm]);
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

        $loginForm = new LoginForm();

        if ($loginForm->load(Yii::$app->request->post()) && $loginForm->login()) {
            return $this->goBack();
        }

        $loginForm->password = '';
        return $this->render('login', [
            'loginForm' => $loginForm,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
