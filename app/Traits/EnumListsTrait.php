<?php
namespace App\Traits;

trait EnumListsTrait
{
  public static function getEnumList($enumClass)
  {
    $lists = [];
    foreach ($enumClass::cases() as $list) {
      $lists[] = [
        'value' => $list->value,
        'label' => $list->label(),
        'color' => method_exists($list, 'color') ? $list->color() : 'default'
      ];
    }
    return $lists;
  }

  public static function getEnumValue($enumClass, $label)
  {
    foreach ($enumClass::cases() as $list) {
      if ($list->label() === (string)$label) {
        return $list->value;
      }
    }
    return null;
  }
 
  public static function getEnumLabel($enumClass, $value)
  {
    foreach ($enumClass::cases() as $list) {
      if ($list->value === (string)$value) {
        return $list->label();
      }
    }
    return null;
  }

  public static function getEnumColor($enumClass, $value)
  {
    foreach ($enumClass::cases() as $list) {
      if ($list->value === (string)$value) {
        return method_exists($list, 'color') ? $list->color() : 'default';
      }
    }
    return null;
  }

  public static function getEnumLabelList($enumClass){
    return array_map(fn($case) => $case->label(), $enumClass::cases());
  }

}
