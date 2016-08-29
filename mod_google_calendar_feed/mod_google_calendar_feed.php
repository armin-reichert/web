<?php
/**
 * Module GoogleCalendarFeed.
 * 
 * Displays a number of events from a Google calendar feed.
 * 
 * @copyright 2015 Armin Reichert
 */
defined('_JEXEC') or die();
require_once __DIR__ . '/helper.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);
setlocale(LC_ALL, "de_DE");

$clientId = $params->get('clientId');
$calendarId = $params->get('calendarId');
$maxItemCount = $params->get('maxItemCount', 5);
$layout = $params->get('layout', 'default');
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

if (empty($clientId)) {
    echo "Google client ID is missing.";
    return;
}
if (empty($calendarId)) {
    echo "Google calendar ID is missing.";
    return;
}

$events = ModGoogleCalendarFeedHelper::getEvents($clientId, $calendarId, $maxItemCount);

require JModuleHelper::getLayoutPath('mod_google_calendar_feed', $layout);
