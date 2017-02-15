<?php

namespace maks757\eventsdata\entities;

use maks757\egallery\entities\Gallery;
use maks757\imagable\Imagable;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "yii2_data_event_gallery".
 *
 * @property integer $id
 * @property string $position
 * @property integer $event_id
 *
 * @property Yii2DataEvent $event
 * @property Yii2DataEventGalleryTranslation[] $yii2DataEventGalleryTranslations
 */
class Yii2DataEventGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'yii2_data_event_gallery';
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
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Yii2DataEventGalleryTranslation::className(), ['event_gallery_id' => 'id']);
    }

    public function getImages(){
        $images = [];
        $galleries = Gallery::findAll(['key' => md5(self::className()), 'object_id' => $this->id]);
        ArrayHelper::multisort($galleries, 'position');
        foreach ($galleries as $gallery){
            /**@var Imagable $imagine */
            $imagine = \Yii::$app->egallery;
            $imagePath = $imagine->get('egallery', 'origin', $gallery->image);
            $aliasPath = FileHelper::normalizePath(Yii::getAlias('@frontend/web'));
            $images[] = [
                'image' => str_replace('\\', '/', str_replace($aliasPath,'',$imagePath)),
                'name' => $gallery->title
            ];
        }
        return $images;
    }
}
