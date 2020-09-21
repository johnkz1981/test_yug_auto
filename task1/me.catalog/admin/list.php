<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Grid\Panel\Snippet;
use Bitrix\Main\Loader;
use Bitrix\Main\UI\{PageNavigation, Extension, Filter\Options};
use Me\Catalog\DataTable;

IncludeModuleLangFile(__FILE__);
Extension::load("ui.forms");
Extension::load("ui.notification");
Loader::IncludeModule("me.catalog");

$POST_RIGHT = $APPLICATION->GetGroupRight("me.catalog");
$APPLICATION->SetTitle(GetMessage("LIST_TITLE"));

$request = Application::getInstance()->getContext()->getRequest()->getPostList();
$list_id = 'me_flowers';
$grid_options = new GridOptions($list_id);
$snippet = new Snippet();

/**
 * actions
 */
if($request->get('action_button_me_flowers')){
  if($request->get('action_button_me_flowers') === 'delete'){
    $ids = $request->get('ID');
    foreach ($ids as $id){
      DataTable::delete($id);
    }
  }

  if($request->get('action_button_me_flowers') === 'edit'){
    $fields = $request->get('FIELDS');

    foreach ($fields as $id => $row){
      $objRow = DataTable::getByPrimary($id)->fetchObject();

      $objRow->setTitle($row['TITLE']);
      $objRow->setQuantity($row['QUANTITY']);
      $objRow->setPrice($row['PRICE']);

      $result = $objRow->save();
    }
  }
}

/**
 * Навигация
 */
$nav_params = $grid_options->GetNavParams();
$nav = new PageNavigation('request_list');

$nav->allowAllRecords(true)//Показать все
->setRecordCount(DataTable::getCount())//Для работы кнопки "показать все"
->setPageSize($nav_params['nPageSize'])//Параметр сколько отображать на странице
->initFromUri();

/**
 * Фильтрация
 */
$filterOption = new Options($list_id);
$filterData = $filterOption->getFilter([]);
$filter = [];

foreach ($filterData as $key => $value) {

  /**
   * Фильтр название
   */
  if ($key === 'TITLE' || $key === 'FIND' && strlen($value) > 0) {
    $filter['%TITLE'] = $value;
  }
}

/**
 * Результат запроса для отображения таблицы
 */
$rsData = DataTable::getList([
  'filter' => $filter,
  'order' => $grid_options->getSorting()['sort'],
  'limit' => $nav->getLimit(),
  'offset' => $nav->getOffset()
]);

/**
 * Список фильтров
 */
$filter_list = [
  [
    "id" => "TITLE",
    'type' => 'text',
    "name" => GetMessage("DATA_ENTITY_TITLE_FIELD"),
    "default" => true
  ],
];

/**
 * Колонки таблицы
 */
$arHeaders = [
  ["id" => "ID", "name" => GetMessage("DATA_ENTITY_ID_FIELD"), "sort" => "ID", "align" => "center", "default" => true],
  ["id" => "TITLE", "name" => GetMessage("DATA_ENTITY_TITLE_FIELD"), "sort" => "TITLE", "align" => "center", "default" => true, 'editable' => true],
  ["id" => "QUANTITY", "name" => GetMessage("DATA_ENTITY_QUANTITY_FIELD"), "align" => "center", "default" => true, 'editable' => true],
  ["id" => "PRICE", "name" => GetMessage("DATA_ENTITY_PRICE_FIELD"), "sort" => "PRICE", "align" => "center", "default" => true, 'editable' => true],
];

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<div class="adm-toolbar-panel-container">
  <div class="adm-toolbar-panel-flexible-space">
    <?php
    $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
      'FILTER_ID' => $list_id,
      'GRID_ID' => $list_id,
      'FILTER' => $filter_list,
      'ENABLE_LIVE_SEARCH' => true,
      'ENABLE_LABEL' => true
    ]);
    ?>
  </div>
  <div class="adm-toolbar-panel-align-right">
  </div>
