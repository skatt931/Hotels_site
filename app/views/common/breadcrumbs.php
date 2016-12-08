<?php if (!isset($aAttrs)) $aAttrs = Attrtypes::all(); ?>
<div class="crumbs_wrap">
	<ol class="breadcrumb my_breadcrumbs">
		<li><a href="<?= Hl::url('/') ?>"><?= $aAttrs[6500]['name'] ?> </a></li>
		<?php
		$lastBC = count($aBreadCrumbs) - 1;
		foreach ($aBreadCrumbs as $kBC => $vBC) { ?>
			<li><i class="fa fa-angle-right"></i></li>
			<li
			<?php
			if ($kBC == $lastBC)
				echo ' class="active">' . $vBC['name'];
			else
				echo '><a ' . 'href="' . $vBC['url'] . '">' . $vBC['name'] . '</a>';
			?>
			</li>
		<?php } ?>
	</ol>
</div>