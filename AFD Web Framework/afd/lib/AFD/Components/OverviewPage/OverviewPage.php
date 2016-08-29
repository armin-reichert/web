<?php
namespace AFD\Components\OverviewPage;

use AFD\Controller\App;
use AFD\View\Template;
use AFD\View\View;
use AFD\Components\FlowLayout;

/**
 * This component realizes an overview page showing the teasers and navigation links for a given
 * list of pages or the pages inside a directory.
 *
 * @author Armin Reichert
 */
class OverviewPage
{

    const TEASER_FILENAME = 'description.php';

    /**
     *
     * @var string
     */
    private $pageRootDir;

    /**
     *
     * @var string
     */
    private $route;

    /**
     *
     * @var string
     */
    private $title;

    /**
     *
     * @var string
     */
    private $description;

    /**
     *
     * @var bool
     */
    private $showGroups;

    /**
     *
     * @var bool
     */
    private $showGroupHeaders;

    /**
     *
     * @var array
     */
    private $pages = [];

    /**
     * Constructor.
     *
     * @param string $pageRootDir
     *            directory under which content pages are stored
     * @param string $route
     *            the route for creating the URLs to the single pages
     */
    public function __construct($pageRootDir, $route)
    {
        $this->pageRootDir = $pageRootDir;
        $this->route = $route;
    }

    /**
     * Sets the title of the overview page.
     * Can be omitted if the teaser contains already a title which must be tagged as h2.
     *
     * @param string $title
     *            title as plain text
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Sets the description text for this overview page.
     *
     * @param string $description
     *            description as HTML (will not get escaped again!)
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Tells if the pages should be displayed in groups.
     *
     * @param boolean $showGroups
     *            tells if page list is displayed in groups
     */
    public function setShowGroups($showGroups)
    {
        $this->showGroups = $showGroups;
    }

    /**
     * Tells if the group headers should be displayed.
     *
     * @param boolean $showGroupHeaders
     *            tells if group headers are displayed
     */
    public function setShowGroupHeaders($showGroupHeaders)
    {
        $this->showGroupHeaders = $showGroupHeaders;
    }

    /**
     * Sets the pages to be shown in this overview page.
     *
     * @param array $pages
     *            array containing the page IDs
     */
    public function setPages(array $pages)
    {
        $this->pages = $pages;
    }

    /**
     * Orders the page list explicitly be the given array of keys.
     *
     * @param array $ordering
     *            array of keys
     */
    public function order(array $keys)
    {
        $ordered = [];
        foreach ($keys as $key) {
            if (isset($this->pages[$key])) {
                $ordered[] = $this->pages[$key];
                unset($this->pages[$key]);
            }
        }
        // add remaining elements
        foreach ($this->pages as $entry) {
            $ordered[] = $entry;
        }
        $this->pages = $ordered;
    }

    /**
     * Sorts the page list ascending (<code>asc === true</code>) or descending.
     *
     * @param boolean $asc
     *            tells if sorting should be done in ascending order
     */
    public function sort($asc)
    {
        if ($asc) {
            ksort($this->pages);
        } else {
            krsort($this->pages);
        }
    }

    /**
     * Returns the view containing the teasers and links to the pages.
     *
     * @return \AFD\View\Template
     */
    public function getView()
    {
        return new Template(__DIR__ . '/OverviewPageView.php', [
            'title' => $this->title,
            'description' => $this->description,
            'content' => $this->getPageLayout()
        ]);
    }

    /**
     * Returns the layout containing the overview parts for the pages (teaser and link).
     *
     * @return \Components\FlowLayout
     */
    private function getPageLayout()
    {
        $layout = new FlowLayout();
        $prevGroupKey = null;
        foreach ($this->pages as $key => $pageID) {
            $entryParts = [];
            if ($this->showGroups) {
                $groupKey = $this->getGroupKey($key);
                if ($prevGroupKey !== $groupKey) {
                    $headerText = ($this->showGroupHeaders ? $this->massageGroupHeaderText($groupKey) : '');
                    $heading = '<h2 class="overviewPageGroupHeader">' . $headerText . '</h2>';
                    $entryParts[] = $heading;
                    $prevGroupKey = $groupKey;
                }
            }
            $teaserPath = realpath($this->pageRootDir . DS . $pageID . DS . self::TEASER_FILENAME);
            if (false !== $teaserPath) {
                $entryParts[] = new Template($teaserPath);
            }
            $readMoreURL = App::app()->controllerURL($this->route, [
                'page' => $pageID
            ]);
            // TODO: how to avoid URL encoding of path parameter?
            $readMoreURL = str_replace('%2F', '/', $readMoreURL);
            $entryParts[] = $this->readMoreButton($readMoreURL);
            $layout->addComponent(new FlowLayout($entryParts));
            $layout->addComponent('<p style="height:0.5em">&nbsp;</p>');
        }
        return $layout;
    }

    private function readMoreButton($url)
    {
        return '<a class="afdButton" href="' . $url . '" target="_self" title="Zum vollständigen Artikel">Weiterlesen&hellip;</a>' . "\n";
    }

    /**
     * Returns the prefix of the given key used for grouping.
     *
     * @param string $key
     *            key in overview list, for example directory name
     * @return string part of key used for grouping
     */
    private function getGroupKey($key)
    {
        $i = strpos($key, '_');
        if (false !== $i) {
            return substr($key, 0, $i);
        }
        return $key;
    }

    /**
     * Returns the massaged group header text for the given group key.
     *
     * @param string $groupKey
     *            the group key, for example the prefix of a directory name up to the first underscore
     * @return string the corresponding display text
     */
    private function massageGroupHeaderText($groupKey)
    {
        if ($groupKey === '0000') {
            return "Ältere/Sonstige";
        } else
            if ($groupKey === '9999') {
                return "Neuere/Sonstige";
            }
        return $groupKey;
    }
}
