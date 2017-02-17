<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */

/**
 * @var $model \maks757\eventsdata\entities\Yii2DataEventText
 * @var $model_translation \maks757\eventsdata\entities\Yii2DataEventTextTranslation
 */

use maks757\language\entities\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<a href="<?= \yii\helpers\Url::toRoute(['/events/post/create', 'id' => $event_id, 'languageId' => $language_id]) ?>"
   class="btn btn-info">Назад к статье</a><br><br>
<?php $form = ActiveForm::begin() ?>
<?php $translations = ArrayHelper::index($model->translations, 'language.lang_id'); ?>
<?php /** @var $languages Language[] */ foreach ($languages as $language): ?>
    <a href="<?= Url::to([
        '/events/field/create-text',
        'id' => $model->id,
        'event_id' => $model->event_id,
        'languageId' => $language->id
    ]) ?>"
       class="btn btn-xs btn-<?= $translations[$language->lang_id] ? 'success' : 'danger' ?>">
        <?= $language->name ?>
    </a>
<?php endforeach ?>
<br><br>
<?= $form->field($model_translation, 'text')->widget(\dosamigos\ckeditor\CKEditor::className(), [
    'preset' => 'full',
    'options' => ['rows' => 20],
    'clientOptions' => [
        'extraPlugins' => 'iframe,font,uicolor,colordialog,colorbutton,flash,magicline,print',
        'filebrowserUploadUrl' => \yii\helpers\Url::toRoute(['/events/post/upload'], true)
    ]
])->label('Текст') ?>
    <?= \yii\bootstrap\Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>
<?php ActiveForm::end() ?>
