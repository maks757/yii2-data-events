<?php

namespace maks757\eventsdata\entities;

use maks757\language\entities\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_event_text".
 *
 * @property integer $id
 * @property string $position
 * @property integer $event_id
 *
 * @property Yii2DataEvent $event
 * @property array|Yii2DataEventTextTranslation|null|\yii\db\ActiveRecord $translation
 * @property Yii2DataEventTextTranslation[] $translations
 */
class Yii2DataEventText extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event_text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['position'], 'required'],
            [['event_id', 'position'], 'integer'],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataEvent::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'position' => 'Position',
            'event_id' => 'Event ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Yii2DataEvent::className(), ['id' => 'event_id']);
    }

    /**
     * @return array|Yii2DataEventTextTranslation|null|\yii\db\ActiveRecord
     */
    public function getTranslation($language_id = null)
    {
        $current = Yii2DataEventTextTranslation::find()->where(['event_text_id' => $this->id, 'language_id' => (!empty($language_id) ? $language_id : Language::getCurrent()->id)])->one();
        if(empty($current)){
            $current = Yii2DataEventTextTranslation::find()->where(['event_text_id' => $this->id])->one();
        }
        return $current;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Yii2DataEventTextTranslation::className(), ['event_text_id' => 'id']);
    }
}
