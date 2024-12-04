<?php

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\ArgumentException;

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
            throw new ArgumentException(Loc::getMessage('IP_IS_EMPTY'));
        }
        if (!Loader::includeModule("highloadblock")) {
            throw new ArgumentException(Loc::getMessage('HL_NOT_INSTALLED'));
        }
        $hlId = 0;
        $hlRes = HL\HighloadBlockTable::getList(['filter' => ['NAME' => 'GeoIpItems']]);
        if ($hl = $hlRes->fetch()) {
            $hlId = $hl['ID'];
        } else {
            throw new ArgumentException(Loc::getMessage('HL_NOT_FOUND'));
        }

        $entity = HL\HighloadBlockTable::compileEntity($hlId);
        $entityDataClass = $entity->getDataClass();

        $rsData = $entityDataClass::getList([
            "filter" => ["UF_IP" => $ip]
        ]);

        // Если находим нужный элемент, то в переменную для возврата закидываем информацию об этом ip
        if ($arData = $rsData->Fetch()) {
            $result = unserialize($arData['UF_DATA']);
        } else { // иначе используем api sypexgeo для получения информации
            $is_bot = empty($_SERVER['HTTP_USER_AGENT']) || preg_match(
                    "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl|request|Guzzle|Java)~i",
                    $_SERVER['HTTP_USER_AGENT']
                );
            $geo = !$is_bot ? json_decode(
                file_get_contents('http://api.sypexgeo.net/json/' . $ip),
                true) : [];
            $result = $geo;

            if (!empty($result) && $result['city'] !== null) {
                // если удалось найти, то добавляем информацию в HL-блок для последующего использования
                $fields = [
                    'UF_IP' => $result['ip'],
                    'UF_DATA' => serialize($result)
                ];
                $entityDataClass::add($fields);
            } else {
                // если в конечном массиве ничего нет или значение города = null, то это значит, что ничего не удалось по нему найти
                throw new ArgumentException(Loc::getMessage('IP_INFO_NOT_FOUND'));
            }
        }

        return $result;
    }
}
