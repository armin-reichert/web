<?php
/**
 * Module for birthday reminder.
 * 
 * Displays a birthday reminder where birthdays are read from a Google calendar.
 * 
 * @copyright 2015 Armin Reichert
 */
defined('_JEXEC') or die;
require_once __DIR__ . '/helper.php';

error_reporting(E_ALL);
ini_set("display_errors", 1);

setlocale(LC_ALL, "de_DE");

$clientId = $params->get('clientId');
$calendarId = $params->get('calendarId');

if (empty($clientId)) {
   echo "Google client ID is missing.";
   return;
}
if (empty($calendarId)) {
    echo "Google calendar ID is missing.";
    return;
}
//TODO how to handle exceptions?
$birthdays = ModBirthdayReminderHelper::getBirthdays($clientId, $calendarId);
if (empty($birthdays)) {
    return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
require JModuleHelper::getLayoutPath('mod_birthday_reminder', $params->get('layout', 'default'));
