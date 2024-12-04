<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Форма");

$APPLICATION->IncludeComponent('test:geoip_form', '', []);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>