<!DOCTYPE html>
<!---->
<?php
///CONFIGURATION FILE///
require __DIR__ . '/config.php';
///PAGE VARIABLES///START
$PAGE_HEADER = 'Tacacs Devices';
$PAGE_SUBHEADER = 'Here you can add some devices that will be use tacacs';
$PAGE_TITLE = 'TacacsGUI';
$PAGE_SUBTITLE = 'Tacacs Devices';
$BREADCRUMB = array(
	'Tacacs' => [
		'name' => 'Tacacs', 
		'href' => '', 
		'icon' => 'fa fa-cogs', 
		'class' => ''  //last item should have active class!!
	], 
	'Devices' => [
		'name' => 'Devices', 
		'href' => '', 
		'icon' => 'fa fa-server', //leave empty if you won't put icon
		'class' => 'active' //last item should have active class!!
	]
);
///!!!!!////
$ACTIVE_MENU_ID=20;
$ACTIVE_SUBMENU_ID=210;
///!!!!!////
///PAGE VARIABLES///END
?>
<html>

<?php
require __DIR__ . '/templates/header.php';
?>
<!--ADDITIONAL CSS FILES START-->
	<!-- DataTables -->
	<link rel="stylesheet" href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	<!-- bootstrap slider -->
	<link rel="stylesheet" href="/plugins/bootstrap-slider/slider.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="/plugins/iCheck/square/blue.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
	<style>
		#prefix_slider .slider-selection{
			background:#444;
		}
	</style>
<!--ADDITIONAL CSS FILES END-->

<?php 

require __DIR__ . '/templates/body_start.php'; 

?>
<!--MAIN CONTENT START-->

<div class="row"> 
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">Tacacs Devices</h3>
				<div class="dropdown pull-right">
					<a class="btn btn-flat btn-success" id="addDeviceBtn" data-toggle="modal" data-target="#addDevice">+ Add Device</a>
					<a class="btn btn-flat btn-info" id="filterButton">Filter</a>
				</div>
			</div><!-- /.box-header -->
			<div class="box-body">
				<div class="table-responsive">
					<table id="devicesDataTable" class="table-striped display table table-bordered" style="overflow: auto;">
	
					</table>	
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box --> 
	</div><!-- /.col -->
</div><!-- /.row -->

<!--MAIN CONTENT END-->

<?php 

require __DIR__ . '/templates/pages/tac_devices/modalAddDevice.php'; 

?>

<?php 

require __DIR__ . '/templates/pages/tac_devices/modalEditDevice.php'; 

?>

<?php 

require __DIR__ . '/templates/body_end.php'; 

?>


<?php

require __DIR__ . '/templates/footer_end.php';

?>
<!-- ADDITIONAL JS FILES START-->
	<!-- DataTables -->
	<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<!-- Bootstrap slider -->
	<script src="/plugins/bootstrap-slider/bootstrap-slider.js"></script>
	<!-- iCheck -->
	<script src="/plugins/iCheck/icheck.min.js"></script>	
	<!-- Select2 -->
	<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
	
	<!-- DATATABLES MAIN -->
    <script src="dist/js/pages/tac_devices/datatables.js"></script>
	<!-- main js Device MAIN Functions -->
    <script src="dist/js/pages/tac_devices/main.js"></script>
<!-- ADDITIONAL JS FILES END-->
</body>

</html>