
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>GatoBot</title>

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
	
     <div class="container starter-template">
      <!-- Example row of columns -->
      <div class="row">
		<div class="col-md-12">
			<h2>Your Temperature</h2>
		</div>
        <div class="col-md-offset-4 col-md-2">
				<p>Current: <h1>71</h1></p>
		</div>
			<div class="col-md-2">
				<p>Past Average: <h1>73</h1></p>
			</div>

		<div class="col-md-offset-4 col-md-4">
			<p>Rating:</p>
			<div class="alert alert-success" role="alert"><b>Good:</b> Better than average</div>
		</div>			
		</div>
		<br>
		<hr>
		<br>
		<div class="row">
			<div class="col-md-12">
				<h2>Group</h2>
			</div>
			<div class="col-md-offset-5 col-md-2">
				<p>Rank: <h1>2/5</h1></p>
			</div>
			<div class="col-md-offset-2 col-md-8 no-align">
			<table class="table">
			  <thead>
				<tr>
				  <th>Rank</th>
				  <th>Household</th>
				  <th>Temperature</th>
				</tr>
			  </thead>
			  <tbody>
				<tr>
				  <td>1</td>
				  <td>Smiths</td>
				  <td>70</td>
				</tr>
				<tr class="success">
				  <td>2</td>
				  <td>Team Gato</td>
				  <td>71</td>
				</tr>
				<tr>
				  <td>3</td>
				  <td>Johnsons</td>
				  <td>73</td>
				</tr>
				<tr>
				  <td>4</td>
				  <td>Williams</td>
				  <td>77</td>
				</tr>
				<tr>
				  <td>5</td>
				  <td>Millers</td>
				  <td>80</td>
				</tr>
			  </tbody>
			</table>
		</div>
		</div>
		<br>
		<hr>
		<br>
		
		<div class="row">
			<div class="col-md-12">
				<h2>Community</h2>
			</div>
			<div class="col-md-offset-5 col-md-2">
				<p>Current: <h1>71</h1></p>
			</div>
			<div class="col-md-offset-4 col-md-4">
				<p>Rating:</p>
				<div class="alert alert-warning" role="alert"><b>Okay:</b> Average</div>
			</div>
		</div>
		
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
