<?php

namespace common\models;

use Yii;
use backend\models\OnlinesimNumbers;
use common\components\parser\Onlinesim;
use common\components\Telegram;

/**
 * This is the model class for table "onlinesim_alerts".
 *
 * @property int $id
 * @property string $code
 * @property string $msg
 * @property string $created_at
 */
class OnlinesimAlerts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'onlinesim_alerts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'msg'], 'required'],
            [['created_at'], 'safe'],
            [['code'], 'string', 'max' => 8],
            [['msg'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'msg' => 'Msg',
            'created_at' => 'Created At',
        ];
    }
    
    public function beforeSave($attr)
    {
        //echo "<br/>{$this->code}:NOW:"; var_dump($now); echo "<br/>";
        $expression = new \yii\db\Expression('now()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $this->created_at = $now;

        return parent::beforeSave($attr);
    }

    public static function postAlerts($model, $item, $code)
    {//return true;
        $onlinesim = new Onlinesim([], false);
        $baseUrl = $onlinesim->url;
        
        //return true;

        var_dump($model->isNewRecord);

        if (!empty($item['last_phone']) && ($model->isNewRecord || ($model->last_number != $item['last_phone']))) {
            $link =  $baseUrl . $item['href'];
            $msg = "<a href='{$link}'>New phone: {$item['last_phone']}</a>";
        }
        
        //return true;

        if (!empty($msg)) {
            echo $msg . "<br/>" . $code . "<br/>";
            $alert = new OnlinesimAlerts();
            $alert->code = $code;
            $alert->msg = $msg;
            var_dump($alert->save());

            Telegram::sendMessage($msg, "@znaidachn");

            return true;
        }

        return false;
    }
}
