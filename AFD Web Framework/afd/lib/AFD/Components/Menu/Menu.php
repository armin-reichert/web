<?php
namespace AFD\Components\Menu;

use AFD\Controller;
use AFD\View\Template;

/**
 * A simple menu implementation.
 */
class Menu
{

    const ITEM_INDEX = "itemIndex";

    private $items;

    public function __construct()
    {
        $this->items = array();
    }

    public function addItem($text, $controller, $params = array())
    {
        $params['itemIndex'] = count($this->items);
        $this->items[] = array(
            'text' => $text,
            'url' => $controller->createURL($params)
        );
    }

    public function getSelectedIndex()
    {
        if (! empty($_GET[self::ITEM_INDEX])) {
            $index = $_GET[self::ITEM_INDEX];
        } else {
            $index = 0;
        }
        if ($index >= 0 && $index < count($this->items)) {
            return $index;
        }
        return - 1;
    }

    public function getView()
    {
        $view = new Template(__DIR__ . '/MenuView.php');
        $view->set('items', $this->items);
        $view->set('selectedIndex', $this->getSelectedIndex());
        return $view;
    }
}