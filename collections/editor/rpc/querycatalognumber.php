<?php
	include_once('../../../config/dbconnection.php');
	$retArr = Array();
	$con = MySQLiConnectionFactory::getCon("readonly");
	$catNum = $con->real_escape_string($_REQUEST['cn']);
	$collId = $con->real_escape_string($_REQUEST['collid']);
	$occid = $con->real_escape_string(isset($_REQUEST['occid'])?$_REQUEST['occid']:0);

	if($catNum && $collId){
		$sql = 'SELECT occid FROM omoccurrences '.
			'WHERE catalognumber = "'.$catNum.'" AND collid = '.$collId.' AND occid <> '.$occid;
		//echo $sql;
		$result = $con->query($sql);
		while ($row = $result->fetch_object()) {
			$retArr[] = $row->occid;
		}
		$result->free();
	}
	$con->close();
	echo implode(',',$retArr);
?>