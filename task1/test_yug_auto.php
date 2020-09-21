<?php

use Bitrix\Main\Loader;
use PHPUnit\Framework\TestCase;
use Me\Catalog\{Test, DataTable};

final class testYugAuto extends TestCase
{
  /**
   * @before
   */
  public function includeModule()
  {
    Loader::IncludeModule("me.catalog");
  }

  /**
   * @test
   */
  public function getNameTable()
  {
    $result = Test::get();

    $this->assertEquals(
      $result,
      'me_flowers'
    );
  }

  /**
   * @test
   */
  public function getTableMap()
  {
    $result = DataTable::getMap();

    $this->assertEquals(
      [$result['ID']['title'], $result['TITLE']['title'], $result['QUANTITY']['title'], $result['PRICE']['title']],
      ['ID', 'Наименование', 'Количество', 'Цена']
    );
  }

  /**
   * @test
   */
  public function addRow()
  {
    $result = DataTable::add([
      'TITLE' => 'Роза',
      'QUANTITY' => 1,
      'PRICE' => 5,
    ]);

   $this->assertTrue($result->getId() > 0);
  }

  /**
   * @test
   */
  public function getList()
  {
    $result = DataTable::getList([
      'select' => [
        'ID', 'TITLE', 'QUANTITY', 'PRICE'
      ],
      'filter' => ['ID' => 1]
    ]);

    $this->assertEquals(
      $result->fetchAll()[0],
      ['ID' => 1, 'TITLE' => 'Роза', 'QUANTITY' => 1, 'PRICE' => 5]
    );
  }

  /**
   * @test
   */
  public function deleteRow()
  {
    $id = 3;
    $result = DataTable::delete($id);

    $this->assertTrue($result->isSuccess());
  }

  /**
   * @test
   */
  public function updateRow()
  {
    $id = 4;
    $title = 'Тюльпан';
    $quantity = 10;
    $price = 10;

    $objRow = DataTable::getByPrimary($id)->fetchObject();

    $objRow->setTitle($title);
    $objRow->setQuantity($quantity);
    $objRow->setPrice($price);

    $result = $objRow->save();

    $this->assertTrue($result->isSuccess());
  }

  /**
   * @test
   */
  public function getCountRows()
  {
    $countRows = DataTable::getCount();
    $this->assertTrue($countRows > 0 || $countRows === '0');
  }

  /**
   * @test
   */
  public function setFilter()
  {
    $rows = DataTable::getList(
      [
        'filter' => ['%TITLE' => 'роз']
      ]
    )->fetchAll();
    $this->assertTrue(!!count($rows));
  }

  /**
   * @test
   */
  public function copyInstallUninstallFile()
  {
    DeleteDirFilesEx('/bitrix/admin/me.catalog_edit.php');
    $this->assertFalse(is_file($_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin/me.catalog_edit.php'));

    CopyDirFiles(
      $_SERVER["DOCUMENT_ROOT"] . '/local/modules/me.catalog/install/me.catalog_edit.php',
      $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin/me.catalog_edit.php', true
    );
    $this->assertTrue(is_file($_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin/me.catalog_edit.php'));
  }
}