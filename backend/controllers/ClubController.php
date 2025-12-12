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

    public function __construct($id, $module, ClubService $service, $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
    }

    public function actionList()
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
