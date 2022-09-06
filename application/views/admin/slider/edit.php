<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>KingsPizza Kiosk</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/plugins/fontawesome-free/css/all.min.css">
	<!-- IonIcons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?=base_url()?>assets/admin/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">


	<!-- Main Sidebar Container -->

	<?php $this->load->view('admin/leftMenu'); ?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">

		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<!-- /.row -->
				<div class="row">
					<!-- left column -->
					<div class="col-md-6 mt-2">
						<!-- general form elements -->
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title"><?= "Slider məlumatı dəyiş" ?> </h3>
							</div>
							<!-- /.card-header -->
							<!-- form start -->
							<form method="post" action="<?=base_url()?>admin/slider/update/<?=$sliderData[0]['id']?>" enctype="multipart/form-data">
								<div class="card-body">
									<div class="form-group">
										<label for="title">Başlıq </label>
										<input value="<?=$sliderData[0]['title']?>" type="text" name="title" class="form-control" id="title" required placeholder="Başlıq">
									</div>
									<div class="form-group">
										<label for="summary">Məlumat </label>
										<input value="<?=$sliderData[0]['summary']?>" type="text" name="summary" class="form-control" id="summary" placeholder="Məlumat">
									</div>
									<div class="form-group">
										<label for="exampleInputFile">Slider şəkli </label>
										<div class="input-group">
											<div class="custom-file">
												<input type="file" name="slider_image" class="custom-file-input" id="slider_image">
												<label class="custom-file-label" for="exampleInputFile">Fayl seçin</label>
											</div>
											<div class="input-group-append">
												<span class="input-group-text">Upload</span>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label for="current_image">Mövcud şəkil : </label for="current_image">
										<div>

											<img width="100" id="current_image" src="<?=base_url().$sliderData[0]['icon']?>">
										</div>
									</div>
								</div>
								<!-- /.card-body -->

								<div class="card-footer">
									<button type="submit" class="btn btn-primary">Təsdiqlə</button>
								</div>
							</form>
						</div>

					</div>
					<!--/.col (left) -->
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="<?=base_url()?>assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="<?=base_url()?>assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="<?=base_url()?>assets/admin/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="<?=base_url()?>assets/admin/plugins/chart.js/Chart.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="--><?//=base_url()?><!--assets/admin/dist/js/demo.js"></script>-->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?=base_url()?>assets/admin/dist/js/pages/dashboard3.js"></script>
</body>
</html>
