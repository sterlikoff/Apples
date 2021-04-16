<?php


namespace common\models;


class Common
{

  const MYSQL_DATE_FORMAT = "Y-m-d H:i:s";
  const IN_DAY_SECONDS = 60 * 60 * 24;

  public static function leadingZero($value, $number) {

    for ($i = 1; $i++ <= $number - strlen($value);) {
      $value = "0" . $value;
    }

    return $value;
  }

  /**
   * @return string
   */
  public static function getColor() {
    $r = self::leadingZero(dechex(rand(0, 255)), 2);
    $g = self::leadingZero(dechex(rand(0, 255)), 2);
    $b = self::leadingZero(dechex(rand(0, 255)), 2);
    return "#{$r}{$g}{$b}";
  }

  /**
   * @param string $dateFrom
   * @param string|null $dateTo
   *
   * @return int
   */
  public static function getRandomUnixTime($dateFrom, $dateTo = null) {

    $timeFrom = strtotime($dateFrom);
    $timeTo = $dateTo ? strtotime($dateTo) : time();

    return rand($timeFrom, $timeTo);

  }

}