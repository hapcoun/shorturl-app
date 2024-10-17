<?php

namespace app\controllers;

use app\components\AppConstants;
use app\components\ClickStatistics;
use app\models\ShortUrl;
use app\models\ShortUrlStatistics;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\Pagination;

class ShorturlController extends Controller
{
    protected ClickStatistics $clickStatistics;

    public function __construct($id, $module, $config = [])
    {
        $this->clickStatistics = new ClickStatistics();
        parent::__construct($id, $module, $config);
    }

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
                        'actions' => ['redirect'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'create', 'redirect', 'statistics'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ]
            ]
        ];
    }

    public function actionIndex(): string {
        $query = ShortUrl::find()->where(['user_id' => Yii::$app->user->id]);
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);
        $shortUrls = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', [
            'shortUrls' => $shortUrls,
            'pagination' => $pagination
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionCreate(): Response|string {
        $model = new ShortUrl();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Short URL successfully created.');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionRedirect($code): Response {
        $cacheKey = AppConstants::SHORT_URL_CACHE_KEY_PREFIX . $code;
        $shortUrl = Yii::$app->cache->get($cacheKey);

        if ($shortUrl === false) {
            $shortUrl = ShortUrl::findOne(['short_code' => $code]);

            if (!$shortUrl) {
                throw new NotFoundHttpException("Link not found.");
            }

            Yii::$app->cache->set($cacheKey, $shortUrl, AppConstants::SHORT_URL_CACHE_EXPIRATION);
        }

        $this->clickStatistics->registerClick($shortUrl->id);
        return $this->redirect($shortUrl->original_url);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionStatistics(int $id): string {
        $shortUrl = ShortUrl::find()
        ->where([
            'id' => $id,
            'user_id' => Yii::$app->user->id
        ])
        ->one();

        if ($shortUrl === null) {
            throw new NotFoundHttpException("URL not found");
        }

        $query = ShortUrlStatistics::find()
            ->select(['DATE(clicked_at) as date', 'SUM(clicks) as date_clicks'])
            ->where(['short_url_id' => $shortUrl->id])
            ->groupBy(['DATE(clicked_at)'])
            ->orderBy(['date' => SORT_ASC]);
        $pagination = new Pagination([
            'defaultPageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $statistics = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('statistics', [
            'shortUrl' => $shortUrl,
            'statistics' => $statistics,
            'pagination' => $pagination,
        ]);
    }
}
