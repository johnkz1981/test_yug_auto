<?php

namespace Me\ImportCar;

use \Bitrix\Iblock\Iblock;
use \Bitrix\Main\Loader;
use CIBlockElement;

class Import
{
  private $user;
  private $csv;

  public function __construct(Csv $csv)
  {
    Loader::includeModule('iblock');
    global $USER;
    $this->user = $USER;
    $this->csv = $csv;
  }

  /**
   * @param array $row
   * @return int
   */
  public function addElement(array $row)
  {
    $el = new CIBlockElement;
    $rowModification = $this->convertNameField($row);

    $arLoadProductArray = array(
      "MODIFIED_BY" => $this->user->GetID(), // элемент изменен текущим пользователем
      "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
      "IBLOCK_ID" => Fields::IBLOCK_ID,
      "PROPERTY_VALUES" => $rowModification['PROPS'],
      "NAME" => $rowModification['NAME'],
      "ACTIVE" => "Y",
    );

    return $el->Add($arLoadProductArray);
  }

  /**
   * @return array $ids
   */
  public function addFullTable()
  {
    $ids = [];
    $tableTranspose = $this->csv->transpose();

    foreach ($tableTranspose as $row) {
      $ids[] = $this->addElement(
        $row
      );
    }
    return $ids;
  }

  /**
   * @param array $row
   * @return array
   */
  public function convertNameField(array $row)
  {
    $rowModification = [];

    foreach (Fields::getList() as $key => $field) {
      if ($row[$field['TITLE']]) {
        if ($field['ID'] === 'NAME') {
          $rowModification[$field['ID']] = $row[$field['TITLE']];
        } else {
          $rowModification['PROPS'][$field['ID']] = $row[$field['TITLE']];
        }
      }
    }
    return $rowModification;
  }
}