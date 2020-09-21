<?php

namespace Me\ImportCar;

use Bitrix\Main\{Application, IO};

class Csv
{
  private $fileEntity = null;
  private $pictures = null;

  public function __construct($file)
  {
    $fileEntity = new IO\File(Application::getDocumentRoot() . "/" . $file);
    if ($fileEntity->isExists()) {
      if($fileEntity->getSize()){
        $this->fileEntity = $fileEntity;
        $this->pictures = $this->getFiles();
      }else{
        throw new \Exception('Файл пуст');
      }
    } else {
      throw new IO\FileNotFoundException($fileEntity->getPath());
    }
  }

  private function utf8FopenRead()
  {
    $fc = iconv('cp1251', 'utf8', $this->fileEntity->getContents());
    $handle = fopen("php://memory", "rw");
    fwrite($handle, $fc);
    fseek($handle, 0);
    return $handle;
  }

  private function readFile(){
    $table = [];
    $fh = $this->utf8FopenRead();

    while (($data = fgetcsv($fh, 1000, ";")) !== false) {

      $table[] = $data;
    }
    return $table;
  }

  public function transpose(){

    $table = $this->readFile();
    $tableTranspose = [];

    $countField = count($table[0]);
    for($i = 1; $i < $countField; $i++){
      $tableTranspose[] = $this->transposeColumnToRow($table, $i);
    }
    return $tableTranspose;
  }

  private function transposeColumnToRow($data, $indexField)
  {
    $countRows = count($data);
    $row = [];
    for ($i = 0; $i < $countRows; $i++) {
      if($data[$i][0]){
        $row[$data[$i][0]] = $data[$i][$indexField];
      }else{
        $row[Fields::PROPERTY_OTHER['TITLE']][] = $data[$i][$indexField];
      }
    }
    $row['Картинки'] = $this->findImagesInName($row[Fields::NAME['TITLE']], $this->pictures);
    return $row;
  }

  /**
   * @param $file string
   * @return bool
   */
  private function validateImg(string $file){

    $extImages = ['.JPG', '.JPEG', '.GIF', '.PNG'];
    $ext = strtoupper(substr($file, -4));
    $result = in_array($ext, $extImages);
    return $result;
  }

  /**
   * @param string $name
   * @return string $vin
   */
  private function getVin(string $name){
    $nameArr = explode(' ', $name);
    return $nameArr[count($nameArr) - 1];
  }

  /**
   * @param $name string
   * @param $images array
   * @return array
   */
  public function findImagesInName($name, $images = []){
    $vin = $this->getVin($name);
    $images = $images ? $images: $this->pictures;
    $pictures = [];
    foreach ($images as $pict){
      if(strstr($pict, $vin)){
        $pictures[] = $pict;
      }
    }
    return $pictures;
  }

  /**
   * @return array
   */
  private function getFiles()
  {
    $path = $this->fileEntity->getDirectoryName();

    $pictures = [];
    foreach (scandir($path) as $file){
      if($this->validateImg($file)){
        $pictures[] = $file;
      }
    }
    return $pictures;
  }
}