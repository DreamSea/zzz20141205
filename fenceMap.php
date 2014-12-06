
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Map</title>

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
	
	<link rel="stylesheet" href="notGithub/default.css">

  <!-- css: dependencies -->
  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.1/leaflet.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.2.3/leaflet.draw.css">
  <link rel="stylesheet" href="../../vendor/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css">

  <!-- css: main -->
  <link rel="stylesheet" href="notGithub/dist/css/geotrigger-editor.css">
  </head>

  <body>
  <?php include 'components/navbar.php' ?>
	
    <div class="container">

		<div id="gt-editor">
    <form id="example">
      <h1>Geotrigger Editor</h1>
      <input id="client-id" type="text" placeholder="Client ID">
      <input id="client-secret" type="text" placeholder="Client Secret">
      <button class="engage">Engage</button>
    </form>
  </div>

  <!-- js: cdn dependencies -->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js" ></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.0/backbone-min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.marionette/1.4.1-bundled/backbone.marionette.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.3.0/handlebars.runtime.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.1/leaflet.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/leaflet.draw/0.2.3/leaflet.draw.js"></script>

  <!-- js: local dependencies -->
  <script src="notGithub/geotrigger-js/geotrigger.js"></script>
  <script src="notGithub/esri-leaflet/dist/esri-leaflet.js"></script>
  <script src="notGithub/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.js"></script>

  <!-- js: main -->
  <script src="notGithub/dist/js/geotrigger-editor.js"></script>

  <!-- js: initializer -->
  <script>
    $(function(){
      $('#example').submit(function(e){
        e.preventDefault();
        var config = {
          session: {
            clientId: $('#client-id').val(),
            clientSecret: $('#client-secret').val()
          }
        };
        $(this).remove();
        Geotrigger.Editor.start(config);
      });
    });
  </script>
  
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
