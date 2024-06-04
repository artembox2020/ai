<?php

namespace backend\models;

use Yii;
use common\models\OnlinesimAlerts;

/**
 * This is the model class for table "onlinesim_numbers".
 *
 * @property string $code
 * @property string $href
 * @property string $last_number
 * @property string $updated_at
 */
class OnlinesimNumbers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'onlinesim_numbers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'href', 'last_number'], 'required'],
            [['updated_at'], 'safe'],
            [['code'], 'string', 'max' => 8],
            [['href'], 'string', 'max' => 255],
            [['last_number'], 'string', 'max' => 64],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Code',
            'href' => 'Href',
            'last_number' => 'Last Number',
            'updated_at' => 'Updated At',
        ];
    }

    public function beforeSave($attr)
    {
        //echo "<br/>{$this->code}:NOW:"; var_dump($now); echo "<br/>";
        $expression = new \yii\db\Expression('now()');
        $now = (new \yii\db\Query)->select($expression)->scalar();
        $this->updated_at = $now;

        return parent::beforeSave($attr);
    }

    public function updateByData($data)
    {
        $codesList = array_keys($data);
        $codesStr = !empty($codesList) ? ("'" . implode("','", $codesList) . "'") : '';

        $models = self::find()->where(['code' => $codesList])->all();
        $existModels = [];

        foreach ($models as $model) {
            $existModels[$model->code] = $model;
        }

        foreach ($data as $code => $item) {
            echo "<br/>CODE:"; var_dump($code); echo "<br/>";
            $model = array_key_exists($code, $existModels) ? $existModels[$code] : new OnlinesimNumbers();
            $result = OnlinesimAlerts::postAlerts($model, $item, $code);
            $model->code = $code;
            $model->href = $item['href'];
            $model->last_number = isset($item['last_phone']) ? $item['last_phone'] : null;

            //var_dump($model);

            $model->save();
        }
    }

    public function saveRec($validate = true)
    {
        if ($validate && !$this->validate()) {

            return false;
        }
        
        $expression = new \yii\db\Expression('now()');
        $now = (new \yii\db\Query)->select($expression)->scalar();

        if ($this->isNewRecord) {
            Yii::$app->db->createCommand()
            ->insert('onlinesim_numbers', [
                'code' => $this->code,
                'last_number' => $this->last_number,
                'href' => $this->href,
                'updated_at' => $now,
            ])->execute();
        } else {
            Yii::$app->db->createCommand()
            ->update('onlinesim_numbers', [
                'last_number' => $this->last_number,
                'updated_at' => $now,
            ], ['id' => $this->id])->execute();
        }

        return true;
    }
}
