<?php

namespace common\models;

use Yii;
use common\components\parser\BaseParser;

/**
 * This is the model class for table "worker_urls".
 *
 * @property int $id
 * @property string $url
 * @property int $is_active
 * @property int $is_busy
 * @property int $is_init
 */
class WorkerUrls extends \yii\db\ActiveRecord
{
    const DOMAIN = 'http://k4225675.bget.ru';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'worker_urls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['is_active', 'is_busy', 'is_init'], 'integer'],
            [['url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'url' => 'Url',
            'is_active' => 'Is Active',
            'is_busy' => 'Is Busy',
            'is_init' => 'Is Init',
        ];
    }

    public static function initUrls()
	{
	    $wUrls = self::find()->andWhere(['is_active' =>1])->all();

	    foreach ($wUrls as $wUrl) {
	        $url = $wUrl->url . '/init.php?domain=' . self::DOMAIN;
	        $curl = BaseParser::initCurl($url, [], '', '');

	        curl_exec($curl);
			curl_close($curl);

			$url = $wUrl->url . '/initapi.php?domain=' . self::DOMAIN;
			$curl = BaseParser::initCurl($url, [], '', '');

			curl_exec($curl);
			curl_close($curl);

			$wUrl->is_init = 1;
			$wUrl->save(false);

			echo "\n{$wUrl->url} passed\n";
	    }

	    return true;
	}
	
	public static function findAvailableWorker()
	{

	    return self::find()->andWhere([
	        'is_active' => 1,
	        'is_busy' => 0,
	        'is_init' => 1
	        ])
	        ->orderBy("RAND()")
	        ->one()
	   ;
	}

	public function setBusy()
    {
        $this->is_busy = 1;
        $this->save(false);

        return $this;
    }

    public function unsetBusy()
    {
        $this->is_busy = 0;
        $this->save(false);

        return $this;
    }
}
