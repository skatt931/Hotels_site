<div class="subscribe">
	<div class="container">
		<div class="row">
			<div class="col-md-6 text-right">
				<div class="logo">
					<img src="
                <?php
					$qCTours = HT::sel('pictures',
						['select' =>
							'path as pict, pictures.note' . Hl::$pstfx . ' as title',
							'where' => 'attr_id = 6600',
						]);
					echo $qCTours[0]['pict'];
					?>
                " alt="">
				</div>
			</div>
			<div class="col-md-6">
				<p class="descr">
					<?= Feedbacks::get(['attr_id' => 6600])[0]['note' . Hl::$pstfx];
					?>
				</p>
			</div>
		</div>
		<form id="subscribeForm">
			<div class="orang">
				<?= $qCTours[0]['title']; ?>
			</div>
			<label for="subscribeEmail">
				<input id="subscribeEmail" type="email" placeholder="Ваш e-mail" name="email">
			</label>
			<label for="subscribe">
				<input type="submit" class="btn btn-default" id="subscribe" value="<?= Attrtypes::get(6600)['name'] ?>">
			</label>
		</form>
	</div>
</div>