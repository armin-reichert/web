<?php
/**
 * Default template of birthday reminder module.
 */
defined('_JEXEC') or die();

$doc = JFactory::getDocument();
$doc->addStyleSheet('modules/mod_birthday_reminder/assets/default.css');
?>
<?php if (!empty($module->title)): ?>
<h3 class="birthday-reminder-title">
	<i class="fa fa-heart"></i>&nbsp;<?php echo $module->title?>
</h3>
<?php endif; ?>
<table class="birthday-reminder-container<?php echo $moduleclass_sfx ?>">
<?php foreach ($birthdays as $event): ?>
<?php

    $today = new DateTime('today');
    $month = $today->format('m');
    $day = $today->format('d');
    $name = $event->getSummary();
    $birthYear = $event->getDescription();
    $birthDate = new DateTime("$birthYear-$month-$day");
    $age = $today->diff($birthDate)->y;
    $cartoon = ModBirthdayReminderHelper::getRandomCartoon();
    ?>
  <tr>
		<td><strong><?php echo $name . " ($age)"; ?></strong></td>
	</tr>
  <tr>
    <td><img src="<?php echo $cartoon; ?>"></td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>
	<?php endforeach; ?>
</table>
