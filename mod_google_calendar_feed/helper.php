<?php
defined('_JEXEC') or die();

require JPATH_BASE . '/vendor/autoload.php';

class ModGoogleCalendarFeedHelper
{

    public static function getEvents($clientID, $calendarID, $maxItemCount)
    {
        date_default_timezone_set('Europe/Berlin'); //TODO should be set in php.ini
        $client = new Google_Client();
        $client->setDeveloperKey($clientID);
        $calendar = new Google_Service_Calendar($client);
        $optParams = [
            'singleEvents' => true,
            'orderBy' => 'startTime',
            'timeMin' => date(DateTime::ATOM), // ONLY PULL EVENTS STARTING TODAY
            'maxResults' => $maxItemCount
        ];
        $events = $calendar->events->listEvents($calendarID, $optParams);
        return $events->getItems();
    }

    public static function fa($icon)
    {
        return "<i class=\"fa fa-$icon\"></i> ";
    }
}
