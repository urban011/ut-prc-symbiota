<?php
include_once('../config/symbini.php');
include_once($serverRoot.'/classes/OccurrenceManager.php');
header("Content-Type: text/html; charset=".$charset);

$catId = array_key_exists("catid",$_REQUEST)?$_REQUEST["catid"]:0;
if(!$catId && isset($DEFAULTCATID) && $DEFAULTCATID) $catId = $DEFAULTCATID;

$collManager = new OccurrenceManager();
$collManager->reset();

$collList = $collManager->getFullCollectionList($catId);
$specArr = (isset($collList['spec'])?$collList['spec']:null);
$obsArr = (isset($collList['obs'])?$collList['obs']:null);

$otherCatArr = $collManager->getOccurVoucherProjects();
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
		<title><?php echo $defaultTitle; ?> Collections Search</title>
		<link href="../css/base.css?<?php echo $CSS_VERSION; ?>" type="text/css" rel="stylesheet" />
		<link href="../css/main.css?<?php echo $CSS_VERSION; ?>" type="text/css" rel="stylesheet" />
		<link href="../css/jquery-ui.css" type="text/css" rel="Stylesheet" />
		<script src="../js/jquery.js" type="text/javascript"></script>
		<script src="../js/jquery-ui.js" type="text/javascript"></script>
		<script src="../js/symb/collections.index.js" type="text/javascript"></script>
		<script type="text/javascript">
			<?php include_once($serverRoot.'/config/googleanalytics.php'); ?>
		</script>
	</head>
	<body>
	
	<?php
	$displayLeftMenu = (isset($collections_indexMenu)?$collections_indexMenu:false);
	include($serverRoot."/header.php");
	if(isset($collections_indexCrumbs)){
		if($collections_indexCrumbs){
			echo "<div class='navpath'>";
			echo $collections_indexCrumbs;
			echo " <b>Collections</b>";
			echo "</div>";
		}
	}
	else{
		echo "<div class='navpath'>";
		echo "<a href='../index.php'>Home</a> &gt;&gt; ";
		echo "<b>Collections</b>";
		echo "</div>";
	}
	?>
	<!-- This is inner text! -->
	<div id="innertext">
		<h1>Collections to be Searched</h1>
		<div id="tabs" style="margin:0px;">
			<ul>
				<?php 
				if($specArr && $obsArr) echo '<li><a href="#specobsdiv">Specimens &amp; Observations</a></li>';
				if($specArr) echo '<li><a href="#specimendiv">Specimens</a></li>';
				if($obsArr) echo '<li><a href="#observationdiv">Observations</a></li>';
				if($otherCatArr) echo '<li><a href="#otherdiv">Federal Units</a></li>';
				?>
			</ul>
			<?php
			if($specArr && $obsArr){
				?>
				<div id="specobsdiv">
					<form name="collform1" action="harvestparams.php" method="post" onsubmit="return verifyCollForm(this)">
						<div style="margin:0px 0px 10px 20px;">
							<input id="dballcb" name="db[]" class="specobs" value='all' type="checkbox" onclick="selectAll(this);" checked />
					 		Select/Deselect all <a href="<?php echo $clientRoot; ?>/collections/misc/collprofiles.php">Collections</a>
						</div>
						<?php 
						$collManager->outputFullCollArr($specArr); 
						if($specArr && $obsArr) echo '<hr style="clear:both;margin:20px 0px;"/>'; 
						$collManager->outputFullCollArr($obsArr);
						?>
						<div style="clear:both;">&nbsp;</div>
					</form>
				</div>
			<?php 
			}
			if($specArr){
				?>
				<div id="specimendiv">
					<form name="collform2" action="harvestparams.php" method="post" onsubmit="return verifyCollForm(this)">
						<div style="margin:0px 0px 10px 20px;">
							<input id="dballspeccb" name="db[]" class="spec" value='allspec' type="checkbox" onclick="selectAll(this);" checked />
					 		Select/Deselect all <a href="<?php echo $clientRoot; ?>/collections/misc/collprofiles.php">Collections</a>
						</div>
						<?php
						$collManager->outputFullCollArr($specArr);
						?>
						<div style="clear:both;">&nbsp;</div>
					</form>
				</div>
				<?php 
			}
			if($obsArr){
				?>
				<div id="observationdiv">
					<form name="collform3" action="harvestparams.php" method="post" onsubmit="return verifyCollForm(this)">
						<div style="margin:0px 0px 10px 20px;">
							<input id="dballobscb" name="db[]" class="obs" value='allobs' type="checkbox" onclick="selectAll(this);" checked />
							Select/Deselect all <a href="<?php echo $clientRoot; ?>/collections/misc/collprofiles.php">Collections</a>
						</div>
						<?php
						$collManager->outputFullCollArr($obsArr);
						?>
						<div style="clear:both;">&nbsp;</div>
					</form>
				</div>
				<?php 
			} 
			if($otherCatArr && isset($otherCatArr['titles'])){
				$catTitleArr = $otherCatArr['titles']['cat'];
				asort($catTitleArr);
				?>
				<div id="otherdiv">
					<form id="othercatform" action="harvestparams.php" method="post" onsubmit="return verifyOtherCatForm(this)">
						<?php
						foreach($catTitleArr as $catPid => $catTitle){
							?>
							<fieldset style="margin:10px;padding:10px;">
								<legend style="font-weight:bold;"><?php echo $catTitle; ?></legend>
								<div style="margin:0px 15px;float:right;">
                                    <button title="next" title="Click button to advance to the next step" class="link-button">next ></button>
<!--									<input type="image" src='../images/next.png'-->
<!--										onmouseover="javascript:this.src = '../images/next_rollover.png';" -->
<!--										onmouseout="javascript:this.src = '../images/next.png';"-->
<!--										title="Click button to advance to the next step" />-->
								</div>
								<?php
								$projTitleArr = $otherCatArr['titles'][$catPid]['proj'];
								asort($projTitleArr);
								foreach($projTitleArr as $pid => $projTitle){
									?>
									<div>
										<a href="#" onclick="togglePid('<?php echo $pid; ?>');return false;"><img id="plus-pid-<?php echo $pid; ?>" src="../images/plus_sm.png" /><img id="minus-pid-<?php echo $pid; ?>" src="../images/minus_sm.png" style="display:none;" /></a>
										<input name="pid[]" type="checkbox" value="<?php echo $pid; ?>" onchange="selectAllPid(this);" />
										<b><?php echo $projTitle; ?></b>
									</div>
									<div id="pid-<?php echo $pid; ?>" style="margin:10px 15px;display:none;">
										<?php 
										$clArr = $otherCatArr[$pid];
										asort($clArr);
										foreach($clArr as $clid => $clidName){
											?>
											<div>
												<input name="clid[]" class="pid-<?php echo $pid; ?>" type="checkbox" value="<?php echo $clid; ?>" />
												<?php echo $clidName.'asd'; ?>
											</div>
											<?php
										} 
										?>
									</div>
									<?php
								} 
								?>
							</fieldset>
							<?php 
						}
						?>
					</form>
				</div>
				<?php 
			}
			?>
		</div>
	</div>
	<?php
	include($serverRoot."/footer.php");
	?>
	</body>
</html>