<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\Code\Services\Mapper;
use app\Code\Structures\SomeRequestStructure;

class ApiController extends Controller
{
    public function actionTest()
    {
        \Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $request = \Yii::$app->request;
        $params = json_decode($request->getRawBody(), true);

        $mapper = new Mapper();
        $struct = $mapper->map($params, SomeRequestStructure::class);

        return $struct;
    }
}
