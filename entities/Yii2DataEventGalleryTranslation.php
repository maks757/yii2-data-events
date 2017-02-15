<?php

namespace maks757\eventsdata\entities;

use maks757\language\entities\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_event_gallery_translation".
 *
 * @property integer $id
 * @property integer $event_gallery_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 *
 * @property Yii2DataEventGallery $eventGallery
 * @property Language $language
 */
class Yii2DataEventGalleryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event_gallery_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_gallery_id', 'language_id'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['event_gallery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataEventGallery::className(), 'targetAttribute' => ['event_gallery_id' => 'id']],
            [['language_id'], 'exist', 'skipOnError' => true, 'targetClass' => Language::className(), 'targetAttribute' => ['language_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_gallery_id' => 'Event Gallery ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGallery()
    {
        return $this->hasOne(Yii2DataEventGallery::className(), ['id' => 'event_gallery_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
