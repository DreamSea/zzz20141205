
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <!--link href="../css/starter-template.css" rel="stylesheet"-->

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container">
	<br><br>
	<?php
		session_start();
		
		include '../notGithub/mysql_config.php';
		$dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
		
		if (isset($_POST['bindTo']))
		{
			$_SESSION['device'] = $_POST['bindTo'];
		}
		
		if (isset($_SESSION['device']))
		{
			if (isset($_POST['optionsRadios']))
			{
				$sql = "UPDATE devices SET location=\"".$_POST['optionsRadios']."\" WHERE name=\"".$_SESSION['device']."\"";
				$stmt = $dbh->prepare($sql);
				$stmt->execute();
			}
		
			echo("Bound: ".$_SESSION['device']);
			$stmt = $dbh->prepare("SELECT location FROM devices WHERE name=\"".$_SESSION['device']."\"");
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			$location = $result['location'];
			echo("<br>Location: ".$location);
		}
		else
		{
			echo ("unbound");
		}
	?>
	<br><br>
	
	<form role="form" method="POST" action="secret.php" class="form-horizontal">
	<div class="form-group">
	<div class="col-md-4">
      <?php
		$stmt = $dbh->prepare("SELECT DISTINCT location FROM devices ORDER BY location");
		$stmt->execute();
		$numRows = $stmt->rowCount();
		
		for ($i = 0; $i < $numRows; $i++)
		{
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			echo ("<div class=\"radio\"><label><input type=\"radio\" name=\"optionsRadios\" value=\"".$result['location']."\"");
			
			if (isset($_SESSION['device']))
			{
				if ($result['location'] == $location)
				{
					echo(" checked");
				}
			}
			echo(">");
			echo($result['location']);
			//echo(">".$result['name']."</option>");
			echo("</label></div>");
		}
	  ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-4">
			<button type="submit" class="btn btn-default" name="bind">Set Location</button>
		</div>
		</div>
	</form>
	<br><br>
	<form role="form" method="POST" action="secret.php" class="form-horizontal">
	<div class="form-group">
	<div class="col-md-4">
		<select class="form-control" name="bindTo">
      <?php
		$stmt = $dbh->prepare("SELECT * FROM devices");
		$stmt->execute();
		$numRows = $stmt->rowCount();
		
		for ($i = 0; $i < $numRows; $i++)
		{
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			echo ("<option");	
			if (isset($_SESSION['device']))
			{
				if ($result['name'] == $_SESSION['device'])
				{
					echo(" selected=\"selected\"");
				}
			}
			echo(">".$result['name']."</option>");
		}
	  ?>
		</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-4">
			<button type="submit" class="btn btn-default" name="bind">Bind Device</button>
		</div>
		</div>
	</form>
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
