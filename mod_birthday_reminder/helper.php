<?php
defined('_JEXEC') or die();

require JPATH_BASE . '/vendor/autoload.php';

/**
 * Helper for birthday reminder module.
 */
class ModBirthdayReminderHelper
{

    public static function getBirthdays($clientId, $calendarId)
    {
        $client = new Google_Client();
        $client->setDeveloperKey($clientId);
        $calendar = new Google_Service_Calendar($client);
        $optParams = [
            'singleEvents' => true,
            'orderBy' => 'startTime',
            'timeMin' => date(DateTime::ATOM),
            'timeMax' => date(DateTime::ATOM, (new DateTime('tomorrow'))->getTimestamp())
        ];
        $events = $calendar->events->listEvents($calendarId, $optParams);
        return $events->getItems();
    }
    
    public static function getRandomCartoon() {
        $number = rand(0, 14);
        return JURI::base() . 'images/birthday/cartoons/' . $number . '.jpg';
    }
}
