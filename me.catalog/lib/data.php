<?php
namespace Me\Catalog;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;


/**
 * Class DataTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> TITLE string mandatory
 * <li> QUANTITY int mandatory
 * <li> PRICE float mandatory
 * <li> SORT int optional default 500
 * <li> CREATED datetime mandatory default 'CURRENT_TIMESTAMP'
 * </ul>
 *
 * @package \Me\Data
 **/

class DataTable extends Entity\DataManager
{
  /**
   * Returns DB table name for entity.
   *
   * @return string
   */
  public static function getTableName()
  {
    return 'me_flowers';
  }

  /**
   * Returns entity map definition.
   *
   * @return array
   */
  public static function getMap()
  {
    return array(
      'ID' => array(
        'data_type' => 'integer',
        'primary' => true,
        'autocomplete' => true,
        'title' => Loc::getMessage('DATA_ENTITY_ID_FIELD'),
      ),
      'TITLE' => array(
        'data_type' => 'text',
        'required' => true,
        'title' => Loc::getMessage('DATA_ENTITY_TITLE_FIELD'),
      ),
      'QUANTITY' => array(
        'data_type' => 'integer',
        'required' => true,
        'title' => Loc::getMessage('DATA_ENTITY_QUANTITY_FIELD'),
      ),
      'PRICE' => array(
        'data_type' => 'float',
        'required' => true,
        'title' => Loc::getMessage('DATA_ENTITY_PRICE_FIELD'),
      ),
      'SORT' => array(
        'data_type' => 'integer',
        'title' => Loc::getMessage('DATA_ENTITY_SORT_FIELD'),
      ),
      'CREATED' => array(
        'data_type' => 'datetime',
        'title' => Loc::getMessage('DATA_ENTITY_CREATED_FIELD'),
      ),
    );
  }
}