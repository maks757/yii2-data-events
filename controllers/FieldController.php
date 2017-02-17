<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

namespace maks757\eventsdata\controllers;

use maks757\eventsdata\EventModule;
use maks757\eventsdata\components\UploadImages;
use maks757\eventsdata\entities\Yii2DataEvent;
use maks757\eventsdata\entities\Yii2DataEventGallery;
use maks757\eventsdata\entities\Yii2DataEventGalleryTranslation;
use maks757\eventsdata\entities\Yii2DataEventImage;
use maks757\eventsdata\entities\Yii2DataEventImageTranslation;
use maks757\eventsdata\entities\Yii2DataEventText;
use maks757\eventsdata\entities\Yii2DataEventTextTranslation;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\UploadedFile;

class FieldController extends Controller
{
    //<editor-fold desc="Text field">
    public function actionCreateText($id = null, $languageId = null, $event_id = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataEventText();
        $model_translation = new Yii2DataEventTextTranslation();
        //Languages
        /** @var $module EventModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('event_id')))
            $event_id = $request->post('event_id');

        if($model_data = Yii2DataEventText::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataEventTextTranslation::findOne(['event_text_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if($request->isPost){
            $fields = Yii2DataEvent::findOne($event_id)->getField($languageId);

            $model->load($request->post());
            $model->event_id = $event_id;
            if(!is_integer($model->position))
                $model->position = ($fields[count($fields) - 1]['position'] + 1);
            $model->save();

            $model_translation->load($request->post());
            $model_translation->event_text_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();

            return $this->redirect(Url::toRoute(['/events/post/create', 'id' => $event_id, 'languageId' => $languageId]));
        }

        return $this->render('create_text', [
            'model' => $model,
            'model_translation' => $model_translation,
            'event_id' => $event_id,
            'languages' => $languages,
            'language_id' => $languageId,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionTextPosition($id, $type)
    {
        $field = Yii2DataEventText::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionTextDelete($id)
    {
        Yii2DataEventText::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Image">
    public function actionCreateImage($id = null, $event_id = null, $languageId = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataEventImage();
        $model_translation = new Yii2DataEventImageTranslation();
        //Languages
        /** @var $module EventModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);
        //end
        $model_image = new UploadImages();


        if(!empty($request->post('id')))
            $id = $request->post('id');

        if($model_data = Yii2DataEventImage::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataEventImageTranslation::findOne(['event_image_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if($request->isPost){
            $fields = Yii2DataEvent::findOne($event_id)->getField($languageId);
            $model_image->imageFile = UploadedFile::getInstance($model_image, 'imageFile');

            $model->load($request->post());
            if($image = $model_image->upload())
                $model->image = $image;
            $model->event_id = $event_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
            $model->save();

            $model_translation->load($request->post());
            $model_translation->event_image_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();

            return $this->redirect(Url::toRoute(['/events/field/create-image', 'id' => $model->id, 'event_id' => $event_id, 'languageId' => $languageId]));
        }

        return $this->render('create_image', [
            'model' => $model,
            'model_translation' => $model_translation,
            'languages' => $languages,
            'event_id' => $event_id,
            'model_image' => $model_image,
            'language_id' => $languageId,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionImagePosition($id, $type)
    {
        $field = Yii2DataEventImage::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionImageDelete($id)
    {
        Yii2DataEventImage::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>

    //<editor-fold desc="Slider">
    public function actionCreateSlider($id = null, $event_id = null, $languageId = null)
    {
        $request = \Yii::$app->request;
        $model = new Yii2DataEventGallery();
        $model_translation = new Yii2DataEventGalleryTranslation();
        //Languages
        /** @var $module EventModule */
        $module = $this->module;
        $languages = \Yii::createObject($module->language_class);
        $languages = $languages::findAll($module->language_where);
        //end

        if(!empty($request->post('id')))
            $id = $request->post('id');

        if(!empty($request->post('event_id')))
            $event_id = $request->post('event_id');

        if($model_data = Yii2DataEventGallery::findOne($id)){
            $model = $model_data;
            if($model_translation_data = Yii2DataEventGalleryTranslation::findOne(['event_gallery_id' => $model->id, 'language_id' => $languageId])){
                $model_translation = $model_translation_data;
            }
        }

        if($request->isPost){
            $fields = Yii2DataEvent::findOne($event_id)->getField($languageId);
            $model->load($request->post());
            $model->event_id = $event_id;
            if(!is_integer($model->position))
                $model->position = $fields[count($fields) - 1]['position'] + 1;
            $model->save();

            $model_translation->load($request->post());
            $model_translation->event_gallery_id = $model->id;
            $model_translation->language_id = $languageId;
            $model_translation->save();

            return $this->redirect(Url::toRoute(['/events/field/create-slider', 'id' => $model->id, 'event_id' => $event_id, 'languageId' => $languageId]));
        }

        return $this->render('create_slider', [
            'model' => $model,
            'model_translation' => $model_translation,
            'event_id' => $event_id,
            'languages' => $languages,
            'language_id' => $languageId,
            'language_field_name' => $module->language_field
        ]);
    }

    public function actionSliderPosition($id, $type)
    {
        $field = Yii2DataEventGallery::findOne($id);
        switch ($type){
            case 'up':{
                if($field->position > 0)
                    $field->position = ($field->position - 1);
                break;
            }
            case 'down':{
                $field->position = ((integer)$field->position + 1);
                break;
            }
        }
        $field->save();

        return $this->redirect(\Yii::$app->request->referrer);
    }

    public function actionSliderDelete($id)
    {
        Yii2DataEventGallery::findOne($id)->delete();
        return $this->redirect(\Yii::$app->request->referrer);
    }
    //</editor-fold>
}