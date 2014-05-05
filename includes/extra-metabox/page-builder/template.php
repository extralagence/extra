<?php
/**
 * @var $mb \ExtraPageBuilder
 */
$mb;
?>

<div class="extra-page-builder">
	<div class="repeatable">

		<div class="repeat-actions">
			<a href="#" class="docopy-page_builder copy-btn"><div class="dashicons dashicons-plus"></div><?php echo ($this->add_label == null) ? __("Ajouter une ligne", "extra") : $this->add_label; ?></a>
			<a href="#" class="dodelete-page_builder delete-btn"><div class="icon-extra-page-builder icon-extra-page-builder-cross"></div><?php _e("Tout supprimer", "extra"); ?></a>
		</div>

		<?php while($mb->have_fields_and_multi("page_builder")) : ?>
			<?php $mb->the_group_open();
			?>
			<div class="extra-page-builder-row">
				<?php
				$mb->the_field('page_builder_row_type');
				$page_builder_row_type = $mb->get_the_value();
				?>
				<input class="extra-page-builder-row-type" type="hidden" name="<?php $mb->the_name(); ?>" value="<?php echo (!empty($page_builder_row_type)) ? $mb->get_the_value() : ''; ?>">

				<div class="extra-page-builder-row-admin">
					<div class="grip">
					</div>
					<a href="#" class="layout-selected layout-button"><span class="icon-extra-page-builder icon-extra-page-builder-<?php echo (!empty($page_builder_row_type)) ? $mb->get_the_value() : '1'; ?>"></span></a>
					<div class="layout-choices">
						<a href="#layout1" class="layout-button"><span class="icon-extra-page-builder icon-extra-page-builder-1"></span></a>
						<a href="#layout12" class="layout-button"><span class="icon-extra-page-builder icon-extra-page-builder-12"></span></a>
						<a href="#layout21" class="layout-button"><span class="icon-extra-page-builder icon-extra-page-builder-21"></span></a>
						<a href="#layout11" class="layout-button"><span class="icon-extra-page-builder icon-extra-page-builder-11"></span></a>
						<a href="#layout111" class="layout-button"><span class="icon-extra-page-builder icon-extra-page-builder-111"></span></a>
					</div>
					<a href="#" class="dodelete"><span class="icon-extra-page-builder icon-extra-page-builder-cross"></span></a>
				</div>

				<div class="extra-page-builder-row-content extra-page-builder-row-content-<?php echo (!empty($page_builder_row_type)) ? $page_builder_row_type : '1'; ?>">
					<?php
					$mb->the_block(1);
					?>
					<?php
					$mb->the_block(2);
					?>
					<?php
					$mb->the_block(3);
					?>
				</div>
			</div>
			<?php $mb->the_group_close(); ?>
		<?php endwhile; ?>

		<div class="extra-page-builder-modal" style="display: none;">
			Ceci est une modal.
		</div>
	</div>
</div>