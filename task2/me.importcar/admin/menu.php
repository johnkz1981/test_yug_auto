<?
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('me.importcar')) return;

return [
  [
    'parent_menu' => 'global_menu_me',
    'section' => 'orders-from-suppliers',
    'sort' => 20,
    'module_id' => Loc::getMessage('IMPORT_MODULE_ID'),
    'items_id' => 'menu_'.Loc::getMessage('IMPORT_MODULE_ID'),
    'icon' => 'clouds_menu_icon',
    'page_icon' => 'clouds_menu_icon',
    'text' => Loc::getMessage('IMPORT_MODULE_NAME'),
    'items' => [
      [
        'items_id' => 'menu_'.Loc::getMessage('IMPORT_MODULE_ID').'_import',
        'sort' => 100,
        'icon' => 'list_menu_icon',
        'page_icon' => 'list_menu_icon',
        'text' => Loc::getMessage('IMPORT'),
        'url' => 'me.import_import.php?lang='.LANG
      ],
    ]
  ]
];