</div>
<div class="adm-toolbar-panel-container">
  <form class="adm-toolbar-panel-flexible-space" name="add-row">
    <div class="ui-ctl ui-ctl-textbox ui-ctl-inline">
      <input type="text" class="ui-ctl-element" name="TITLE" placeholder="Наименование">
    </div>
    <div class="ui-ctl ui-ctl-textbox ui-ctl-inline">
      <input type="text" class="ui-ctl-element" name="QUANTITY" placeholder="Количество">
    </div>
    <div class="ui-ctl ui-ctl-textbox ui-ctl-inline">
      <input type="text" class="ui-ctl-element" name="PRICE" placeholder="Цена">
    </div>
    <button class="ui-btn" id="me-catalog_list_add-row">Добавить</button>
  </form>
</div>
<?php
/**
 * Данные по каждому выбранному элементу таблицы
 */
$list = [];
while ($row = $rsData->fetch()) {

  $url_params = http_build_query(
    [
      'CODE_PARTNER' => $row['CODE_PARTNER'],
      'lang' => LANGUAGE_ID,
      'ID' => $row['ID'],
      'group-by' => 1
    ]
  );

  $list[] = [
    'data' => $row,
  ];
}

$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
  'GRID_ID' => $list_id,
  'COLUMNS' => $arHeaders,
  'ROWS' => $list,
  'SHOW_ROW_CHECKBOXES' => true,
  'NAV_OBJECT' => $nav,
  'AJAX_MODE' => 'Y',
  'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
  'PAGE_SIZES' => [
    ['NAME' => '5', 'VALUE' => '5'],
    ['NAME' => '20', 'VALUE' => '20'],
    ['NAME' => '50', 'VALUE' => '50'],
    ['NAME' => '100', 'VALUE' => '100']
  ],
  'AJAX_OPTION_JUMP' => 'Y',
  'SHOW_CHECK_ALL_CHECKBOXES' => true,
  'SHOW_ROW_ACTIONS_MENU' => false,
  'SHOW_GRID_SETTINGS_MENU' => false,
  'SHOW_NAVIGATION_PANEL' => true,
  'SHOW_PAGINATION' => true,
  'SHOW_SELECTED_COUNTER' => true,
  'SHOW_TOTAL_COUNTER' => true,
  'SHOW_PAGESIZE' => true,
  'SHOW_ACTION_PANEL' => true,
  'ALLOW_COLUMNS_SORT' => true,
  'ALLOW_COLUMNS_RESIZE' => true,
  'ALLOW_HORIZONTAL_SCROLL' => true,
  'ALLOW_SORT' => true,
  'ALLOW_PIN_HEADER' => true,
  'AJAX_OPTION_HISTORY' => 'Y',
  'TOTAL_ROWS_COUNT_HTML' => '<span class="main-grid-panel-content-title">Всего:</span> <span class="main-grid-panel-content-text">' . $nav->getRecordCount() . '</span>',
  'ACTION_PANEL' => [
    'GROUPS' => [
      'TYPE' => [
        'ITEMS' => [
          $snippet->getRemoveButton(),
          $snippet->getEditButton(),
        ],
      ]
    ],
  ],
]);
?>
<script>
  const addRow = document.getElementById('me-catalog_list_add-row');

  addRow.addEventListener('click', evn => {
    evn.preventDefault();

    const addForm = document.forms['add-row'],
        formData = new FormData(addForm);

    const title = formData.get("TITLE");
    const quantity = formData.get("QUANTITY");
    const price = formData.get("PRICE");

    console.log('title', title, quantity, price);

    BX.ajax.runAction('me:catalog.api.modification.add', {data: {title, quantity, price}})
        .then(function (evn) {

          const response = evn.data.response;

          if(response){
            BX.UI.Notification.Center.notify({
              content: 'Запись добавлена',
              position: 'top-center'
            });
            BX.Main.gridManager.reload('me_flowers');
          }
        });
  })
</script>
