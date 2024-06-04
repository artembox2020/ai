<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "provider".
 *
 * @property int $id
 * @property int $group_id
 * @property string $api_key
 * @property int $is_active
 * @property string|null $expire_at
 *
 * @property ProviderGroup $group
 */
class Provider extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group_id', 'api_key'], 'required'],
            [['group_id', 'is_active'], 'integer'],
            [['expire_at'], 'safe'],
            [['api_key'], 'string', 'max' => 255],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProviderGroup::class, 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group ID',
            'api_key' => 'Api Key',
            'is_active' => 'Is Active',
            'expire_at' => 'Expire At',
        ];
    }

    /**
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(ProviderGroup::class, ['id' => 'group_id']);
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
