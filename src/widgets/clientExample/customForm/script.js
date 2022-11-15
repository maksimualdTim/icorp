define(['jquery'], function ($) {
    var CustomFormWidget = function () {
        var self = this, // для доступа к объекту из методов
            system = self.system(), //Данный метод возвращает объект с переменными системы.
            langs = self.langs;  //Объект локализации с данными из файла локализации (папки i18n)

        self.getTemplate = function (template, params, callback) {
            params = (typeof params == 'object') ? params : {};
            template = template || '';

            return self.render({
                href: '/templates/' + template + '.twig',
                base_path: self.params.path, //тут обращение к объекту виджет вернет /widgets/#WIDGET_NAME#
                load: callback //вызов функции обратного вызова
            }, params); //параметры для шаблона
        };

        this.callbacks = {
            settings: function () {
                const account_id = $('.n-avatar ').attr('id')
                self.getTemplate(  //вызов функции
                    'template', //указываем имя шаблона, который лежит у нас в папке с виджетом в папке templates
                    {}, /* пустые данные для шаблона, т.к мы сначала запросим шаблон, если он существует, то функция обр.вызова вызовет уже функцию для добавления данных к шаблону, см.ниже */
                    function (template) {
                        const script = template.render({
                            account_id: account_id, //параметры для шаблона.
                            referer: window.location.hostname,
                        })
                        $('.modal-dp__caption').parent().prepend(script)
                        console.log(script);
                    })
            },
            init: function () {
                return true;
            },
            bind_actions: function () {
                return true;
            },
            render: function () {
                return true;
            },
            dpSettings: function () {
            },
            advancedSettings: function () {
            },
            destroy: function () {
                const account_id = $('.n-avatar ').attr('id')


            },
            contacts: {
                selected: function () {
                }
            },
            onSalesbotDesignerSave: function (handler_code, params) {
            },
            leads: {
                selected: function () {
                }
            },
            todo: {
                selected: function () {
                }
            },
            onSave: function () {
            },
            onAddAsSource: function (pipeline_id) {
            }
        };
        return this;
    };
    return CustomFormWidget;
});