<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\{Application, Loader, UI\Extension};
use Me\ImportCar\{Import, Csv};

IncludeModuleLangFile(__FILE__);
Extension::load("ui.forms");
Extension::load("ui.buttons");
Extension::load("ui.notification");
Loader::IncludeModule("me.importcar");

$POST_RIGHT = $APPLICATION->GetGroupRight("me.catalog");
$APPLICATION->SetTitle(GetMessage("IMPORTCAR_TITLE"));
$pathCsv = Application::getInstance()->getContext()->getRequest()->get('PATH_CSV');
$ids = [];

if ($pathCsv) {
  if (file_exists(Application::getDocumentRoot() . $pathCsv)) {
    $ids = (new Import(new Csv($pathCsv)))->addFullTable();
  }
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<div class="adm-toolbar-panel-container">
    <form class="adm-toolbar-panel-flexible-space" name="add-row">
        <div class="ui-ctl ui-ctl-textbox ui-ctl-inline">
            <input
                    type="text"
                    class="ui-ctl-element"
                    name="PATH_CSV"
                    value="/upload/import_car/best.csv"
                    placeholder="Путь к файлу"
                    style="width: 200px"
            >
        </div>
        <button class="ui-btn" id="me-catalog_list_add-row">Добавить</button>
    </form>
</div>
<ul>
  <?php
  if($ids){
      echo "<h3>Добавлены новые элементы</h3>";
  }
  foreach ($ids as $id) {
    echo "<li>$id</li>";
  }
  ?>
</ul>