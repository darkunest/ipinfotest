BX.namespace('BX.Test.GeoIp');

BX.Test.GeoIp = {
    init: function (params) {
        // заполнение параметров
        this.messages = params.messages;
        this.form = params.form;
        this.infoBlock = params.infoBlock;

        // обработка отправки формы
        BX.bind(this.form, 'submit', this.sendForm.bind(this));
    },
    sendForm: function (e) {
        e.preventDefault();
        const that = this;
        // получаем данные формы
        const data = BX.ajax.prepareForm(e.target).data;
        if (!data.geoip) {
            return that.createErrorBlock(BX.message('GEOIP_ERROR_EMPTY'));
        }
        // взял регулярное выражение из интернета
        const grepIp = /([0-9]{1,3}[\\.]){3}[0-9]{1,3}/;
        if (!grepIp.test(data.geoip)) {
            return that.createErrorBlock(BX.message('GEOIP_ERROR_NOT_VALID'));
        }

        const ajaxConfig = {
            method: 'post',
            mode: 'class',
            data: {
                params: data
            }
        };

        // делаем запрос на получение
        BX.ajax.runComponentAction('test:geoip_form', 'getInfo', ajaxConfig)
            .then((response) => {
                if (response.status === 'success') {
                    const result = response.data;

                    // создаем блок с отображением IP
                    const ipBlock = BX.create({
                        tag: 'div',
                        props: {
                            className: 'ip-info__heading'
                        },
                        text: BX.message('GEOIP_RESULT_IP') + result.ip
                    });

                    // создание блоков для отображения
                    const city = result.city ? result.city : [];
                    const cityBlock = that.createInfoBlock(city, 'CITY', ['lat', 'lon', 'name_ru', 'population']);

                    const region = result.region ? result.region : [];
                    const regionBlock = that.createInfoBlock(region, 'REGION', ['lat', 'lon', 'name_ru', 'timezone']);

                    const country = result.country ? result.country : [];
                    const countryBlock = that.createInfoBlock(country, 'COUNTRY', ['lat', 'lon', 'name_ru', 'population', 'timezone', 'capital_ru']);

                    // создание общего блока для отображения всех дочерних
                    const infoBlock = BX.create({
                        tag: 'div',
                        props: {
                            className: 'ip-info__wrapper'
                        },
                        children: [
                            ipBlock,
                            BX.create({
                                tag: 'div',
                                props: {
                                    className: 'ip-info__list'
                                },
                                children: [
                                    cityBlock,
                                    regionBlock,
                                    countryBlock
                                ]
                            })
                        ]
                    });

                    BX.cleanNode(that.infoBlock);
                    BX.append(infoBlock, that.infoBlock);
                }
            }).catch((response) => {
                if (response.errors.length) {
                    that.createErrorBlock(response.errors[0].message);
                }
        });
    },

    // метод для создания блоков страны, региона и города
    createInfoBlock: function (item, itemName, reqFields = []) {
        const children = [];
        children.push(BX.create({
            tag: 'div',
            text: BX.message(`GEOIP_${itemName}`),
            props: {
                className: 'ip-info__heading'
            }
        }));

        for (let i in item) {
            if (reqFields.includes(i)) {
                const childItem = BX.create({
                    tag: 'div',
                    props: {
                        className: 'ip-info__item'
                    },
                    // Добавляем проверку для нахождения поля "Население", чтобы отформатировать его, ставя пробел через каждые 3 цифры (регулярку брал из интернета)
                    text: BX.message(`GEOIP_RESULT_${i}`) + (i === 'population' ? String(item[i]).replace(/(\d)(?=(\d{3})+$)/g, '$1 ') : item[i])
                });
                children.push(childItem);
            }
        }

        const block = BX.create({
            tag: 'div',
            props: {
                className: 'ip-info__block'
            },
            children
        });

        return BX.create({
            tag: 'div',
            props: {
                className: 'ip-info__block-wrapper'
            },
            children: [block]
        });
    },

    // создание блока с ошибкой
    createErrorBlock: function (message) {
        const errorBlock = BX.create({
            tag: 'div',
            props: {
                className: 'ip-info__error'
            },
            text: message
        });

        // добавление и удаление класса и элемента с простой анимацией
        BX.cleanNode(this.infoBlock);
        BX.append(errorBlock, this.infoBlock);

        setTimeout(function (){
            BX.addClass(errorBlock, 'active');
        }, 100);

        setTimeout(function (){
            BX.removeClass(errorBlock, 'active');
        }, 1500);

        setTimeout(function () {
            BX.remove(errorBlock)
        }, 2000);

        return false;
    }
}