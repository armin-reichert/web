<?php
$startDay = '';
$startDate = '';
$startDateShort = '';
$startTime = '';
if ($event->getStart()->getDateTime() != null) {
    $eventStartDateTime = strtotime($event->getStart()->getDateTime());
    $startDay = strftime('%A', $eventStartDateTime);
    $startDate = strftime('%e. %B %Y', $eventStartDateTime);
    $startDateShort = strftime('%e.%m.%Y', $eventStartDateTime);
    $startTime = strftime('%H:%M', $eventStartDateTime);
}
if ($event->getEnd()->getDateTime() != null) {
    $eventEndDateTime = strtotime($event->getEnd()->getDateTime());
    $endTime = strftime('%H:%M', $eventEndDateTime);
} else {
    $endTime = '';
}
if ($event->getLocation() != null) {
    $location = preg_replace('/@.*$/', '', $event->getLocation());
    $mapLink = 'https://maps.google.de/maps?q=' . urlencode($event->getLocation());
} else {
    $location = '';
}
$description = $event->getDescription();
$summary = $event->getSummary();
$itemID = "$accordionID-$itemCnt";

function calendar_entry_title($startDateShort, $summary)
{
    echo $startDateShort;
    if (! empty($startDateShort)) {
        echo " - ";
    }
    echo $summary;
}

function calendar_entry_content($startDate, $startDay, $startTime, $endTime)
{
    if (! empty($startDay)) {
        echo ModGoogleCalendarFeedHelper::fa("calendar") . $startDay . ' ' . utf8_encode($startDate);
    }
    if (! empty($startTime)) {
        echo '&nbsp;' . ModGoogleCalendarFeedHelper::fa("clock-o") . $startTime;
    }
    if (! empty($endTime)) {
        echo ' - ' . $endTime . ' Uhr';
    }
}

function calendar_entry_map_link($mapLink)
{
    if (isset($mapLink)) {
        echo "(<a href='" . $mapLink . "' target='_blank'>";
        echo ModGoogleCalendarFeedHelper::fa("location-arrow") . "Karte";
        echo "</a>)";
    }
}
?>

<div class="accordion-item">
	<h2 class="accordion-header" id="<?php echo $itemID; ?>">
		<button class="accordion-button" type="button"
			data-bs-toggle="collapse"
			data-bs-target="#collapse-<?php echo $itemID; ?>">
		<?php calendar_entry_title($startDateShort, $summary) ?>
		</button>
	</h2>
	<div id="collapse-<?php echo $itemID; ?>"
		class="accordion-collapse collapse <?php if ($itemCnt == 0) echo " show"; ?>"
		data-bs-parent="#<?php echo $accordionID; ?>">
		<div class="accordion-body">
			<?php calendar_entry_content($startDate, $startDay, $startTime, $endTime) ?>
			<br /> <span class="google-calendar-feed-item-description"><?php echo $description ?></span>
			<br /> <span class="google-calendar-feed-item-location">
				<?php echo $location ?>
				<?php calendar_entry_map_link($mapLink) ?>
			</span>
		</div>
	</div>
</div>