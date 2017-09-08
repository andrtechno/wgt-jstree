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
    public $allOpen = false;

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
        'default' => [
                "icon" =>"icon-folder-open",
        ],
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
            'core' => array_merge(['data' => $this->createDataTree($this->data)], $this->core),

            'checkbox' => $this->checkbox,
            'contextmenu' => $this->contextmenu,
            'dnd' => $this->dnd,
            'search' => $this->search,
            'sort' => $this->sort,
            'state' => $this->state,
            'plugins' => $this->plugins,
            'types' => $this->types
        ];

        //$defaults = Json::encode(array_merge($config,['contextmenu'=>'customMenu()']));
        $defaults = Json::encode($config);

        $inputId = (!$this->hasModel()) ? $this->options['id'] : Html::getInputId($this->model, $this->attribute);




        $view->registerJs("$('#jsTree_{$this->options['id']}').jstree({$defaults})");

    }


    


    private function createDataTree($data) {
        $result = [];

        foreach ($data as $node) {

            if (basename(get_class($this->view->context)) == 'CategoryController') {
                $icon = ($node['switch']) ? 'icon-eye' : 'icon-eye-close';
            } else {
                $icon = '';
            }
            $result[] = [
                'id' => 'node_' . $node->id,
                'text' => Html::encode($node->name) . ' ' . $node->id,
                'icon' => $icon,
                'data'=>['is_switch'=>$node->switch],
                'state' => [
                    'opened' => ($this->allOpen || $node->id == 1) ? true : false,
                ],
                'children' => $this->createDataTree($node['children'])
            ];
        }
        return $result;
    }

}
