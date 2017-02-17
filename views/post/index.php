<?php
/**
 * @author Maxim Cherednyk <maks757q@gmail.com, +380639960375>
 */
use maks757\eventsdata\entities\Yii2DataEvent;
use maks757\language\entities\Language;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


?>

<table class="table table-bordered">
    <thead>
    <tr>
        <th class="col-sm-1">Ид</th>
        <th class="col-sm-2">Название</th>
        <th class="col-sm-2">Описание</th>
        <th class="col-sm-2">Изображение</th>
        <th class="col-sm-1">Дата</th>
        <th class="col-sm-2">Языки</th>
        <th class="col-sm-2"></th>
    </tr>
    </thead>
    <tbody>
    <?php /** @var $events Yii2DataEvent[] */ foreach ($events as $event): ?>
        <tr style="height: 70px;">
            <th><?= $event->id ?></th>
            <td><?= $event->getTranslation()->name ?></td>
            <td><?= $event->getTranslation()->description ?></td>
            <td><img src="<?= $event->getImage() ?>" alt="" width="100"></td>
            <td><?= date('d-m-Y', $event->date) ?></td>
            <td>
                <?php $translations = ArrayHelper::index($event->translations, 'language.'.$language_field_name); ?>
                <?php /** @var $languages Language[] */ foreach ($languages as $language): ?>
                    <a href="<?= Url::to([
                        '/events/post/create',
                        'id' => $event->id,
                        'languageId' => $language->id
                    ]) ?>"
                       class="btn btn-xs btn-<?= $translations[$language->$language_field_name] ? 'success' : 'danger' ?>">
                        <?= $language->$language_field_name ?>
                    </a>
                <?php endforeach ?>
            </td>
            <td>
                <a href="<?= \yii\helpers\Url::toRoute(['/events/post/create', 'id' => $event->id, 'languageId' => $language_default])?>"
                    class="btn btn-info btn-xs">Изменить</a>
                <a href="<?= \yii\helpers\Url::toRoute(['/events/post/delete', 'id' => $event->id])?>"
                    class="btn btn-danger btn-xs">Удалить</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="<?= \yii\helpers\Url::toRoute(['/events/post/create'])?>"
    class="btn btn-success pull-right">Добавить статью</a>