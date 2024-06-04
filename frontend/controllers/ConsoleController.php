<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\WorkerUrls;
use common\components\parser\BaseParser;

/**
 * Console controller
 */
class ConsoleController extends Controller
{
    public function actionInitUrls()
    {
        WorkerUrls::initUrls();
    }

    public function actionTestMultiCurl()
    {
        $mh = curl_multi_init();

        $texts = [
            'When season comes anybody put the main question',
            'Who of the newly-minted characters will be the brightest?',
            'Mrs Taylor is well-mannered',
            'Mrs Anna stands out of the crowd',
            'This crop promises to be very dazzling'
        ];

        $froms = [
            'en',
            'en',
            'en',
            'en',
            'en',
        ];

        $endpoint = 'http://k4225675.bget.ru/api/paraphrase/';
        
        $chs = [];
        $results = [];
        
        foreach ($texts as $key => $text) {
            $params = [
                'text' => $text,
                'from' => $froms[$key],
            ];

            $ch = BaseParser::initCurl($endpoint, $params, '', '', null, false);
            $chs[] = $ch;
            curl_multi_add_handle($mh, $ch);
        }
        
        do {
            $status = curl_multi_exec($mh, $actives);
        } while ($actives && ($status == CURLM_OK));
        
        if (!$actives) {
            foreach ($chs as $ch) {
                $results[] = curl_multi_getcontent($ch);
            }
        }
        
        foreach ($chs as $ch) {
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        
        curl_multi_close($mh);
        
        echo "<pre>"; var_dump($results); echo "</pre>";
        
        die;
    }
}