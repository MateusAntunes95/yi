<?php

namespace app\controllers;

use app\services\ClubService;
use app\enums\ClubEnum;
use yii\rest\Controller;
use yii\web\Response;

class ClubController extends Controller
{
    private ClubService $service;
    public $enableCsrfValidation = false;

    /**
     * @param string $id
     * @param \yii\base\Module $module
     * @param ClubService $service
     * @param array $config
     * @return void
     */
    public function __construct($id, $module, ClubService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        return $this->service->list(null);
    }

    /**
     * @return array
     */
    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $results = $this->service->importFromCrawler();

            return [
                'success' => true,
                'imported' => $results
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param string $slug
     * @param string $field
     * @return array
     */
    public function actionDetail($slug, $field)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            return [
                'success' => true,
                'value' => $this->service->getClubField($slug, $field),
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
