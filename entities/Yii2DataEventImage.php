<?php

namespace maks757\eventsdata\entities;

use maks757\imagable\Imagable;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "yii2_data_event_image".
 *
 * @property integer $id
 * @property string $image
 * @property string $position
 * @property integer $event_id
 *
 * @property Yii2DataEvent $event
 * @property Yii2DataEventImageTranslation[] $yii2DataEventImageTranslations
 */
class Yii2DataEventImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'position'], 'required'],
            [['event_id', 'position'], 'integer'],
            [['image'], 'string', 'max' => 100],
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
            'image' => 'Image',
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
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Yii2DataEventImageTranslation::className(), ['event_image_id' => 'id']);
    }

    public function getImage(){
        /**@var Imagable $imagine */
        $imagine = \Yii::$app->event;
        $imagePath = $imagine->getOriginal('images', $this->image);
        $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
        return str_replace($aliasPath,'',$imagePath);
    }
}
