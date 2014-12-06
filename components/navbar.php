<?php 
	session_start();
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">GATO</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <!--li class="active"><a href="#">by Device</a></li-->
            <li><a href="byDevice.php">Status by Device</a></li>
            <li><a href="thermostat.php">Thermostat</a></li>
            <li><a href="#">Map</a></li>
          </ul>
		  <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Register Device</a></li>
			<li><a href="dlDemo/index.html">Login</a></li>
			
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>