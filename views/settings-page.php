<div class="wrap">
	<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

	<form action="options.php" method="POST">
		
		<?php settings_fields('cc_form_group'); ?>

		<?php do_settings_sections('cc_form_page1'); ?>
		
		<?php submit_button('Zapisz klucz API'); ?>
	</form>
</div>