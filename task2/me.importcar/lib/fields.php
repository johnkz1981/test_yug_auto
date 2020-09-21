<?php

namespace Me\ImportCar;

class Fields
{
  const IBLOCK_ID = 172;
  const NAME = ['ID' => 'NAME', 'NAME' => 'NAME', 'TITLE' => 'Название'];
  const PROPERTY_YEAR = ['ID' => 215305, 'NAME' => 'YEAR', 'TITLE' => 'Год'];
  const PROPERTY_VOLUME = ['ID' => 215306, 'NAME' => 'VOLUME', 'TITLE' => 'Объем двигателя'];
  const PROPERTY_POWER = ['ID' => 215307, 'NAME' => 'POWER', 'TITLE' => 'Мощность, л.с.'];
  const PROPERTY_TYPE = ['ID' => 215308, 'NAME' => 'TYPE', 'TITLE' => 'Тип двигателя'];
  const PROPERTY_KPP = ['ID' => 215309, 'NAME' => 'KPP', 'TITLE' => 'КПП'];
  const PROPERTY_COLOR = ['ID' => 215310, 'NAME' => 'COLOR ', 'TITLE' => 'Цвет'];
  const PRICE = ['ID' => 0, 'NAME' => 'PRICE', 'TITLE' => 'Цена'];
  const PRICE_DISCOUNT = ['ID' => 0, 'NAME' => 'DISCOUNT', 'TITLE' => 'Цена с выгодой'];
  const PROPERTY_LOCATION = ['ID' => 215311, 'NAME' => 'LOCATION', 'TITLE' => 'Дилерский центр'];
  const PROPERTY_MANAGER = ['ID' => 215312, 'NAME' => 'MANAGER', 'TITLE' => 'Менеджер'];
  const PROPERTY_EQUIPMENT = ['ID' => 215313, 'NAME' => 'EQUIPMENT', 'TITLE' => 'Название комплектации'];
  const PROPERTY_OTHER = ['ID' => 215314, 'NAME' => 'OTHER', 'TITLE' => 'Прочее'];
  const PROPERTY_GALLERY = ['ID' => 215315, 'NAME' => 'GALLERY', 'TITLE' => 'Картинки'];

  /**
   * @return array
   */
  static function getList(){
    $reflection = new \ReflectionClass(__CLASS__);
    return $reflection->getConstants();
  }
}