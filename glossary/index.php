<?php
include_once('../config/symbini.php');
include_once($serverRoot.'/classes/GlossaryManager.php');
header("Content-Type: text/html; charset=".$charset);

$glossId = array_key_exists('glossid',$_REQUEST)?$_REQUEST['glossid']:0;
$glossgrpId = array_key_exists('glossgrpid',$_REQUEST)?$_REQUEST['glossgrpid']:0;
$language = array_key_exists('language',$_REQUEST)?$_REQUEST['language']:'';
$tId = array_key_exists('tid',$_REQUEST)?$_REQUEST['tid']:'';
$formSubmit = array_key_exists('formsubmit',$_POST)?$_POST['formsubmit']:'';

$glosManager = new GlossaryManager();
$termList = '';

$statusStr = '';
if($formSubmit){
	if($formSubmit == 'Search Terms'){
		$termList = $glosManager->getTermList($_POST['searchtermkeyword'],$_POST['searchdefkeyword'],$_POST['searchlanguage'],$_POST['searchtaxa']);
		$language = $_POST['searchlanguage'];
		$tId = $_POST['searchtaxa'];
	}
	if($formSubmit == 'Delete Term'){
		$statusStr = $glosManager->deleteTerm($glossId,$glossgrpId);
		$glossId = 0;
	}
}
elseif($language && $tId){
	$termList = $glosManager->getTermList('','',$language,$tId);
}
elseif(!$formSubmit || $formSubmit != 'Search Terms'){
	$termList = $glosManager->getTermList('','',$defaultLang,'');
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title><?php echo $defaultTitle; ?> Glossary</title>
    <link href="../css/base.css?<?php echo $CSS_VERSION; ?>" rel="stylesheet" type="text/css" />
    <link href="../css/main.css?<?php echo $CSS_VERSION; ?>" rel="stylesheet" type="text/css" />
	<link href="../css/jquery-ui.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery-ui.js"></script>
	<script type="text/javascript" src="../js/symb/glossary.index.js"></script>
</head>
<body>
	<?php
	$displayLeftMenu = (isset($glossary_indexMenu)?$glossary_indexMenu:false);
	include($serverRoot."/header.php");
	if(isset($glossary_indexCrumbs)){
		if($glossary_indexCrumbs){
			?>
			<div class='navpath'>
				<a href='../index.php'>Home</a> &gt;&gt; 
				<?php echo $glossary_indexCrumbs; ?>
				<a href='index.php'> <b>Glossary Management</b></a>
			</div>
			<?php 
		}
	}
	else{
		?>
		<div class='navpath'>
			<a href='../index.php'>Home</a> &gt;&gt; 
			<a href='index.php'> <b>Glossary Management</b></a>
		</div>
		<?php 
	}
	?>
	<!-- This is inner text! -->
	<div id="innertext">
		<?php 
		if($statusStr){
			?>
			<div style="margin:15px;color:red;">
				<?php echo $statusStr; ?>
			</div>
			<?php 
		}
		?>
		<div id="" style="float:right;width:240px;">
			<form name="filtertermform" action="index.php" method="post">
				<fieldset style="background-color:#FFD700;">
					<legend><b>Filter List</b></legend>
					<div>
						<div>
							<b>Term Keyword:</b> 
							<input type="text" autocomplete="off" name="searchtermkeyword" id="searchtermkeyword" size="25" value="<?php echo ($formSubmit == 'Search Terms'?$_POST['searchtermkeyword']:''); ?>" />
						</div>
						<div style="margin-top:8px;">
							<b>Definition Keyword:</b> 
							<input type="text" autocomplete="off" name="searchdefkeyword" id="searchdefkeyword" size="25" value="<?php echo ($formSubmit == 'Search Terms'?$_POST['searchdefkeyword']:''); ?>" />
						</div>
						<div style="margin-top:8px;">
							<b>Language:</b><br />
							<select name="searchlanguage" id="searchlanguage" style="margin-top:2px;" onchange="">
								<option value="">Select Language</option>
								<option value="">----------------</option>
								<?php 
								$langArr = $glosManager->getLanguageArr();
								foreach($langArr as $k => $v){
									if($language){
										echo '<option value="'.$k.'" '.($k==$language?'SELECTED':'').'>'.$k.'</option>';
									}
									else{
										echo '<option value="'.$k.'" '.($k==$defaultLang?'SELECTED':'').'>'.$k.'</option>';
									}
								}
								?>
							</select>
						</div>
						<div style="margin-top:8px;">
							<b>Taxonomic Group:</b><br />
							<select name="searchtaxa" id="searchtaxa" style="margin-top:2px;width:150px;" onchange="">
								<option value="">Select Group</option>
								<option value="">----------------</option>
								<?php 
								$taxaArr = $glosManager->getTaxaGroupArr();
								foreach($taxaArr as $k => $v){
									if($tId){
										echo '<option value="'.$k.'" '.($k==$tId?'SELECTED':'').'>'.$v.'</option>';
									}
									else{
										echo '<option value="'.$k.'">'.$v.'</option>';
									}
								}
								?>
							</select>
						</div>
						<div style="padding-top:8px;float:right;">
							<button name="formsubmit" type="submit" value="Search Terms">Filter List</button>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		<div id="termlistdiv" style="min-height:200px;">
			<?php
			if($symbUid){
				?>
				<div style="float:right;margin:10px;">
					<a href="#" onclick="toggle('newtermdiv');">
						<img src="../images/add.png" alt="Create New Term" />
					</a>
				</div>
				<div id="newtermdiv" style="display:none;margin-bottom:10px;">
					<form name="termeditform" action="termdetails.php" method="post" onsubmit="return verifyNewTermForm(this.form);">
						<fieldset>
							<legend><b>Add New Term</b></legend>
							<div style="clear:both;padding-top:4px;float:left;">
								<div style="float:left;">
									<b>Term: </b>
								</div>
								<div style="float:left;margin-left:10px;">
									<input type="text" name="term" id="term" maxlength="45" style="width:200px;" value="" onchange="" title="" />
								</div>
							</div>
							<div style="clear:both;padding-top:4px;float:left;">
								<div style="float:left;">
									<b>Definition: </b>
								</div>
								<div style="float:left;margin-left:10px;">
									<textarea name="definition" id="definition" rows="10" style="width:380px;height:70px;resize:vertical;" ></textarea>
								</div>
							</div>
							<div style="clear:both;padding-top:4px;float:left;">
								<div style="float:left;">
									<b>Language: </b>
								</div>
								<div style="float:left;margin-left:10px;">
									<input type="text" name="language" id="language" maxlength="45" style="width:200px;" value="" onchange="" title="" />
								</div>
							</div>
							<div style="clear:both;margin-top:12px;float:left;">
								Please enter the taxonomic group, higher than the rank of family, to which this term applies:
							</div>
							<div style="clear:both;padding-top:4px;float:left;">
								<div style="float:left;">
									<b>Taxonomic Group: </b>
								</div>
								<div style="float:left;margin-left:10px;">
									<input type="text" name="taxagroup" id="taxagroup" maxlength="45" style="width:250px;" value="" onchange="" title="" />
									<input name="tid" id="tid" type="hidden" value="" />
								</div>
							</div>
							<div style="clear:both;padding-top:8px;float:right;">
								<button name="formsubmit" type="submit" value="Create Term">Create Term</button>
							</div>
						</fieldset>
					</form>
				</div>
				<?php
			}
			if($termList){
				echo '<div style="font-weight:bold;font-size:120%;">Terms</div>';
				echo '<div><ul>';
				foreach($termList as $termId => $terArr){
					echo '<li>';
					echo '<a href="#" onclick="openTermPopup('.$termId.'); return false;"><b>'.$terArr["term"].'</b></a>';
					echo '</li>';
				}
				echo '</ul></div>';
			}
			elseif($formSubmit == 'Search Terms'){
				echo '<div style="margin-top:10px;"><div style="font-weight:bold;font-size:120%;">There are no terms matching your criteria.</div></div>';
			}
			else{
				echo '<div style="margin-top:10px;"><div style="font-weight:bold;font-size:120%;">There are currently no terms in the database.</div></div>';
			}
			?>
		</div>
	</div>
	<?php
	include($serverRoot."/footer.php");
	?>
</body>
</html>