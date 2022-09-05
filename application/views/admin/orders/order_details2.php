<?php
//var_dump( $order_products );
//die()
?>
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

	<script src="<?=base_url()?>assets/admin/dist/js/sweetalert.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">


	<!-- Main Sidebar Container -->

	<?php $this->load->view('admin/leftMenu'); ?>
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-sm-6">
						<h1><?= $order_details['orderNumber'] ?></h1>
					</div>
					<div class="col-sm-6">
						<ol class="breadcrumb float-sm-right">
							<li class="breadcrumb-item"><a href="<?=base_url()?>admin/order">Sifarişlər</a></li>
							<li class="breadcrumb-item active">Sifarişin detalları</li>
						</ol>
					</div>
				</div>
			</div><!-- /.container-fluid -->
		</section>

		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">


						<!-- Main content -->
						<div class="invoice p-3 mb-3">
							<!-- title row -->
							<div class="row">
								<div class="col-12">
									<h4> Kingsmart
										<small class="float-right">Tarix: <?= $order_details['createdAt'] ?></small>
									</h4>
								</div>
								<!-- /.col -->
							</div>
							<!-- info row -->
							<div class="row invoice-info">
								<!-- /.col -->
								<div class="col-sm-8 invoice-col">
									Müştəri məlumatları
									<address>
										<strong><?= $order_details['user_fullName'] ?></strong><br>
										<?= $order_details['city'] ?><br>
										<?= $order_details['address'] ?><br>
										<?= $order_details['postal'] ?><br>
										Phone: <?= $order_details['user_phone'] ?><br>
									</address>
								</div>
								<!-- /.col -->
								<div class="col-sm-4 invoice-col">
									<b>Sifariş nömrəsi:</b> <?= $order_details['orderNumber'] ?> <br>
								</div>
								<!-- /.col -->
							</div>
							<!-- /.row -->

							<!-- Table row -->
							<div class="row">
								<div class="col-12 table-responsive">
									<table class="table table-striped">
										<thead>
										<tr>
											<th>Mehsul adi</th>
											<th>Qiymet</th>
											<th>Say</th>
											<th>Qiymet x Say</th>
										</tr>
										</thead>
										<tbody>
										<?php foreach ( $order_items as $item ): ?>
											<tr>
												<td><?= $item['productName'] ?>
												<td><?= $item['price'] ?>
												<td><?= $item['quantity'] ?></td>
												<td><?= (float)$item['price'] * (float)$item['quantity'] ?></td>
											</tr>
										<?php endforeach; ?>

										</tbody>
									</table>
								</div>
								<!-- /.col -->
							</div>
							<!-- /.row -->

							<div class="row">
								<!-- /.col -->
								<div class="col-8">
									<p class="lead"></p>

									<div class="table-responsive">
										<table class="table">
											<tr>
												<th>Çatdırılma :</th>
												<td> 0 AZN </td>
											</tr>
											<tr>
												<th> Cəmi </th>
												<td><?= $order_details['total'] ?> AZN <br></td>
											</tr>
										</table>
									</div>
								</div>
								<!-- /.col -->
							</div>
							<!-- /.row -->

							<!-- this row will not appear when printing -->

						</div>
						<!-- /.invoice -->
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->


	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
		<!-- Control sidebar content goes here -->
	</aside>
	<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

</body>
</html>
