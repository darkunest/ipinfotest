<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;
\CJSCore::Init();
?>

<div class="ip-form">
    <form action id="geoip-form" class="ip-form__wrapper">
        <div class="form-group">
            <label for="geoip"><?=Loc::getMessage('GEOIP_HEADING')?></label>
            <input type="text" name="geoip" id="geoip" placeholder="<?=Loc::getMessage('GEOIP_IP_PLACEHOLDER')?>">
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="<?=Loc::getMessage('GEOIP_SEND')?>">
        </div>
    </form>
</div>
<div class="ip-info" id="ip-info"></div>

<?php
$messages = Loc::loadLanguageFile(__FILE__);
?>
<script>
    BX.ready(function () {
        const params = {
            messages: BX.message(<?=CUtil::PhpToJSObject($messages)?>),
            form: BX('geoip-form'),
            infoBlock: BX('ip-info')
        };
        BX.Test.GeoIp.init(params);
    });
</script>