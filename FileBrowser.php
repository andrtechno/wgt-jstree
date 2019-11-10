<?php

namespace panix\ext\jstree;

use panix\engine\data\Widget;

class FileBrowser extends Widget
{

    public function init()
    {
        $view = $this->getView();
        JsTreeAsset::register($view);


        //$this->cs->registerCssFile($assetsUrl . '/themes/default/style.min.css');
       // $this->cs->registerCssFile($assetsUrl . '/themes/default/filebrowser.css');

    }

    public function run()
    {
       // Yii::import('mod.admin.components.fs');
       // $fs = new fs(Yii::getPathOfAlias('webroot.themes'));

    }


}
