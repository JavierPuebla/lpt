<!DOCTYPE html>
<html lang="es">
  <head>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale = 1">
     <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <!--   <meta name="description" content="Lotes Para Todos - App V 1.1">
    <meta name="author" content="BigBot.io">
    <meta property="og:title" content="Lotes Para Todos - App V 1.1" />
  <meta property="og:type" content="web application" />
  <meta property="og:url" content="https://www.bigbot.io/lpt/" />
   --><!-- <meta property="og:image" content="https://www.bigbot.io/lpt/logo_lpt.png" /> -->

  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">

  <!-- <link rel="manifest" href="manifest.json" /> -->
  <!-- <script async  src="https://cdn.rawgit.com/GoogleChrome/pwacompat/v2.0.1/pwacompat.min.js"></script> -->
    <!-- <script async  src="<?php  //base_url() ?>dependencies/local_cdn/pwacompat/v2.0.1/pwacompat.min.js"></script> -->
    <link rel="icon" href="favicon.png">


    <title>Lotes Para Todos - App V 1.2</title>
  <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="<?php echo base_url() ?>dependencies/local_cdn/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"> -->

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700,400italic" rel="stylesheet">

    <!-- yotheme CSS -->
    <link rel="stylesheet" type="text/css" href="<?php  echo base_url()?>css/yotheme_css/theme.9.css" />
    <link rel="stylesheet" type="text/css" href="<?php  echo base_url()?>css/yotheme_css/theme.update.css" />


    <!-- Custom core CSS -->
    <link rel="stylesheet" type="text/css" href="<?php  echo base_url()?>css/JP.css" />



<!-- JAVASCRIPTS -->
   <!--Load JQUERY from Google's network -->
  <script src="https://code.jquery.com/jquery-latest.min.js"></script>
  <!-- Load JQuery from local cdn -->
  <!-- <script src="<?php echo base_url() ?>dependencies/local_cdn/jquery/jquery-latest.min.js"></script> -->

  <!--Load Bootstrap JS -->
  <script  src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <!-- <script  src="<?php echo base_url() ?>dependencies/local_cdn/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->

<!--Load JQUERY Autocomplete  -->
  <script type="text/javascript" src="<?php echo base_url() ?>dependencies/jquery-ui.min.js"></script>
  <link href="<?php echo base_url() ?>dependencies/jquery-ui.min.css" rel="stylesheet">
  </head>
  <body>
<div class="bs-component">
    <div class="container">
      <?php $t = validation_errors();
        if ($t != ''){
          echo"<div class='alert alert-danger' role='alert'><strong>Warning!</strong>$t</div>";
        }
      ?>
      <div class="row d-flex justify-content-center mt-5">
        <div class="col-sm-10 col-md-6 col-lg-4 text-center">
           <img src="images/iso-lpt.png"/>
           <form class="jp-form-signin" method="post" accept-charset="utf-8" action="verifylogin">
        <div class="jp-form-signin-heading text-center">Acceso Administración</div>
        <div><label for="username" >Usuario</label>
        <input type="text" id="usr_usuario" name="usr_usuario" class="form-control" required="" autofocus=""></div>
        <div><label for="inputPassword" >Clave</label>
        <input type="password" id="clave_usuario" name="clave_usuario" class="form-control" required=""></div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
      </form>

        </div>
      </div>

    </div> <!-- /container -->
</div>



</body></html>
