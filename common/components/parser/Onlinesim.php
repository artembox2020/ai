<?php

namespace common\components\parser;

use Yii;
use yii\base\BaseObject;
use yii\diDom\Document;

class Onlinesim extends BaseObject
{
    public $url;
    public $document;

    public function __construct($config = [], $initialize = true)
    {
        //$this->url = 'https://mysqlprj.000webhostapp.com/get_content.php?url=https://onlinesim.io';
        $this->url = 'https://onlinesim.io';
        
        if ($initialize) {
            $this->document = new Document($this->url, true);
        }
        
        //var_dump($this->document); die;

        parent::__construct($config);
    }

    public function init()
    {

        parent::init();
    }

    public function getCountries()
    {
        $sel = ".countries .fw-li";
        $countries = [];

        foreach ($this->document->find($sel) as $item) {

            if (stristr($item->class, "--archive") !== FALSE) {
                continue;
            }

            $code = $item->find(".addon")[0]->text();
            $countries[$code] = ['href' => $item->href];
        }

        return $countries;
    }

    public function getCountriesLastNumbers($countries = [])
    {
        if (empty($countries)) {
            $countries = $this->getCountries();
        }
        
        //var_dump($countries); die;
        
        $i = 0;

        foreach ($countries as $code => $data) {
            $fullUrl = $this->url . $data['href'];
            
            var_dump($fullUrl);
            $selector = ".fw-items .fw-li .text";
            $document = new Document($fullUrl, true);
            $data = $document->find($selector);
            
            //die;

            if (empty($data)) {

                continue;
            }
            
            $number = $data[0]->text();

            $countries[$code]['last_phone'] = $number;

            if (++$i >= 40) {

                break;
            }
        }

        return $countries;
    }
}