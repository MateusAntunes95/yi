<?php

namespace app\controllers;

use services\ClubService;
use yii\web\Controller;
use yii\web\Response;

class ClubController extends Controller
{
    public function create()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $service = new ClubService();
        $results = $service->importFromCrawler();

        return [
            'success' => true,
            'imported' => $results
        ];
    }

    public function list()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $name = \Yii::$app->request->get('name');

        $service = new ClubService();
        $clubs = $service->list($name);

        return [
            'success' => true,
            'total' => count($clubs),
            'data' => $clubs
        ];
    }
}
