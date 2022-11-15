define(['jquery'], function ($) {
    var CustomFormWidget = function () {
        var self = this, // для доступа к объекту из методов
            system = self.system(), //Данный метод возвращает объект с переменными системы.
            langs = self.langs;  //Объект локализации с данными из файла локализации (папки i18n)

        this.callbacks = {
            settings: function () {},
            init: function () {
                return true;
            },
            bind_actions: function () {
                return true;
            },
            render: function () {
                return true;
            },
            dpSettings: function () {},
            advancedSettings: function () {},
            destroy: function () {},
            contacts: {
                selected: function () {}
            },
            onSalesbotDesignerSave: function (handler_code, params) {},
            leads: {
                selected: function () {}
            },
            todo: {
                selected: function () {}
            },
            onSave: function () {},
            onAddAsSource: function (pipeline_id) {}
        };
        return this;
    };
    return CustomWidget;
});