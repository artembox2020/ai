<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "provider_group".
 *
 * @property int $id
 * @property string $name
 * @property string $base_url
 * @property int $is_active
 *
 * @property Provider[] $providers
 */
class ProviderGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provider_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'base_url'], 'required'],
            [['is_active'], 'integer'],
            [['name', 'base_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'base_url' => 'Base Url',
            'is_active' => 'Is Active',
        ];
    }

    public static function find()
    {

        return parent::find()->andWhere(['is_active' => 1]);
    }

    /**
     * Gets query for [[Providers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProviders()
    {

        return $this->hasMany(Provider::class, ['group_id' => 'id']);
    }

    public function getAvailableProvider()
    {

        return $this->hasOne(Provider::class, ['group_id' => 'id'])
            ->where([
                'is_busy' => 0,
                'provider.is_active' => 1,
            ])
            ->orderBy("RAND()")
            ->one()
        ;

        /*if (!$provider) {

            return null;
        }

        $provider->is_busy = 1;
        $provider->save(false);*/

        return $provider;
    }

    /*public static function getAvailableProvider()
    {
        $provider = (new \yii\db\Query())
            ->select(['provider.*', 'provider_group.name'])
            ->from('provider')
            ->join('INNER JOIN', 'provider_group', 'provider_group.id = provider.group_id')
            ->where([
                'is_busy' => 0,
                'provider.is_active' => 1,
                'provider_group.is_active' => 1
            ])
            ->one();

        return $provider;
    }*/
}
