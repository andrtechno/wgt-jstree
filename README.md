wgt-jstree
===========
Widget for Yii Framework 2.0 to use [JsTree](http://www.jstree.com)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist panix/wgt-jstree "*"
```

or add

```
"panix/wgt-jstree": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by :

```php
<?=  \panix\jstree\JsTree::widget([
    'attribute' => 'attribute_name',
    'model' => $model,
    'core' => [
        'data' => $data
        ...
    ],
    'plugins' => ['types', 'dnd', 'contextmenu', 'wholerow', 'state'],
    ...
]); ?>
```

Usage without a model (you must specify the "name" attribute) :

```php
<?=  \panix\jstree\JsTree::widget([
    'name' => 'js_tree',
    'core' => [
        'data' => $data
        ...
    ],
    'plugins' => ['types', 'dnd', 'contextmenu', 'wholerow', 'state'],
    ...
]); ?>
```
