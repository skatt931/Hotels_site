<div class="wrapper wrapp" id="wrapper">
	<?php include( __DIR__ . "/../common/sidebar_menu.php" ); ?>
	<header class="home">
		<div class="container">
			<?php include __DIR__ . '/../common/header.php'; ?>
			<?php include __DIR__ . '/../common/search_form.php'; ?>
		</div>
	</header>
	<div class="main">
		<?php
		foreach ( $blocks as $block ) {
			$fileName = __DIR__ . '/' . $block[ "path" ];
			if ( file_exists( $fileName ) )
				include $fileName;
		}
		?>
	</div>
	<footer>
		<?php include __DIR__ . '/../common/footer.php'; ?>
	</footer>
	<?php include __DIR__ . '/../common/footer_js.php'; ?>
</div>
