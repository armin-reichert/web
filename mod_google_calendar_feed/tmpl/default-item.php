<?php
$itemID = "$accordionID-$itemCnt";
$startDay = '';
$startDate = '';
$startDateShort = '';
$startTime = '';
if ($event->getStart ()->getDateTime () != null) {
	$eventStartDateTime = strtotime ( $event->getStart ()->getDateTime () );
	$startDay = strftime ( '%A', $eventStartDateTime );
	$startDate = strftime ( '%e. %B %Y', $eventStartDateTime );
	$startDateShort = strftime ( '%e.%m.%Y', $eventStartDateTime );
	$startTime = strftime ( '%H:%M', $eventStartDateTime );
}
if ($event->getEnd ()->getDateTime () != null) {
	$eventEndDateTime = strtotime ( $event->getEnd ()->getDateTime () );
	$endTime = strftime ( '%H:%M', $eventEndDateTime );
} else {
	$endTime = '';
}
if ($event->getLocation () != null) {
	$location = preg_replace ( '/@.*$/', '', $event->getLocation () );
	$mapLink = 'https://maps.google.de/maps?q=' . urlencode ( $event->getLocation () );
} else {
	$location = '';
}
$description = $event->getDescription ();
$summary = $event->getSummary ();
?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<a href="<?php echo "#$itemID"; ?>" data-toggle="collapse"
				data-parent="<?php echo "#$accordionID"; ?>">
					<?php
					echo $startDateShort;
					if (! empty ( $startDateShort ))
						echo " - ";
					echo $summary;
					?>
					</a>
		</h3>
	</div>
	<div id="<?php echo $itemID; ?>"
		class="panel-collapse collapse<?php if ($itemCnt == 0) echo " in"; ?>">
		<div class="panel-body"><?php
		if (! empty ( $startDay )) {
			echo ModGoogleCalendarFeedHelper::fa ( "calendar" ) . $startDay . ' ' . utf8_encode($startDate);
		}
		if (! empty ( $startTime )) {
			echo '&nbsp;' . ModGoogleCalendarFeedHelper::fa ( "clock-o" ) . $startTime;
		}
		if (! empty ( $endTime )) {
			echo ' - ' . $endTime . ' Uhr';
		}
		?><br /> <span class="google-calendar-feed-item-description"><?php echo $description; ?></span><br />
			<span class="google-calendar-feed-item-location">
			<?php echo $location; ?>
		  <?php if (isset($mapLink)): ?>
		    (<a href="<?php echo $mapLink; ?>" target="_blank"><?php echo ModGoogleCalendarFeedHelper::fa("location-arrow") . "Karte"; ?>
				</a>)
		  <?php endif; ?>
		  </span>
		</div>
	</div>
</div>
