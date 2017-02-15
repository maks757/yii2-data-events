<?php

namespace maks757\eventsdata\entities;

use maks757\imagable\Imagable;
use maks757\language\entities\Language;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "yii2_data_event".
 *
 * @property integer $id
 * @property string $image
 * @property integer $date
 * @property integer $author
 *
 * @property Yii2DataEventGallery[] $yii2DataEventGalleries
 * @property Yii2DataEventImage[] $yii2DataEventImages
 * @property Yii2DataEventText[] $yii2DataEventTexts
 * @property Yii2DataEventTranslation[] $yii2DataEventTranslations
 */
class Yii2DataEvent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['date', 'author'], 'integer'],
            [['image'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Image',
            'date' => 'Date',
            'author' => 'Author',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGalleries()
    {
        return $this->hasMany(Yii2DataEventGallery::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(Yii2DataEventImage::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTexts()
    {
        return $this->hasMany(Yii2DataEventText::className(), ['event_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Yii2DataEventTranslation::className(), ['event_id' => 'id']);
    }

    /**
     * @return Yii2DataEventTranslation
     */
    public function getTranslation()
    {
        return Yii2DataEventTranslation::findOne(['event_id' => $this->id, 'language_id' => Language::getDefault()->id]);
    }

    public function getImage(){
        if(!empty($this->image)) {
            /**@var Imagable $imagine */
            $imagine = \Yii::$app->event;
            $imagePath = $imagine->getOriginal('event', $this->image);
            $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
            return str_replace($aliasPath, '', $imagePath);
        } else {
            return '';
        }
    }

    public function getField($language_id)
    {
        $rows = [];
        foreach (Yii2DataEventText::findAll(['event_id' => $this->id]) as $text){
            $rows[] = ($text->toArray() + $text->getTranslation($language_id)->toArray() + ['key' => 'text']);
        }
        foreach (Yii2DataEventImage::findAll(['event_id' => $this->id]) as $image){
            $image->image = $image->getImage();
            $rows[] = ($image->toArray() + ['key' => 'image']);
        }
        foreach (Yii2DataEventGallery::findAll(['event_id' => $this->id]) as $gallery){
            $rows[] = ($gallery->toArray() + ['images' => $gallery->getImages()] + ['key' => 'slider']);
        }
        ArrayHelper::multisort($rows, 'position');
        return $rows;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataEvent
     */
    public function getNext() {
        $next = $this->find()->where(['>', 'id', $this->id])->one();
        return $next;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataEvent
     */
    public function getPrev() {
        $prev = $this->find()->where(['<', 'id', $this->id])->orderBy('id desc')->one();
        return $prev;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord|Yii2DataEvent
     */
    public function getFirst() {
        $prev = $this->find()->orderBy(['id' => SORT_ASC])->one();
        return $prev;
    }

    public static function fieldsPosition($block, $type, $block_id){
        if(!empty($block) && !empty($type) && !empty($block_id)) {
            switch ($block) {
                case 'image': {
                    $field = Yii2DataEventImage::findOne($block_id);
                    break;
                }
                case 'slider': {
                    $field = Yii2DataEventGallery::findOne($block_id);
                    break;
                }
                case 'text': {
                    $field = Yii2DataEventText::findOne($block_id);
                    break;
                }
            }

            switch ($type) {
                case 'up': {
                    if ($field->position > 0)
                        $field->position = ($field->position - 1);
                    break;
                }
                case 'down': {
                    $field->position = ($field->position + 1);
                    break;
                }
            }
            if(!empty($field))
                $field->save();
        }
    }

    public function create($post, $image)
    {
        if(!empty($post)){
            $this->load($post);
            $this->date = !empty($this->date) ? strtotime($this->date) : time();
            if(!empty($image))
                $this->image = $image;
            $this->save();
        }
    }
}
