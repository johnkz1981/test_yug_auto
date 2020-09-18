<?
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('me.catalog')) return;

return [
  [
    'parent_menu' => 'global_menu_me',
    'section' => 'orders-from-suppliers',
    'sort' => 20,
    'module_id' => Loc::getMessage('CATALOG_MODULE_ID'),
    'items_id' => 'menu_'.Loc::getMessage('CATALOG_MODULE_ID'),
    'icon' => 'clouds_menu_icon',
    'page_icon' => 'clouds_menu_icon',
    'text' => Loc::getMessage('CATALOG_MODULE_NAME'),
    'items' => [
      [
        'items_id' => 'menu_'.Loc::getMessage('CATALOG_MODULE_ID').'_list',
        'sort' => 100,
        'icon' => 'list_menu_icon',
        'page_icon' => 'list_menu_icon',
        'text' => Loc::getMessage('LIST'),
        'url' => 'me.catalog_list.php?lang='.LANG
      ],
    ]
  ]
];