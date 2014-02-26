<?php $mb->the_field('allow_social');
$value = $mb->get_the_value();
?>
<p>
<input id="<?php $metabox->the_name(); ?>-on" name="<?php $metabox->the_name(); ?>"<?php if($value == "on" || empty($value)) echo ' checked="checked"'; ?> type="radio" value="on"/>
<label for="<?php $mb->the_name(); ?>-on"><?php _e("Afficher", "extra"); ?></label>
</p>
<p>
<input id="<?php $metabox->the_name(); ?>-off" name="<?php $metabox->the_name(); ?>"<?php if($value == "off") echo ' checked="checked"'; ?> type="radio" value="off"/>
<label for="<?php $mb->the_name(); ?>-off"><?php _e("Ne pas afficher", "extra"); ?></label>
</p>