<?php

/**
 *
 * JsTree widget
 *
 * @author CORNER CMS <dev@corner-cms.com>
 * @link http://www.corner-cms.com/
 */

namespace panix\ext\jstree;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class JsTree extends InputWidget {

    /**
     * @var array Data configuration.
     * If left as false the HTML inside the jstree container element is used to populate the tree (that should be an unordered list with list items).
     */
    public $data = [];

    /**
     * @var array Stores all defaults for the core
     */
    public $core = [
        'expand_selected_onload' => true,
        'themes' => [
            'icons' => false
        ]
    ];

    /**
     * @var array Stores all defaults for the checkbox plugin
     */
    public $checkbox = [
        'three_state' => true,
        'keep_selected_style' => false];

    /**
     * @var array Stores all defaults for the contextmenu plugin
     */
    public $contextmenu = [];

    /**
     * @var array Stores all defaults for the drag'n'drop plugin
     */
    public $dnd = [];

    /**
     * @var array Stores all defaults for the search plugin
     */
    public $search = [];

    /**
     * @var string the settings function used to sort the nodes.
     * It is executed in the tree's context, accepts two nodes as arguments and should return `1` or `-1`.
     */
    public $sort = [];

    /**
     * @var array Stores all defaults for the state plugin
     */
    public $state = [];

    /**
     * @var array Configure which plugins will be active on an instance. Should be an array of strings, where each element is a plugin name.
     */
    public $plugins = ["checkbox"];

    /**
     * @var array Stores all defaults for the types plugin
     */
    public $types = [
        '#' => [],
        'default' => [],
    ];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->registerAssets();

        if (!$this->hasModel()) {
            echo Html::hiddenInput($this->options['id'], null, [ 'id' => $this->options['id']]);
        } else {
            echo Html::activeTextInput($this->model, $this->attribute, ['class' => 'hidden', 'value' => $this->value]);
            Html::addCssClass($this->options, "js_tree_{$this->attribute}");
        }

        $this->options['id'] = 'jsTree_' . $this->options['id'];
        echo Html::tag('div', '', $this->options);
    }

    /**
     * Registers the needed assets
     */
    public function registerAssets() {
        $view = $this->getView();
        JsTreeAsset::register($view);

        $config = [
            'core' => array_merge(['data' => $this->createHtmlTree($this->data)], $this->core),
            'checkbox' => $this->checkbox,
            'contextmenu' => $this->contextmenu,
            'dnd' => $this->dnd,
            'search' => $this->search,
            'sort' => $this->sort,
            'state' => $this->state,
            'plugins' => $this->plugins,
            'types' => $this->types
        ];
        $defaults = Json::encode($config);

        $inputId = (!$this->hasModel()) ? $this->options['id'] : Html::getInputId($this->model, $this->attribute);

        $js = <<<SCRIPT
;(function($, window, document, undefined) {
    $('#jsTree_{$this->options['id']}')
        .bind("loaded.jstree", function (event, data) {
                $("#{$inputId}").val(JSON.stringify(data.selected));
            })
        .bind("changed.jstree", function(e, data, x){
                $("#{$inputId}").val(JSON.stringify(data.selected));
        })
        .jstree({$defaults});
})(window.jQuery, window, document);
SCRIPT;
        $view->registerJs($js);
    }

    private function createHtmlTree($data) {
        $result = [];
        foreach ($data as $node) {
            /* $result['id']='node_' . $node['id'];
              $result['text']=Html::encode($node->name);
              $result['icon']=($node['switch'])?'icon-eye':'icon-eye-close';
              $result['state']=array(
              'opened' => ($node->id == 1) ? true : false,
              'switch'=>$node['switch']
              );
              $result['children']=$this->createHtmlTree($node['children']); */

            if (Yii::$app->controller->id == 'admin/category' || Yii::$app->controller->id == 'admin/default') {
                $icon = ($node['switch']) ? 'icon-eye' : 'icon-eye-close';
            } else {
                $icon = '';
            }
            //  $visible = (isset($node->visible)) ? $node->visible : true;
            // if ($visible) {
            $result[] = [
                'id' => 'node_' . $node['id'],
                'text' => Html::encode($node->name) . ' ' . $node['id'],
                'icon' => $icon,
                'state' => array(
                    'opened' => ($node->id == 1) ? true : false,
                //'switch' => $node['switch'],
                //'selected' => (in_array($node->id, $this->selected)) ? true : false
                ),
                'children' => $this->createHtmlTree($node['children'])
            ];
            //  }
        }
        return $result;
    }

}
