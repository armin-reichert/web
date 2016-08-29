<?php
namespace AFD\Components;

use AFD\View\Renderable;

/**
 * Layout manager that renders a list of components after each other.
 *
 * @author Armin Reichert
 */
class FlowLayout extends Renderable
{

    private $components;

    public function __construct($components = array())
    {
        $this->components = $components;
    }

    public function addComponent($component)
    {
        $this->components[] = $component;
    }

    public function render()
    {
        echo '<span class="FlowLayout">';
        foreach ($this->components as $component) {
            if ($component instanceof Renderable) {
                $component->render();
            } else {
                echo $component;
            }
        }
        echo '</span>';
    }
}