<?php

namespace maks757\eventsdata\entities;

use maks757\language\entities\Language;
use Yii;

/**
 * This is the model class for table "yii2_data_event_translation".
 *
 * @property integer $id
 * @property integer $event_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 *
 * @property Yii2DataEvent $event
 * @property Language $language
 */
class Yii2DataEventTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'language_id'], 'integer'],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Yii2DataEvent::className(), 'targetAttribute' => ['event_id' => 'id']],
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
            'event_id' => 'Event ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
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
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'language_id']);
    }

    public function create($post, $id)
    {
        if(!empty($post) && !empty($id)){
            $this->load($post);
            $this->event_id = $id;
            $this->save();
        }
    }
}
