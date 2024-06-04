<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\WorkerUrls;
use common\models\ProviderGroup;
use common\components\ai\NeuralWriter;
use common\components\parser\BaseParser;
use Yii\web\Response;
use Yii;

/**
 * Api controller
 */
class ApiController extends Controller
{
    public function beforeAction()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return parent::beforeAction($action);
    }
    
    public function fetchGroupAndProvider(&$group, &$provider)
    {
        $group = ProviderGroup::find()->one();
        $provider = null;

        if ($group) {
            $provider = $group->getAvailableProvider();
        }
    }

    public function actionParaphrase()
    {
        $result = ["success" => false];
        $text = $_REQUEST['text'];
        $from = $_REQUEST['from'];
        
        //echo "<pre>"; var_dump($_REQUEST); echo "</pre>"; die;

        if (empty($text) || empty($from)) {

            return $result;
        }

        $this->fetchGroupAndProvider($group, $provider);

        if (empty($group) || empty($provider)) {

            return $result;
        }
        
        //echo "<br/>" . $provider->id . "<br/>";

        $provider->setBusy();

        $wUrl = WorkerUrls::findAvailableWorker();

        if (empty($wUrl)) {

            return $result;  
        }

        $wUrl->setBusy();

        $params = [
            'text' => $text,
            'from' => $from,
            'providerName' => $group->name,
            'providerUrl' => $group->base_url,
            'api_key' => $provider->api_key,
        ];

        $endpoint = $wUrl->url . '/aiapi/paraphrase.php';

        $ch = BaseParser::initCurl($endpoint, $params, '', '', null, true);

        $r = curl_exec($ch);
        curl_close($ch);
        $wUrl->unsetBusy();
        $provider->unsetBusy();
        
        return $provider->id . "   " . $r;
        
        if (!empty($r)) {

            return ["success" => true, "result" => $r];
        }

        return $result;
    }

    public function actionTestWorkerUrls()
    {
        var_dump(WorkerUrls::initUrls());
        
        return "<br/>TestWorkerUrls<br/>";
        
        
    }
}