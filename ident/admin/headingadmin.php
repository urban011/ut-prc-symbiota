<?php
include_once('../../config/symbini.php');
include_once($serverRoot.'/classes/KeyCharAdmin.php');
header("Content-Type: text/html; charset=".$charset);

if(!$SYMB_UID) header('Location: ../../profile/index.php?refurl=../ident/admin/headingadmin.php?'.$_SERVER['QUERY_STRING']);

$hid = array_key_exists('hid',$_REQUEST)?$_REQUEST['hid']:0;
$action = array_key_exists('action',$_REQUEST)?$_REQUEST['action']:'';
$langId = array_key_exists('langid',$_REQUEST)?$_REQUEST['langid']:'';

$charManager = new KeyCharAdmin();
$charManager->setLangId($langId);

$isEditor = false;
if($isAdmin || array_key_exists("KeyAdmin",$userRights)){
	$isEditor = true;
}

$statusStr = '';
if($isEditor && $action){
	if($action == 'Create'){
		$statusStr = $charManager->addHeading($_POST['headingname'],$_POST['notes'],$_POST['sortsequence']);
	}
	elseif($action == 'Save'){
		$statusStr = $charManager->editHeading($hid,$_POST['headingname'],$_POST['notes'],$_POST['sortsequence']);
	}
	elseif($action == 'Delete'){
		$statusStr = $charManager->deleteHeading($hid);
	}
}
$headingArr = $charManager->getHeadingArr();
$charArr = $charManager->getCharacterArr();
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
	<title>Heading Administration</title>
    <link href="../../css/base.css?<?php echo $CSS_VERSION; ?>" type="text/css" rel="stylesheet" />
    <link href="../../css/main.css?<?php echo $CSS_VERSION; ?>" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="../../js/symb/shared.js"></script>
	<script type="text/javascript">
		function validateHeadingForm(f){
			if(f.headingname.value == ""){
				alert("Heading must have a title");
				return false;
			}
			return true;
		}
	</script>
	<style type="text/css">
		input{ autocomplete: off; } 
	</style>
</head>
<body>
	<!-- This is inner text! -->
	<div id="innertext">
		<?php 
		if($statusStr){
			?>
			<hr/>
			<div style="margin:15px;color:<?php echo (strpos($statusStr,'SUCCESS')===0?'green':'red'); ?>;">
				<?php echo $statusStr; ?>
			</div>
			<hr/>
			<?php 
		}
		if($isEditor){
			?>
			<div style="float:right;margin:10px;">
				<a href="#" onclick="toggle('addheadingdiv');">
					<img src="../../images/add.png" alt="Create New Heading" />
				</a>
			</div>
			<div id="addheadingdiv" style="display:none;">
				<form name="newheadingform" action="headingadmin.php" method="post" onsubmit="return validateHeadingForm(this)">
					<fieldset>
						<legend><b>New Heading</b></legend>
						<div>
							Heading Name<br />
							<input type="text" name="headingname" maxlength="255" style="width:400px;" />
						</div>
						<div style="padding-top:6px;">
							<b>Notes</b><br />
							<input type="text" name="notes" />
						</div>
						<div style="padding-top:6px;">
							<b>Sort Sequence</b><br />
							<input type="text" name="sortsequence" />
						</div>
						<div style="width:100%;padding-top:6px;">
							<button name="action" type="submit" value="Create">Create Heading</button>
						</div>
					</fieldset>
				</form>
			</div>
			<div id="headinglist">
				<?php 
				if($headingArr){
					?>
					<h3>Headings with Characters</h3>
					<?php 
					foreach($headingArr as $headingId => $headArr){
						?>
						<div>
							<a href="#" onclick="toggle('heading-<?php echo $headingId; ?>');"><?php echo $headArr['name']; ?></a>
							<a href="#" onclick="toggle('headingedit-<?php echo $headingId; ?>');"><img src="../../images/edit.png" /></a>
							<div id="headingedit-<?php echo $headingId; ?>" style="display:none;margin:20px;">
								<fieldset style="padding:15px;">
									<legend><b>Heading Editor</b></legend>
									<form name="headingeditform" action="headingadmin.php" method="post" onsubmit="return validateHeadingForm(this)">
										<div style="margin:2px;">
											<b>Heading Name</b><br/>
											<input name="headingname" type="text" value="<?php echo $headArr['name']; ?>" />
										</div>
										<div style="margin:2px;">
											<b>Notes</b><br/>
											<input name="notes" type="text" value="<?php echo $headArr['notes']; ?>" />
										</div>
										<div style="margin:2px;">
											<b>Sort Sequence</b><br/>
											<input name="sortsequence" type="text" value="<?php echo $headArr['sortsequence']; ?>" />
										</div>
										<div>
											<input name="hid" type="hidden" value="<?php echo $headingId; ?>" />
											<button name="action" type="submit" value="Save">Save Edits</button>
										</div>
									</form>
								</fieldset>
								<fieldset style="padding:15px;">
									<legend><b>Delete Heading</b></legend>
									<form name="headingdeleteform" action="headingadmin.php" method="post">
										<input name="hid" type="hidden" value="<?php echo $headingId; ?>" />
										<button name="action" type="submit" value="Delete">Delete Heading</button>
									</form>
								</fieldset>
							</div>
							<div id="heading-<?php echo $headingId; ?>" style="display:none;">
								<?php 
								$charList = $charArr[$headingId];
								foreach($charList as $cid => $charName){
									?>
									<ul>
										<li style="margin-left:10px;">
											<?php echo '<a href="chardetails.php?cid='.$cid.'" target="_blank">'.$charName.'</a>'; ?>
										</li>
									</ul>
									<?php 
								}
								?>
							</div>
						</div>
						<?php 
					}
				}
				else{
					echo '<div style="font-weight:bold;font-size:120%;">There are no existing characters</div>';
				}
				?>
			</div>
			<?php 
		}
		else{
			echo '<h2>You are not authorized to add characters</h2>';
		}
		?>
	</div>
</body>
</html>