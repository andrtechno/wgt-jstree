<?php

/**
 *
 * JsTree widget
 *
 * @author CORNER CMS <dev@corner-cms.com>
 * @link http://www.corner-cms.com/
 */

namespace panix\ext\jstree;

use yii\web\AssetBundle;

class JsTreeAsset extends AssetBundle {

    public $sourcePath = '@vendor/panix/wgt-jstree/assets';
    //public $jsOptions = array(
    //    'position' => \yii\web\View::POS_READY
    //);
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * Set up CSS and JS asset arrays based on the base-file names
     * @param string $type whether 'css' or 'js'
     * @param array $files the list of 'css' or 'js' basefile names
     */
    protected function setupAssets($type, $files = []) {
        $srcFiles = [];
        $minFiles = [];
        foreach ($files as $file) {
            $srcFiles[] = "{$file}.{$type}";
            $minFiles[] = "{$file}.min.{$type}";
        }
        if (empty($this->$type)) {
            $this->$type = YII_DEBUG ? $srcFiles : $minFiles;
        }
    }

    /**
     * @inheritdoc
     */
    public function init() {
        $this->setupAssets('css', ['themes/default/style']);
        $this->setupAssets('js', ['jstree']);
        parent::init();
    }

    /**
     * Sets the source path if empty
     * @param string $path the path to be set
     */
    protected function setSourcePath($path) {
        if (empty($this->sourcePath)) {
            $this->sourcePath = $path;
        }
    }

}
