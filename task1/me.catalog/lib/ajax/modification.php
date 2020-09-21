<?php

namespace Me\Catalog\Ajax;

use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Loader;
use Me\Catalog\DataTable;

class Modification extends Controller
{
  public function addAction()
  {
    Loader::IncludeModule("me.catalog");
    $request = $this->getRequest();

    $data = [
      'title' => $request->get('title'),
      'quantity' => $request->get('quantity'),
      'price' => $request->get('price')
    ];
    $result = DataTable::add($data);

    return ['response' => $result->isSuccess(), 'id' => $result->getId(), $result->getData()];
  }
}