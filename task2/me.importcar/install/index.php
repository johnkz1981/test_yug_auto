<?
IncludeModuleLangFile(__FILE__);

use \Bitrix\Main\ModuleManager;

Class Me_ImportCar extends CModule
{

  var $MODULE_ID = "me.importcar";
  var $MODULE_VERSION;
  var $MODULE_VERSION_DATE;
  var $MODULE_NAME;
  var $MODULE_DESCRIPTION;
  var $errors;

  function __construct()
  {
    //$arModuleVersion = array();
    $this->MODULE_VERSION = "0.0.1";
    $this->MODULE_VERSION_DATE = "2020-09-19";
    $this->MODULE_NAME = "Импорт автомобилей";
    $this->MODULE_DESCRIPTION = "Тестовый модуль Импорт автомобилей";
    $this->PARTNER_NAME = "ME";
  }

  function DoInstall()
  {
    $this->InstallDB();
    $this->InstallEvents();
    $this->InstallFiles();
    RegisterModule("me.importcar");
    return true;
  }

  function DoUninstall()
  {
    $this->UnInstallDB();
    $this->UnInstallEvents();
    $this->UnInstallFiles();
    UnRegisterModule("me.importcar");
    return true;
  }

  function InstallDB()
  {
    return true;
  }

  function UnInstallDB()
  {
    return true;
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
      $_SERVER["DOCUMENT_ROOT"] . '/local/modules/me.importcar/install/import_car',
      $_SERVER["DOCUMENT_ROOT"] . '/upload/'
    );

    CopyDirFiles(
      $_SERVER["DOCUMENT_ROOT"] . '/local/modules/me.importcar/install/me.catalog_list.php',
      $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin/me.import_import.php', true
    );
    return true;
  }

  function UnInstallFiles()
  {
    DeleteDirFilesEx('/bitrix/admin/me.import_import.php');
    return true;
  }
}