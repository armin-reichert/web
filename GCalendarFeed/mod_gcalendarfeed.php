<?php
/*-------------------------------------------------------------------------------
# Google Calendar Feed module for Joomla 3.x v2.0.1
# -------------------------------------------------------------------------------
# author    GraphicAholic
# copyright Copyright (C) 2011 GraphicAholic.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.graphicaholic.com
--------------------------------------------------------------------------------*/
// No direct access
defined('_JEXEC') or die('Restricted access');
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
JHtml::_('bootstrap.framework');
// Import the file / foldersystem
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
$LiveSite 	= JURI::base();
$document = JFactory::getDocument();
$modbase = JURI::base(true).'/modules/mod_gcalendarfeed/';
$document->addScript ($modbase.'js/jquery.gcal_flow.js');
$document->addStyleSheet($modbase.'css/jquery.gcal_flow.css');	
$autoScroll	= $params->get('autoScroll', true);
$containerHeight	= $params->get('containerHeight');
$showHeader	= $params->get('showHeader');
$showFooter	= $params->get('showFooter');
if($showFooter == "hide") $containerHeight = "100%";
$seperatorLine	= $params->get('seperatorLine');
if($seperatorLine == "0") $seperatorLine = "dashed";
if($seperatorLine == "1") $seperatorLine = "solid";
$dateFormat	= $params->get('dateFormat');
$fullYear	= $params->get('fullYear');
$dateSeparator	= $params->get('dateSeparator');
if($dateSeparator == "1") $dateSeparator = "/";
if($dateSeparator == "2") $dateSeparator = ".";
if($dateSeparator == "3") $dateSeparator = "-";
$showDescription	= $params->get('showDescription');
$linkItem	= $params->get('linkItem');
$linkTarget	= $params->get('linkTarget');
$moduleID	= $module->id;
require JModuleHelper::getLayoutPath('mod_gcalendarfeed','default',$params);
?>