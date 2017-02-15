<?php

namespace maks757\eventsdata\entities;

use maks757\language\entities\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_event_text_translation".
 *
 * @property integer $id
 * @property integer $event_text_id
 * @property integer $language_id
 * @property string $text
 *
 * @property Yii2DataEventText $eventText
 * @property Language $language
 */
class Yii2DataEventTextTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event_text_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_text_id', 'language_id'], 'integer'],
            [['text'], 'string'],
            [['event_text_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataEventText::className(), 'targetAttribute' => ['event_text_id' => 'id']],
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
            'event_text_id' => 'Event Text ID',
            'language_id' => 'Language ID',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getText()
    {
        return $this->hasOne(Yii2DataEventText::className(), ['id' => 'event_text_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }
}
