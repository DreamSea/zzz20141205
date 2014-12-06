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
          <a class="navbar-brand" href="#">GATO</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <!--li class="active"><a href="#">by Device</a></li-->
            <li><a href="index.php">Status by Device</a></li>
            <li><a href="index.php">Status by Location</a></li>
            <li><a href="switch.php">Switch</a></li>
          </ul>
		  <ul class="nav navbar-nav navbar-right">
            <li><a href="#">Register Device</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>