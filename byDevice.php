
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Device Location</title>

    <!-- Bootstrap core CSS -->
    <link href="../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/starter-template.css" rel="stylesheet">

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
  <?php include 'components/navbar.php' ?>
	
    <div class="container">
	<?php
		include 'notGithub/mysql_config.php';
		$dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	?>
	<br><br>
	<table class="table table-striped">
	
      <thead>
        <tr>
          <th>#</th>
          <th>Device Name</th>
          <th>Location</th>
        </tr>
      </thead>
      <tbody>
		<?php
			$stmt = $dbh->prepare("SELECT * FROM devices");
			$stmt->execute();
			$numRows = $stmt->rowCount();
			
			for ($i = 0; $i < $numRows; $i++)
			{
				$result = $stmt->fetch(PDO::FETCH_ASSOC);
				include 'components/tableRow.php';
			}
		?>
      </tbody>
    </table>

    </div><!-- /.container -->
  <?php include 'components/footer.php' ?>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
