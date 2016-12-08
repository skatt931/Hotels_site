<!--<div class="col-md-3 col-sm-6">
<h2><?php
/* $aGoals = Hl::getAttrs(5500,false,true);
echo $aGoals[5500]['name']; */ ?>
</h2>
<div class="goal-trip">
<?php
/*foreach ($aGoals as $kGoal=>$vGoal)
if ($kGoal != 5500) {
*/ ?>
<p>
<label>
<input type="checkbox" name="goal_<? /*= $kGoal */ ?>">
<span class="checkboxx"></span><span><?php /*echo $vGoal['name']; */ ?></span>
</label>
</p>
<?php /*} */ ?>
</div>
</div>-->

<div class="col-sm-3 no-padding">
	<h2><?php
		$aGoals = Hl::getAttrs( 5500, false, true );
		echo $aGoals[ 5500 ][ 'name' ]; ?></h2>
	<div class="goal-trip">
		<p>
			<label>
				<input type="checkbox" name="working-affairs">
				<span class="checkboxx"></span><span><strong>BB</strong> <small>(тільки сніданки)</small></span>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" name="vacation">
				<span class="checkboxx"></span><span><strong>FB</strong> <small>(повний пансіон)</small></span>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" name="honeymoon">
				<span class="checkboxx"></span><span><strong>AI</strong> <small>(все включено)</small></span>
			</label>
		</p>
		<p>
			<label>
				<input type="checkbox" name="sanitation">
				<span class="checkboxx"></span><span><strong>FBT</strong> <small>(повний пансіон + лікування)</small></span>
			</label>
		</p>
	</div>
</div>
