<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Ajax controller
 */
class AjaxController extends Controller
{
    private static $forbidPaths = [
        '/config/',
        '/common/config/',
        '/backend/config/',
    ];

    public function actionGetFile()
    {
        $file = $this->request->get('file');

        if (!file_exists($file)) {
            throw new \yii\web\HttpException(404, 'File not found');
        }

        foreach (self::$forbidPaths as $forbidPath) {
            if (stripos($file, $forbidPath) !== FALSE) {
                throw new \yii\web\HttpException(403, 'Frobidden path to retrieve');
            }
        }

        header('Content-Type: text/plain;');

		echo file_get_contents($file);

		die;
    }
}