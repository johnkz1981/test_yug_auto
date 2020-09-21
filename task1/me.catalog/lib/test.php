<?php

namespace Me\Catalog;

class Test
{
  public static function get()
  {
    $result = DataTable::getTableName();
    return $result;
  }
}