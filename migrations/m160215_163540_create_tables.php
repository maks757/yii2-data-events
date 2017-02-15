<?php

use yii\db\Schema;
use yii\db\Migration;

class m160215_163540_create_tables extends Migration {

    public function up() {
        $this->createTable('yii2_data_event', [
            'id' => $this->primaryKey(),
            'image' => $this->string(100)->notNull(),
            'date' => $this->integer(),
            'author' => $this->integer(),
        ]);

        $this->createTable('yii2_data_event_translation', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer(),
            'language_id' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_event_language_fk',
            'yii2_data_event_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_event_yii2_data_event_translation_fk',
            'yii2_data_event_translation', 'event_id',
            'yii2_data_event', 'id',
            'CASCADE', 'CASCADE');

        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('yii2_data_event_gallery', [
            'id' => $this->primaryKey(),
            'position' => $this->integer()->notNull(),
            'event_id' => $this->integer(),
        ]);

        $this->addForeignKey('yii2_data_event_gallery_yii2_data_event_fk',
            'yii2_data_event_gallery', 'event_id',
            'yii2_data_event', 'id',
            'CASCADE', 'CASCADE');

        $this->createTable('yii2_data_event_gallery_translation', [
            'id' => $this->primaryKey(),
            'event_gallery_id' => $this->integer(),
            'language_id' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_event_gallery_translation_language_fk',
            'yii2_data_event_gallery_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_event_gallery_t_yii2_data_event_gallery_fk',
            'yii2_data_event_gallery_translation', 'event_gallery_id',
            'yii2_data_event_gallery', 'id',
            'CASCADE', 'CASCADE');


        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('yii2_data_event_image', [
            'id' => $this->primaryKey(),
            'image' => $this->string(100)->notNull(),
            'position' => $this->integer()->notNull(),
            'event_id' => $this->integer(),
        ]);

        $this->addForeignKey('yii2_data_event_image_yii2_data_event_fk',
            'yii2_data_event_image', 'event_id',
            'yii2_data_event', 'id',
            'CASCADE', 'CASCADE');

        $this->createTable('yii2_data_event_image_translation', [
            'id' => $this->primaryKey(),
            'event_image_id' => $this->integer(),
            'language_id' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_event_image_translation_language_fk',
            'yii2_data_event_image_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_event_image_t_yii2_data_event_image_fk',
            'yii2_data_event_image_translation', 'event_image_id',
            'yii2_data_event_image', 'id',
            'CASCADE', 'CASCADE');

        ///////////////////////////////////////////////////////////////////////////

        $this->createTable('yii2_data_event_text', [
            'id' => $this->primaryKey(),
            'position' => $this->integer()->notNull(),
            'event_id' => $this->integer(),
        ]);

        $this->addForeignKey('yii2_data_event_text_yii2_data_event_fk',
            'yii2_data_event_text', 'event_id',
            'yii2_data_event', 'id',
            'CASCADE', 'CASCADE');

        $this->createTable('yii2_data_event_text_translation', [
            'id' => $this->primaryKey(),
            'event_text_id' => $this->integer(),
            'language_id' => $this->integer(),
            'text' => $this->text(),
        ]);

        $this->addForeignKey('yii2_data_event_text_translation_language_fk',
            'yii2_data_event_text_translation', 'language_id',
            'language', 'id',
            'CASCADE', 'CASCADE');

        $this->addForeignKey('yii2_data_event_text_t_yii2_data_event_text_fk',
            'yii2_data_event_text_translation', 'event_text_id',
            'yii2_data_event_text', 'id',
            'CASCADE', 'CASCADE');

    }

    public function down() {
        return true;
    }

    /*
      // Use safeUp/safeDown to run migration code within a transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}
