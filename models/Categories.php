<?php

namespace app\models;

use phpDocumentor\Reflection\Types\Integer;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "categories".
 *
 * @property string|null $name
 * @property integer|null $id
 */
class Categories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['id'], 'integer']
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
        ];
    }

    public function search($params = []): ActiveDataProvider
    {
        $this->load($params);
        $query = self::find()->alias('tbl')->cache(0);

        $query->andFilterWhere(['like', 'name', $this->name]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);


        return $dataProvider;
    }

}
