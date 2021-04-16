<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m210415_163327_apples
 */
class m210415_163327_apples extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp() {

    $this->createTable("apple", [
      "id" => Schema::TYPE_PK,
      "color" => Schema::TYPE_TEXT,
      "date_created" => Schema::TYPE_DATETIME,
      "date_dropped" => Schema::TYPE_DATETIME,
      "status" => Schema::TYPE_TINYINT,
      "balance" => Schema::TYPE_DOUBLE,
    ]);

  }

  /**
   * {@inheritdoc}
   */
  public function safeDown() {

    $this->dropTable("apple");

  }

}
