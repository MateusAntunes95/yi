<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\BadRequestHttpException;
use app\services\GenericService;

class GenericController extends Controller
{
    /**
     * @return array
     */
    public function actionExtract(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        try {
            $body = Yii::$app->request->getBodyParams();

            $url = $body['url'] ?? null;
            $selectors = $body['selectors'] ?? null;

            if (!$url || !$selectors) {
                throw new BadRequestHttpException('url e selectors são obrigatórios');
            }

            $service = new GenericService();
            return $service->extract($url, $selectors);

        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
