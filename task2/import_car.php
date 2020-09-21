<?php

use PHPUnit\Framework\TestCase;
use \Bitrix\Main\Loader;
use Me\ImportCar\{Import, Csv, Fields};

final class ImportCarTest extends TestCase
{
  private $csv = null;

  /**
   * @before
   */
  public function includeModule()
  {
    Loader::includeModule('me.importcar');
    $this->csv = new Csv('upload/import_car/best.csv');
  }

  /**
   * @test
   */
  public function transpose()
  {
    $tableTranspose = $this->csv->transpose();

    $this->assertEquals(
      [
        $tableTranspose[0]['Название'],
        $tableTranspose[1]['Название'],
        $tableTranspose[2]['Название'],
      ],
      [
        'ELANTRA AD 1,6 AT VIN 000840',
        'GENESIS G90 3.8 AWD VIN 000058',
        'GRAND SANTA FE 2.2D  VIN 000282'
      ]
    );
  }

  /**
   * @test
   */
  public function getVin()
  {
    $csv = $this->createStub(Csv::class);
    $reflectionCsv = new ReflectionClass($csv);
    $reflectionMethod = $reflectionCsv->getMethod('getVin');
    $reflectionMethod->setAccessible(true);

    $this->assertEquals(
      [
        $reflectionMethod->invoke($csv, 'ELANTRA AD 1,6 AT VIN 000840'),
        $reflectionMethod->invoke($csv, 'GENESIS G90 3.8 AWD VIN 000058'),
        $reflectionMethod->invoke($csv, 'GRAND SANTA FE 2.2D  VIN 000282'),
      ],
      [
        '000840',
        '000058',
        '000282'
      ]
    );
  }

  /**
   * @test
   */
  public function getRowTranspose()
  {
    $data = [
      ['Название', 'ELANTRA AD 1,6 AT VIN 000840', 'GRAND SANTA FE 2.2D  VIN 000282'],
      ['Год', '2017', '2016'],
      ['Объем двигателя', '1,6', '3,8'],
      ['Объем двигателя', '1,6', '3,8'],
      ['', 'Прочее1', '3,8'],
      ['', 'Прочее2', '3,8'],
      ['', 'Прочее3', '3,8'],
    ];
    $indexField = 1;

    $reflectionCsv = new ReflectionClass($this->csv);
    $reflectionDevBrandName = $reflectionCsv->getMethod('transposeColumnToRow');
    $reflectionDevBrandName->setAccessible(true);
    $row = $reflectionDevBrandName->invoke($this->csv, $data, $indexField);

    $this->assertEquals(
      $row,
      [
        'Название' => 'ELANTRA AD 1,6 AT VIN 000840',
        'Год' => '2017',
        'Объем двигателя' => '1,6',
        'Прочее' => ['Прочее1', 'Прочее2', 'Прочее3'],
        'Картинки' => [
          '000840-1.JPG',
          '000840-2.JPG',
          '000840-3.JPG',
          '000840-4.JPG',
          '000840-5.JPG',
          '000840-6.JPG',
          '000840-7.JPG',
        ]
      ]
    );
  }

  /**
   * @test
   */
  public function getFiles()
  {
    $reflectionCsv = new ReflectionClass($this->csv);
    $reflectionMethod = $reflectionCsv->getMethod('getFiles');
    $reflectionMethod->setAccessible(true);

    $this->assertEquals(count($reflectionMethod->invoke($this->csv)), 47);
  }

  /**
   * @test
   */
  public function getImagesCar()
  {

    $name = ['ELANTRA AD 1,6 AT VIN 000840', 'GRAND SANTA FE 2.2D  VIN 000282'];
    $images = [
      '000840-1.jpg',
      '000840-1.jpg',
      '000282-1.jpg',
      '000840-1.png',
      '000855-1.jpg',
      '000282-1.gif',
      '000840-1.gif',
    ];

    $this->assertEquals(
      $this->csv->findImagesInName($name[0], $images),
      ['000840-1.jpg', '000840-1.jpg', '000840-1.png', '000840-1.gif',]
    );
    $this->assertEquals(
      $this->csv->findImagesInName($name[1], $images),
      ['000282-1.jpg', '000282-1.gif']
    );
  }

  /**
   * @test
   * @dataProvider additionProvider
   * @param bool $expected
   * @param string $file
   */
  public function validateImg(bool $expected, string $file)
  {
    $csv = $this->createStub(Csv::class);
    $reflectionCsv = new ReflectionClass($csv);
    $reflectionDevBrandName = $reflectionCsv->getMethod('validateImg');
    $reflectionDevBrandName->setAccessible(true);

    $this->assertEquals(
      $reflectionDevBrandName->invoke($csv, $file),
      $expected
    );
  }

  public function additionProvider()
  {
    return [
      [true, 'file1.jpg'],
      [true, 'file2.JPG'],
      [false, 'file3.scv'],
      [true, 'file4.png']
    ];
  }

  /**
   * @test
   */
  public function putElement()
  {
    $tableTranspose = $this->csv->transpose();
    $import = new Import();

    $result = $import->addElement(
      $tableTranspose[1]
    );
    $this->assertTrue($result > 59592);
  }

  /**
   * @test
   */
  function addFullTable(){
    $import = new Import($this->csv);
    $ids = $import->addFullTable();

    $this->assertTrue(count($ids) > 1);
  }

  /**
   * @test
   */
  function getFields()
  {
    $this->assertEquals(
      Fields::getList()['IBLOCK_ID'],
      172
    );
  }

  /**
   * @test
   */
  function convertNameField()
  {
    $import = new Import();
    $row = [
      'Название' => 'test',
      'Год' => '1900',
      'Картинки' => [
        'img' => 1
      ]
    ];

    $rowModification = $import->convertNameField($row);

    $this->assertEquals(
      $rowModification,
      [
        'NAME' => 'test',
        'PROPS' => [
          215305 => '1900',
          215315 => [
            'img' => 1
          ]
        ]
      ]
    );
  }
}