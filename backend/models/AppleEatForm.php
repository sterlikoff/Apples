<?php

namespace backend\models;

use common\models\Apple;
use yii\base\Model;

class AppleEatForm extends Model
{

  public $percent;

  /** @var Apple */
  protected $apple;

  /**
   * AppleEatForm constructor.
   *
   * @param array $config
   * @param Apple $apple
   */
  public function __construct($config, $apple) {
    $this->apple = $apple;
    parent::__construct($config);
  }

  public function rules() {
    return [
      ["percent", "required"],
      ["percent", "number", "min" => 1, "max" => 100],
      [["percent"], "validatePercent"],
    ];
  }

  public function validatePercent() {

    if ($this->apple->balance - $this->percent < 0) {
      $this->addError("percent", "Not can eat more than the rest.");
      return false;
    }

    return true;
  }

  /**
   * @return false
   * @throws Exception
   */
  public function eat() {
    if (!$this->validate()) return false;
    return $this->apple->eat($this->percent);
  }

}