<?php

use Bitrix\Main\Engine\Contract\Controllerable,
    Bitrix\Main\Engine\CurrentUser,
    Bitrix\Main\Localization\Loc;

use \Bitrix\Main\Text\Encoding;

use Bitrix\Main\Type\DateTime;

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class GeoIpForm extends \CBitrixComponent implements Controllerable
{
    /**
     * @return array|void
     */
    public function configureActions()
    {
        return [
            'getInfo' => [
                'prefilters' => [],
                'postfilters' => [],
            ]
        ];
    }

    /**
     * @return mixed|void|null
     */
    public function executeComponent()
    {
        $this->includeComponentTemplate();
    }

    /**
     * @param array $params
     * @return array
     */
    public function getInfoAction($params = [])
    {
        $result = [];
        $ip = $params['geoip'];
        if (!$ip) {
            throw new \Bitrix\Main\ArgumentException(Loc::getMessage('IP_IS_EMPTY'));
        }
        if (!Loader::includeModule("highloadblock")) {
            throw new \Bitrix\Main\ArgumentException(Loc::getMessage('HL_NOT_INSTALLED'));
        }
        $hlId = 0;
        $hlRes = HL\HighloadBlockTable::getList(['filter' => ['NAME' => 'GeoIpItems']]);
        if ($hl = $hlRes->fetch()) {
            $hlId = $hl['ID'];
        } else {
            throw new \Bitrix\Main\ArgumentException(Loc::getMessage('HL_NOT_FOUND'));
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlId);
        $entityDataClass = $entity->getDataClass();

        $rsData = $entityDataClass::getList([
            "filter" => ["UF_IP" => $ip]
        ]);

        if ($arData = $rsData->Fetch()) {
            $result = unserialize($arData['UF_DATA']);
        } else {
            $is_bot = empty($_SERVER['HTTP_USER_AGENT']) || preg_match(
                    "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl|request|Guzzle|Java)~i",
                    $_SERVER['HTTP_USER_AGENT']
                );
            $geo = !$is_bot ? json_decode(
                file_get_contents('http://api.sypexgeo.net/json/' . $ip),
                true) : [];
            $result = $geo;

            if (!empty($result) && $result['city'] !== null) {
                $fields = [
                    'UF_IP' => $result['ip'],
                    'UF_DATA' => serialize($result)
                ];
                $entityDataClass::add($fields);
            } else {
                throw new \Bitrix\Main\ArgumentException(Loc::getMessage('IP_INFO_NOT_FOUND'));
            }
        }

        return $result;
    }
}
