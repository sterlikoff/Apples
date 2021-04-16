<?php

namespace common\models;

use Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "apple".
 *
 * @property int $id
 * @property string|null $color
 * @property string|null $date_created
 * @property string|null $date_dropped
 * @property int|null $status
 * @property float|null $balance
 */
class Apple extends ActiveRecord
{

  const STATUS_NEW = 0;
  const STATUS_DROPPED = 1;
  const STATUS_EATEN = 2;
  const STATUS_CORRUPTED = 3;

  const DAYS_CORRUPTED = 5;

  public static $availableInitStatuses = [
    self::STATUS_NEW,
    self::STATUS_DROPPED,
  ];

  public static $statusTitles = [
    self::STATUS_NEW => "Висит на дереве",
    self::STATUS_DROPPED => "Упало",
    self::STATUS_CORRUPTED => "Испортилось",
    self::STATUS_EATEN => "Съедено",
  ];

  /**
   * {@inheritdoc}
   */
  public static function tableName() {
    return 'apple';
  }

  /**
   * {@inheritdoc}
   */
  public function rules() {
    return [
      [['color'], 'string'],
      [['balance'], 'default', 'value' => 100],
      [['status'], 'default', 'value' => self::STATUS_NEW],
      [['date_created', 'date_dropped'], 'safe'],
      [['status'], 'integer'],
      [['balance'], 'number'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels() {
    return [
      'id' => 'ID',
      'color' => 'Color',
      'date_created' => 'Date Created',
      'date_dropped' => 'Date Dropped',
      'status' => 'Status',
      'balance' => 'Balance',
    ];
  }

  /**
   * @throws Exception
   */
  public function eat($value) {

    switch ($this->status) {
      case self::STATUS_DROPPED;

        if ($this->balance - $value < 0) {
          throw new Exception("Not can eat more than the rest.");
        }

        $this->balance -= $value;
        return $this->save(false);

    }

    throw new Exception("This apple not dropped yet.");

  }

  /**
   * @return bool
   * @throws Exception
   */
  public function drop() {

    switch ($this->status) {

      case self::STATUS_NEW;

        $this->status = self::STATUS_DROPPED;
        $this->date_dropped = date(Common::MYSQL_DATE_FORMAT);
        return $this->save();

    }

    throw new Exception("Only apples with status \"NEW\" can dropped.");

  }

  /**
   * @param bool $insert
   * @return bool
   */
  public function beforeSave($insert) {

    if ($this->isNewRecord && !$this->date_created) {
      $this->date_created = date(Common::MYSQL_DATE_FORMAT);
    }

    if (!$this->color) {
      $this->color = Common::getColor();
    }

    if ($this->balance <= 0) {
      $this->status = self::STATUS_EATEN;
    }

    if ($this->status == self::STATUS_DROPPED) {
      if (time() - strtotime($this->date_dropped) > self::DAYS_CORRUPTED * Common::IN_DAY_SECONDS) {
        $this->status = self::STATUS_CORRUPTED;
      }
    }

    return parent::beforeSave($insert);

  }

  /**
   * @param int $count
   * @param string $dateFrom
   */
  public static function generate($count = 0, $dateFrom = "01.01.2021") {

    if (!$count) {
      $count = rand(1, 50);
    }

    for ($i = 0; $i++ <= $count;) {

      $apple = new static();
      $apple->status = self::$availableInitStatuses[rand(0, count(self::$availableInitStatuses) - 1)];
      $apple->date_created = date(Common::MYSQL_DATE_FORMAT, Common::getRandomUnixTime($dateFrom));

      switch ($apple->status) {

        case self::STATUS_DROPPED;
          $apple->date_dropped = date(
            Common::MYSQL_DATE_FORMAT,
            strtotime($apple->date_created) + rand(1, 10) * Common::IN_DAY_SECONDS
          );

          break;

      }

      $apple->save();

    }

  }

  public static function clear() {
    static::deleteAll();
  }

}
