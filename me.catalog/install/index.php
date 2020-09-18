<?

IncludeModuleLangFile(__FILE__);

use \Bitrix\Main\ModuleManager;

Class Me_Catalog extends CModule
{

  var $MODULE_ID = "me.catalog";
  var $MODULE_VERSION;
  var $MODULE_VERSION_DATE;
  var $MODULE_NAME;
  var $MODULE_DESCRIPTION;
  var $errors;

  function __construct()
  {
    //$arModuleVersion = array();
    $this->MODULE_VERSION = "1.0.0";
    $this->MODULE_VERSION_DATE = "2020-09-17";
    $this->MODULE_NAME = "Модуль цветов D7";
    $this->MODULE_DESCRIPTION = "Модуль цветов";
    $this->PARTNER_NAME = "ME";
  }

  function DoInstall()
  {
    $this->InstallDB();
    $this->InstallEvents();
    $this->InstallFiles();
    RegisterModule("me.catalog");
    return true;
  }

  function DoUninstall()
  {
    $this->UnInstallDB();
    $this->UnInstallEvents();
    $this->UnInstallFiles();
    UnRegisterModule("me.catalog");
    return true;
  }

  function InstallDB()
  {
    global $DB;
    $this->errors = false;
    $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/me.catalog/install/db/install.sql");
    if (!$this->errors) {

      return true;
    } else
      return $this->errors;
  }

  function UnInstallDB()
  {
    global $DB;
    $this->errors = false;
    $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/local/modules/me.catalog/install/db/uninstall.sql");
    if (!$this->errors) {
      return true;
    } else
      return $this->errors;
  }

  function InstallEvents()
  {
    return true;
  }

  function UnInstallEvents()
  {
    return true;
  }

  function InstallFiles()
  {
    CopyDirFiles(
      $_SERVER["DOCUMENT_ROOT"] . '/local/modules/me.catalog/install/me.catalog_list.php',
      $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin/me.catalog_list.php', true
    );
    return true;
  }

  function UnInstallFiles()
  {
    DeleteDirFilesEx('/bitrix/admin/me.catalog_list.php');
    return true;
  }
}