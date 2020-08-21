<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale = 1">
    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
    <meta name="description" content="Lotes Para Todos - App V 1.1">
    <meta name="author" content="BigBot.io">
    <meta property="og:title" content="Lotes Para Todos - App V 1.1" />
    <meta property="og:type" content="web application" />
    <meta property="og:url" content="https://www.bigbot.io/lpt/" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
   <!-- <meta property="og:image" content="https://www.bigbot.io/lpt/logo_lpt.png" />-->
  
  <meta http-equiv="Last-Modified" content="0">
  <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  <meta http-equiv="Pragma" content="no-cache">

  <!-- <link rel="manifest" href="manifest.json" /> -->
  <!-- <script async  src="https://cdn.rawgit.com/GoogleChrome/pwacompat/v2.0.1/pwacompat.min.js"></script> -->
    <!-- <script async  src="<?php  //base_url() ?>dependencies/local_cdn/pwacompat/v2.0.1/pwacompat.min.js"></script> -->
    <!-- <link rel="icon" href="favicon.png"> -->
    <title>Lotes Para Todos - App V 1.1</title>

    <!-- ionicons  CSS (no funciona en forma local) -->
    <link href="https://unpkg.com/ionicons@4.5.5/dist/css/ionicons.min.css" rel="stylesheet">
    
    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- <link href="<?php echo base_url() ?>dependencies/local_cdn/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"> -->


    <!-- Bootswatch Flatly theme modificacdo es bootstrap.css y original es boostrap.min.css  -->
    <!-- <link href="<?php echo base_url() ?>dependencies/local_cdn/bootswatch/4.0.0/flatly/bootstrap.css" rel="stylesheet"> -->
     <!-- <link href="https://maxcdn.bootstrapcdn.com/bootswatch/4.0.0/flatly/bootstrap.min.css" rel="stylesheet"> -->
    
    <!-- Bootstrap datepicker -->
    <link href="<?php echo base_url() ?>dependencies/bootstrap-datepicker/datepicker.css" rel="stylesheet" media="all">

  
    <!-- Datatables CSS -->
    <!-- <link href="<?php echo base_url() ?>dependencies/local_cdn/DataTables/bootstrap4/css/dataTables.bootstrap4.min.css" rel="stylesheet"> -->

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,700,400italic" rel="stylesheet">

    

    <!-- yotheme CSS -->
    <link rel="stylesheet" type="text/css" href="<?php  echo base_url()?>css/yotheme_css/theme.9.css" />
    <link rel="stylesheet" type="text/css" href="<?php  echo base_url()?>css/yotheme_css/theme.update.css" />




    <!-- Custom core CSS -->
    <link rel="stylesheet" type="text/css" href="<?php  echo base_url()?>css/JP.css" />
  

    
    <link href="<?php echo base_url() ?>dependencies/datepicker-1.5.1-dist/css/bootstrap-datepicker.standalone.css" rel="stylesheet">
     <!-- Bootstrap slider css  -->
    <link href="<?php echo base_url() ?>dependencies/slider/css/slider.css" rel="stylesheet">
    

    <!-- donut chart style -->
    <!-- <link href="<?php // base_url() ?>css/donut3d.css" rel="stylesheet"> -->


