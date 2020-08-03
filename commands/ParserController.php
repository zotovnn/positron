<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Books;
use app\models\Categories;
use DateTime;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParserController extends Controller
{
    private $defaultImage = 'default.png'; /*Изображение книги по умолчанию*/
    private $arErrs = [];

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";

        return ExitCode::OK;
    }

    public function actionTest()
    {
        $test = [];
        $test[] = 'val';
        $test[] = 'val2';
        $test[] = 'Val3';
        var_dump($test);
        var_dump(((in_array('val4', $test, true))));
    }

    public function actionParseBooks($url = 'default')
    {
        //Передавать урл экшену как параметр, или в дальнейшем подтягивать урл из конфига
        if ($url === 'default') {
            $url = 'https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json';
        }

        try {
            $json = json_decode(file_get_contents($url), true);
        } catch (\Exception $e) {
            $json = null;
        }
        if (empty($json)) {
            echo 'Error parse url ' . $url;
            $this->arErrs[] = 'Error parse url: ' . $url;
            return false;
        }

        //Вытягиваем справочник категорий из БД,
        //Чтобы в дальнейшем не делать множество запросов, а смотреть только массив
        $arCategories = array_column(Categories::find()->select('name')->all(), 'name');

        $arData = [];
        $book = new Books();

        foreach ($json as $item) {
            //Проверяем отсутствие элемента в БД
            if (!empty($item['isbn']) && !Books::find()->where(['isbn' => $item['isbn']])->exists()) {
                $data = [];
                $data['isbn'] = (int)trim($item['isbn']);
                $data['title'] = trim($item['title']);
                $data['page_count'] = trim($item['pageCount']);
                try {
                    $data['published_date'] = (new DateTime($item['publishedDate']['$date']))->format('Y-m-d');
                } catch (\Exception $e) {
                    $data['published_date'] = '0000-00-00';
                }

                $urlImage = trim($item['thumbnailUrl']);
                try {
                    $image = file_get_contents($urlImage);
                } catch (\Exception $e) {
                    $image = '';
                }
                if (empty($urlImage) || empty($image)) {
                    $thumbnail_url = $this->defaultImage;
                } else {
                    $infoImage = getimagesize($urlImage);
                    $extension = image_type_to_extension($infoImage[2]);
                    file_put_contents('./web' . $book->dirImages . $item['isbn'] . $extension, $image);
                    //Адрес == isbn до момента решения вопроса с хранением изображений
                    $thumbnail_url = $item['isbn'] . $extension;
                }

                $data['thumbnail_url'] = $thumbnail_url;
                $data['short_description'] = trim($item['shortDescription']);
                $data['long_description'] = trim($item['longDescription']);

                if (empty($item['status'])) {
                    $data['status'] = 'Unknown';
                } else {
                    $data['status'] = $item['status'];
                }

                if (empty($item['authors'])) {
                    $data['authors'] = 'Unknown';
                } else {
                    $data['authors'] = implode(',', $item['authors']);
                }

                if (empty($item['categories'])) {
                    $data['categories'] = 'Novelty';
                } else {
                    $data['categories'] = implode(',', $item['categories']);
                    //Проверяем наличие категории в справочнике, добавляем в случае необходимости
                    foreach ($item['categories'] as $cat) {
                        $cat = mb_strtolower(trim($cat));
                        if ((in_array($cat, $arCategories, true)) === false) {
                            $arCategories[] = $cat;
                            $model = new Categories();
                            $model->name = $cat;
                            if (!$model->save()) {
                                $this->arErrs[] = $model->getErrors();
                            }
                        }
                    }
                }

                $arData[] = $data;
            }

        }
        if (!empty($arData[0])) {
            Yii::$app->db->createCommand()->batchInsert('books',
                array_keys($arData[0]),
                $arData)->execute();
        }
        if (!empty($this->arErrs)) {
            //send mail
            var_dump($this->arErrs);
        }

    }


}
