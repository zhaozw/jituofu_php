Шаблонизатор Twig для Yii
=========================

Данное расширение позволяет использовать [Twig](http://twig.sensiolabs.org) как шаблонизатор.

###Полезные ссылки
* [Это расширение](https://github.com/yiiext/twig-renderer)
* [Twig](http://twig.sensiolabs.org)
* [Обсуждение](http://yiiframework.ru/forum/viewtopic.php?f=9&t=238)
* [Соощить об ошибке](https://github.com/yiiext/twig-renderer/issues)

###Требования
* Yii 1.0 и выше

###Установка
* Распаковать в `protected/extensions`.
* [Скачать](http://twig.sensiolabs.org/installation) и распаковать все файлы
  Twig из `fabpot-Twig-______.zip\fabpot-Twig-______\lib\Twig\` в `protected/vendors/Twig`.
* Добавить следующее в файл конфигурации в секцию 'components':

```php
<?php

    'viewRenderer' => array(
        'class' => 'ext.ETwigViewRenderer',

        // Все параметры ниже являются необязательными
        'fileExtension' => '.twig',
        'options' => array(
            'autoescape' => true,
        ),
        'extensions' => array(
            'My_Twig_Extension',
        ),
        'globals' => array(
            'html' => 'CHtml'
        ),
        'functions' => array(
            'rot13' => 'str_rot13',
        ),
        'filters' => array(
            'jencode' => 'CJSON::encode',
        ),
        // Пример изменения синтаксиса на Smarty-подобный (не рекомендуется использовать)
        'lexerOptions' => array(
            'tag_comment'  => array('{*', '*}'),
            'tag_block'    => array('{', '}'),
            'tag_variable' => array('{$', '}')
        ),
    ),
```

###Использование
* См. [синтаксис Twig](http://twig.sensiolabs.org/doc/templates.html).
* Свойства текущего контроллера доступны как {{this.pageTitle}}.
* Объект приложения Yii::app() доступен как {{App}}.
* Базовые статические классы Yii (например, CHtml) доступны как {{C.ClassNameWithoutFirstC.Method}} (пример: {{C.Html.textField(name,'value')}})
* Для вызова функций или методов, которые возвращают не строку (а объект, например) используйте функцию-обертку 'void': {{void(App.clientScript.registerScriptFile(...))}}

###Пример использования виджета
```html
<div id="mainmenu">
    {{ this.widget('zii.widgets.CMenu',{
        'items':[
            {'label':'Home', 'url':['/site/index']},
            {'label':'About', 'url':{0:'/site/page', 'view':'about'} },
            {'label':'Contact', 'url':['/site/contact']},
            {'label':'Login', 'url':['/site/login'], 'visible':App.user.isGuest},
            {'label':'Logout ('~App.user.name~')', 'url':['/site/logout'], 'visible':not App.user.isGuest}
        ]
    }, true) }}
</div><!-- mainmenu -->
```