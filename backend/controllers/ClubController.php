<?php
namespace app\controllers;

use app\services\ClubService;
use yii\rest\Controller;
use yii\web\Response;

class ClubController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionList()
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

    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

    try {
        $service = new ClubService();
        $results = $service->importFromCrawler();

        return [
            'success' => true,
            'imported' => $results
        ];
    } catch (\Throwable $e) {
        // Loga o erro no Yii
        \Yii::error($e->getMessage(), __METHOD__);

        // Retorna mensagem de erro para o cliente
        return [
            'success' => false,
            'error' => 'Ocorreu um erro ao importar os clubes.',
            'details' => $e->getMessage(), // opcional, pode remover em produção
        ];
    }
    }
}
