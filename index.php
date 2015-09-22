<?php
//error_reporting(E_ALL);
include_once("config/symbini.php");
header("Content-Type: text/html; charset=".$charset);
?>
<html>
<head>
	<meta http-equiv="X-Frame-Options" content="deny">
	<title><?php echo $defaultTitle?> Home</title>
	<link href="css/base.css?<?php echo $CSS_VERSION; ?>" type="text/css" rel="stylesheet" />
	<link href="css/main.css?<?php echo $CSS_VERSION; ?>" type="text/css" rel="stylesheet" />
	<script type="text/javascript">
		<?php include_once('config/googleanalytics.php'); ?>
	</script>
</head>
<body>
	<?php
	include($serverRoot."/header.php");
	?> 
        <!-- This is inner text! -->
        <div  id="innertext">
            <h2>Welcome to</h2>
            <h1>The Lundell Plant Diversity Portal</h1>

            <h3>
                Plant specimen data and images from Texas, Mexico, Mesoamerica, and beyond
            </h3>
            <div>
                The Plant Resources Center of the University of Texas at Austin houses the University of Texas (TEX) and Lundell (LL) herbaria, which combined comprise over a million specimens.  The database presented here includes over 210,000 Texas specimens from both herbaria, presented using the Symbiota interface and software.  Future development will soon expand the on-line data to the Mexican and type specimens in TEX-LL, and interfaces will then be provided to allow other herbaria in Texas and Mexico (and elsewhere) to database their collections and serve their specimen data and images through the Lundell Portal.
            </div>
            <br/>
            <br/>
            <div>
                <a class="link-button" href="/collections/index.php">Search the collection...</a>
            </div>
        </div>

	<?php
	include($serverRoot."/footer.php");
	?> 

</body>
</html>
