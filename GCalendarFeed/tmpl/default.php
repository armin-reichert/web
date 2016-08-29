<?php
/*
 * -------------------------------------------------------------------------------
 * # Google Calendar Feed module for Joomla 3.x v2.0.1
 * # -------------------------------------------------------------------------------
 * # author GraphicAholic
 * # copyright Copyright (C) 2011 GraphicAholic.com. All Rights Reserved.
 * # @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * # Websites: http://www.graphicaholic.com
 * --------------------------------------------------------------------------------
 */
// No direct access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$path = $params->get('path');
?>

<div id="gcf-simple-<?php echo $moduleID ?>" class="gCalFlow" 
	style="height:<?php echo $params->get('gcalHeight') ?> !important; width:<?php echo $params->get('gcalWidth') ?> !important; margin-bottom: <?php echo $params->get('bottomMargin') ?> !important; color: <?php echo $params->get('containerText') ?>;">
	<?php if ($showHeader == "show"): ?>
		<div class="gcf-header-block" style="background: <?php echo $params->get('headerColor') ?>">
		<div class="gcf-title-block" style="color: <?php echo $params->get('headerText') ?>">
			<h3 class="gcf-title1">
				<i class="fa fa-calendar"></i>&nbsp;<?php echo $params->get('gcalTitle') ?></h3>
		</div>
	</div>
	<?php endif; ?>
	<div class="gcf-item-container-block" style="height:<?php echo $containerHeight ?>; background: <?php echo $params->get('containerColor') ?>">
		<div class="gcf-item-block" style="border-bottom: <?php echo $params->get('seperatorSize') ?> <?php echo $seperatorLine ?> <?php echo $params->get('seperatorColor') ?>;">
			<div class="gcf-item-header-block">
				<div class="gcf-item-title-block>
					<a  class="gcf-item-link">
					<span class="gcf-item-date">2012-02-01 09:00</span>:</a> <strong
						class="gcf-item-title">Termine werden geladen...</strong>
		<?php if ($showDescription == "2"): ?>
			<div class="gcf-item-body-block">
						<span class="gcf-item-description" style="color: <?php echo $params->get('descriptionColor') ?>;"></span>
					</div>
		<?php endif ?>	
	</div>
			</div>
		</div>
	</div>
	<?php if ($showFooter == "show"): ?>
		<div class="gcf-last-update-block" style="line-height: 15px; color: <?php echo $params->get('footerText') ?>; background: <?php echo $params->get('footerColor') ?>;">
		<?php echo $params->get('footerData') ?>: <span
			class="gcf-last-update">2012-02-30 20:58</span>
	</div>
	<?php endif ; ?>
</div>
<script type="text/javascript">
jQuery('#gcf-simple-<?php echo $moduleID ?>').gCalFlow({
	calid: '<?php echo $params->get('gcalID') ?>',
	maxitem: <?php echo $params->get('maxItems') ?>,
	apikey: '<?php echo $params->get('gcalAPI') ?>',
	auto_scroll: <?php echo $autoScroll ?>,
	mode: 'upcoming',
	scroll_interval: <?php echo $params->get('scrollSpeed') ?> * 1000,
	link_title: true,
	link_item_title: <?php echo $params->get('linkItem') ?>,	
	link_target: '<?php echo $params->get('linkTarget') ?>',	
	no_items_html: '<?php echo $params->get('noEvent') ?>',
	<?php if ($dateFormat == "1") : ?>
	date_formatter: function(d, allday_p) { function pad(n) { return n < 10 ? "0"+n : n; } return pad(d.getMonth()+1) + "<?php echo $dateSeparator ?>" + pad(d.getDate()) + "<?php echo $dateSeparator ?>" + d.getFullYear().toString().substr(<?php echo $params->get('fullYear') ?>) }
	<?php endif ; ?>	
	<?php if ($dateFormat == "2" /* D.M.Y */) : ?>
	date_formatter: function(d, allday_p) { 
		var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
		return d.toLocaleDateString('de-DE', options);
	}
	<?php endif ; ?>	
	<?php if ($dateFormat == "3") : ?>
	date_formatter: function(d, allday_p) { function pad(n) { return n < 10 ? "0"+n : n; } return d.getFullYear().toString().substr(<?php echo $params->get('fullYear') ?>) + "<?php echo $dateSeparator ?>" + pad(d.getMonth()+1) + "<?php echo $dateSeparator ?>" + pad(d.getDate()) }
	<?php endif ; ?>
	});
</script>