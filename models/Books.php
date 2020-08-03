<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "books".
 *
 * @property int $id
 * @property int $isbn
 * @property string|null $title
 * @property int|null $page_count
 * @property string|null $published_date
 * @property string|null $thumbnail_url
 * @property string|null $short_description
 * @property string|null $long_description
 * @property string|null $status
 * @property string|null $authors
 * @property string|null $categories
 */
class Books extends \yii\db\ActiveRecord
{
    public $dirImages = '/assets/img/books/';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'books';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isbn'], 'required'],
            [['isbn', 'page_count'], 'integer'],
            [['published_date'], 'safe'],
            [['short_description', 'long_description', 'authors', 'categories', 'status'], 'string'],
            [['title', 'thumbnail_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'isbn' => 'Isbn',
            'title' => 'Title',
            'page_count' => 'Page Count',
            'published_date' => 'Published Date',
            'thumbnail_url' => 'Thumbnail Url',
            'short_description' => 'Short Description',
            'long_description' => 'Long Description',
            'status' => 'Status',
            'authors' => 'Authors',
            'categories' => 'Categories',
        ];
    }

    public function search($params = []): ActiveDataProvider
    {
        $this->load($params);
        $query = self::find()->alias('tbl')->cache(0);
        $query
            ->andFilterWhere([
                'isbn' => $this->isbn,
            ]);
        $query->andFilterWhere(['like', 'title', $this->title]);
        $query->andFilterWhere(['like', 'published_date', $this->published_date]);
        $query->andFilterWhere(['like', 'status', $this->status]);
        $query->andFilterWhere(['like', 'authors', $this->authors]);
        $query->andFilterWhere(['like', 'short_description', $this->short_description]);
        $query->andFilterWhere(['like', 'categories', $this->categories]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);


        return $dataProvider;
    }

}
