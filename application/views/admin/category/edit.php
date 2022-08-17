<?php if( empty( $categoryData ) ) die('Kateqoriya tapilmadi'); ?>
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

	<link rel="stylesheet" href="<?=base_url()?>assets/admin/plugins/summernote/summernote-bs4.min.css">
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
								<h3 class="card-title"><?= $categoryData['title'] ?></h3>
							</div>
							<!-- /.card-header -->
							<!-- form start -->
							<form method="post" action="<?=base_url()?>admin/category/update/<?= $categoryData['id'] ?>" enctype="multipart/form-data">
								<div class="card-body">
									<div class="form-group">
										<label for="title"> Kateqoriya adı </label>
										<input value="<?= $categoryData['title'] ?>" type="text" name="title" class="form-control" id="title" required placeholder="Kateqoriya adı">
									</div>
									<div class="form-group">
										<label for="exampleInputFile">Kateqoriya şəkli </label>
										<div class="input-group">
											<div class="custom-file">
												<input type="file" name="category_image" class="custom-file-input" id="category_image">
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

											<img width="100" id="current_image" src="<?=base_url().$categoryData['image']?>">
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
					<div class="col-md-6 pt-2">
						<!-- /.card -->

						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Məhsullar</h3>
							</div>

							<!-- /.card-header -->
							<div class="card-body p-0">
								<div class="row pl-2">
									<div class="col-md-12">
										<div id="example1_filter" class="dataTables_filter">
											<label>Search:<input id="search" type="search" class="form-control form-control-sm" placeholder="" aria-controls="example1"></label>
										</div>
									</div>
								</div>
								<table class="table table-striped">
									<thead>
									<tr>
										<th style="width: 10px">#</th>
										<th>Məhsul adı</th>
										<th>Qiymət</th>
										<th style="width: 40px">Seç</th>
									</tr>
									</thead>
									<tbody id="searchResult">
									<?php foreach ( $products as $product) : ?>
										<tr>
											<td><?= $product['id']?></td>
											<td><?= $product['title'] ?></td>
											<td>
												<?= $product['price'] ?>
											</td>
											<td>
												<input type="checkbox" <?= $product['inCategory'] > 0 ? 'checked' : '' ?> productId="<?= $product['id'] ?>"  id="customCheckbox<?= $product['price'] ?>" value="option<?= $product['price'] ?>" onchange="updateSection(event)">
											</td>
										</tr>
									<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.card-body -->
						</div>
						<!-- /.card -->
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

<script>
	$("#search").keyup(function(event) {
		var regex = new RegExp("^[a-zA-Z0-9\b]+$");
		var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
		if (!regex.test(key)) {
			event.preventDefault();
			return false;
		}
			if( $(this).val().length > 2 || $(this).val().length == 0) {
			var keyword = $(this).val();
			$.ajax({
				method: "POST",
				url: "<?=base_url()?>admin/category/search/<?= $categoryData['id'] ?>",
				data: { keyword: keyword}
			})
				.done(function( html ) {
					$("#searchResult").html( html );
				});
		}

	});
</script>

<script>
	function updateSection(event){
		var selectElement = event.target;

		var productId = selectElement.getAttribute('productId');
		if( event.target.checked ) {
			$.ajax({
				method: "POST",
				url: "<?=base_url()?>admin/category/addProductToCategory/<?= $categoryData['id'] ?>",
				data: { productId: productId}
			})
		}
		else {
			$.ajax({
				method: "POST",
				url: "<?=base_url()?>admin/category/deleteProductFromCategory/<?= $categoryData['id'] ?>",
				data: { productId: productId}
			})
		}
	}
</script>

</body>
</html>
