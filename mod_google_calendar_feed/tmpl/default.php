<?php
/**
 * Renders the calendar feed using a Bootstrap accordion.
 * 
 * @author Armin Reichert
 * @since 2015
 */
defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addStyleSheet('/modules/mod_google_calendar_feed/assets/default.css');

$accordionID = "gcf-accordion-" . $module->id;
$itemCnt = 0;
$itemID = "";
?>
<?php if (!empty($module->title)): ?>
<h3 class="google-calendar-feed-title">
	<?php echo ModGoogleCalendarFeedHelper::fa("calendar") . $module->title; ?>
</h3>
<?php endif; ?>
<div id="<?php echo $accordionID; ?>" class="accordion">
<?php foreach ($events as $event): ?>
<?php include 'default-item.php'; $itemCnt += 1; ?>
<?php endforeach; ?>
</div>
