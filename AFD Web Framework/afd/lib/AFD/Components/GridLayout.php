<?php
namespace AFD\Components;

use AFD\View\Renderable;

/**
 * Layout manager that renders components in a grid.
 *
 * @author Armin Reichert
 */
class GridLayout extends Renderable
{

    public $rowCount;

    public $colCount;

    public $spacing;

    public $cellProvider;

    public function __construct()
    {
        $this->rowCount = 3;
        $this->colCount = 3;
        $this->cellProvider = null;
        $this->spacing = "1px";
    }

    public function render()
    {
        echo '<table style="border-collapse: separate; border-spacing: ' . $this->spacing . '">';
        for ($rowIndex = 0; $rowIndex < $this->rowCount; ++ $rowIndex) {
            echo '<tr>';
            for ($colIndex = 0; $colIndex < $this->colCount; ++ $colIndex) {
                if (empty($this->cellProvider)) {
                    continue;
                }
                $cellContent = call_user_func($this->cellProvider, $this, $rowIndex, $colIndex);
                if (empty($cellContent)) {
                    continue;
                }
                echo '<td>';
                if ($cellContent instanceof Renderable) {
                    $cellContent->render();
                } else {
                    echo $cellContent;
                }
                echo '</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}