<?php
namespace AFD\View;

/**
 * Common base class for views.
 *
 * Views are UI parts that can be rendered and can deliver their content as a string.
 *
 * @author Armin Reichert
 */
abstract class View extends Renderable
{
    use HTML, URL;
}