<?php

namespace maks757\eventsdata\entities;

use maks757\language\entities\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_event_image_translation".
 *
 * @property integer $id
 * @property integer $event_image_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 *
 * @property Yii2DataEventImage $eventImage
 * @property Language $language
 */
class Yii2DataEventImageTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event_image_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_image_id', 'language_id'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['event_image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataEventImage::className(), 'targetAttribute' => ['event_image_id' => 'id']],
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
            'event_image_id' => 'Event Image ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Yii2DataEventImage::className(), ['id' => 'event_image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
