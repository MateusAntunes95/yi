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

        $name = \Yii::$app->request->get('name');

        $clubs = $this->service->list($name);

        return [
            'success' => true,
            'total' => count($clubs),
            'data' => $clubs
        ];
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
     * @return array
     */
    public function actionDetail(string $slug)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            if (!defined("app\\enums\\ClubEnum::$slug")) {
                throw new \RuntimeException("Enum inválido: {$slug}");
            }

            $enum = constant("app\\enums\\ClubEnum::$slug");
            $results = $this->service->getDetail($enum);

            return [
                'success' => true,
                'detail' => $results
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
