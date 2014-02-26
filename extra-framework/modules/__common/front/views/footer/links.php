<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 11/02/2014
 * Time: 11:56
 */
global $extra_options;

?>

<div class="footer-links">
	<?php for ($i = 1; $i <= 3; $i++) : ?>
		<div class="footer-link">
			<div class="footer-link-title"><span class="icon <?php echo $extra_options['footer_link_icon_'.$i] ?>"></span><?php echo $extra_options['footer_link_title_'.$i]; ?></div>
			<a href="<?php echo $extra_options['footer_link_url_'.$i]; ?>"><?php echo $extra_options['footer_link_subtitle_'.$i]; ?></a>
		</div>
	<?php endfor; ?>
</div>