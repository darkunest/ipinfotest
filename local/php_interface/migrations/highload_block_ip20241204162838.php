<?php

namespace Sprint\Migration;


class highload_block_ip20241204162838 extends Version
{
    protected $author = "admin";

    protected $description = "Создание HL-блока для хранения информации об ip-адресах";

    protected $moduleVersion = "4.15.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->saveHlblock(array (
            'NAME' => 'GeoIpItems',
            'TABLE_NAME' => 'geoip_items',
            'LANG' =>
                array (
                    'ru' =>
                        array (
                            'NAME' => 'Элементы geo ip',
                        ),
                ),
        ));
        $helper->Hlblock()->saveGroupPermissions($hlblockId, array (
            'administrators' => 'W',
        ));
        $helper->Hlblock()->saveField($hlblockId, array (
            'FIELD_NAME' => 'UF_IP',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => 'UF_IP',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                array (
                    'SIZE' => 20,
                    'ROWS' => 1,
                    'REGEXP' => '',
                    'MIN_LENGTH' => 0,
                    'MAX_LENGTH' => 0,
                    'DEFAULT_VALUE' => '',
                ),
            'EDIT_FORM_LABEL' =>
                array (
                    'en' => '',
                    'ru' => 'IP',
                ),
            'LIST_COLUMN_LABEL' =>
                array (
                    'en' => '',
                    'ru' => 'IP',
                ),
            'LIST_FILTER_LABEL' =>
                array (
                    'en' => '',
                    'ru' => 'IP',
                ),
            'ERROR_MESSAGE' =>
                array (
                    'en' => '',
                    'ru' => '',
                ),
            'HELP_MESSAGE' =>
                array (
                    'en' => '',
                    'ru' => '',
                ),
        ));
        $helper->Hlblock()->saveField($hlblockId, array (
            'FIELD_NAME' => 'UF_DATA',
            'USER_TYPE_ID' => 'string',
            'XML_ID' => '',
            'SORT' => '100',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'N',
            'SETTINGS' =>
                array (
                    'SIZE' => 20,
                    'ROWS' => 1,
                    'REGEXP' => '',
                    'MIN_LENGTH' => 0,
                    'MAX_LENGTH' => 0,
                    'DEFAULT_VALUE' => '',
                ),
            'EDIT_FORM_LABEL' =>
                array (
                    'en' => '',
                    'ru' => 'Данные',
                ),
            'LIST_COLUMN_LABEL' =>
                array (
                    'en' => '',
                    'ru' => 'Данные',
                ),
            'LIST_FILTER_LABEL' =>
                array (
                    'en' => '',
                    'ru' => 'Данные',
                ),
            'ERROR_MESSAGE' =>
                array (
                    'en' => '',
                    'ru' => '',
                ),
            'HELP_MESSAGE' =>
                array (
                    'en' => '',
                    'ru' => '',
                ),
        ));
    }
}