<!-- JAVASCRIPTS -->
   <!--Load JQUERY from Google's network -->
  <script src="https://code.jquery.com/jquery-latest.min.js"></script>
  <!-- Load JQuery from local cdn -->
  <!-- <script src="<?php echo base_url() ?>dependencies/local_cdn/jquery/jquery-latest.min.js"></script> -->

  <!-- load Popper JS  -->
  <script src="https://unpkg.com/popper.js/dist/umd/popper.min.js"></script>
  <!-- <script src="<?php echo base_url() ?>dependencies/local_cdn/popper_js/popper.min.js"></script> -->
   

  <!--Load Bootstrap JS -->
  <script  src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <!-- <script  src="<?php echo base_url() ?>dependencies/local_cdn/bootstrap/4.0.0/js/bootstrap.min.js"></script> -->


  <!-- Data Tables JS  AND filter    -->
  <!-- <script  src="<?php echo base_url() ?>dependencies/local_cdn/DataTables/datatables.min.js"></script> -->
  
  <script  src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  <script  src="<?php echo base_url() ?>dependencies/local_cdn/DataTables/Filter_yadcf/jquery.dataTables.yadcf.js"></script>
  
  <!-- Select 2 plugin --> 
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>


  <!-- Data Tables date time sorting      -->
  <script  src="<?php echo base_url() ?>dependencies/local_cdn/moment.js-2.8.4/moment.js"></script>

  <script  src="<?php echo base_url() ?>dependencies/local_cdn/DataTables/Ultimate_DateTime_sorting-1.10.19/Ultimate_DateTime_sorting.js"></script>
  <script  src="<?php echo base_url() ?>dependencies/local_cdn/DataTables/Ultimate_DateTime_sorting-1.10.19/Ultimate_DateTime_sorting.js"></script>

  <script  src="<?php echo base_url() ?>dependencies/local_cdn/DataTables/Responsive-2.2.3/js/dataTables.responsive.min.js"></script>  
    <!-- Data Tables suma columnas   -->
    <script  src="<?php echo base_url() ?>dependencies/local_cdn/DataTables/sum.js"></script>  


  <!-- format de numeros  -->
  <script  src="<?php echo base_url() ?>dependencies/local_cdn/accounting/accounting.js"></script>  


   <!-- my functions -->
  
  <script src="<?php echo base_url() ?>js/fnks.js?vdt=<?php echo date('d/m/Y-H:i') ?>"></script>
  <script src="<?php echo base_url() ?>js/ob.js?vdt=<?php echo date('d/m/Y-H:i') ?>"></script>
  <script src="<?php echo base_url() ?>js/router.js?vdt=<?php echo date('d/m/Y-H:i') ?>"></script>
  <script src="<?php echo base_url() ?>js/validator.js?vdt=<?php echo date('d/m/Y-H:i') ?>"></script>

   <!-- Bootstrap datepicker js -->
   <!-- <script type="text/javascript"  src="<?php echo base_url() ?>dependencies/datepicker-1.5.1-dist/js/bootstrap-datepicker.min.js"></script> -->

  
  <script src="<?php echo base_url() ?>dependencies/bootstrap-datepicker/moment.js"></script>
  <script src="<?php echo base_url() ?>dependencies/bootstrap-datepicker/locale-es.js"></script>
  <script src="<?php echo base_url() ?>dependencies/bootstrap-datepicker/datepicker.js"></script>
  <script src="<?php echo base_url() ?>dependencies/bootstrap-confirmation.js"></script>

<!-- PrintThis js -->
  <script type="text/javascript"  src="<?php echo base_url() ?>dependencies/local_cdn/printThis/printThis.js"></script>


  <!-- Bootstrap slider js -->
  <script type="text/javascript"  src="<?php echo base_url() ?>dependencies/slider/js/bootstrap-slider.js"></script>

 <!-- <script src="https://d3js.org/d3.v4.min.js"></script> -->
<script type="text/javascript"  src="<?php echo base_url() ?>dependencies/local_cdn/d3/d3.v4.min.js"></script>

  <!-- Bootstrap d3 min -->
  <!-- <script type="text/javascript"  src="<?php  //base_url() ?>dependencies/d3/d3.min.js"></script> -->
  <!-- d3 charts  -->
  <!-- <script type="text/javascript"  src="<?php  //base_url() ?>dependencies/d3/charts/donut3D.js"></script> -->

  <!-- Add block ui loader and block window plugin -->
  <script type="text/javascript" src="<?php echo base_url() ?>dependencies/blockUI_plugin.js"></script>
  
  
<!--Load JQUERY Autocomplete  -->
  <script type="text/javascript" src="<?php echo base_url() ?>dependencies/jquery-ui.min.js"></script>
  <link href="<?php echo base_url() ?>dependencies/jquery-ui.min.css" rel="stylesheet">

<!-- load AutoNumeric -->
<!-- <script src="<?php //echo base_url() ?>dependencies/local_cdn/AutoNumeric/AutoNumeric.js" type="text/javascript"></script>
<script src="<?php //echo base_url() ?>dependencies/local_cdn/AutoNumeric/AutoNumericHelper.js" type="module"></script>
<script src="<?php //echo base_url() ?>dependencies/local_cdn/AutoNumeric/AutoNumericEnum.js" type="module"></script>
<script src="<?php //echo base_url() ?>dependencies/local_cdn/AutoNumeric/maths/Evaluator.js" type="module"></script>
<script src="<?php //echo base_url() ?>dependencies/local_cdn/AutoNumeric/maths/Parser.js" type="module"></script>
 -->
<script src="https://cdn.jsdelivr.net/npm/autonumeric@4.1.0"></script>

</head>
