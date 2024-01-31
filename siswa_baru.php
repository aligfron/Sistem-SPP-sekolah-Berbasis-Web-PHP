<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['submit'] )){
		$nis = $_REQUEST['nis'];
		$nama = $_REQUEST['nama'];
		$alm = $_REQUEST['alamat'];
		$kls = $_REQUEST['kls'];
		$angkatan = $_REQUEST['angkatan'];
		$username = $_REQUEST['username'];
		$password = $_REQUEST['password'];
	
		$sql = mysqli_query($koneksi,"INSERT INTO siswa VALUES('$nis','$nama','$alm','$kls','$angkatan','$username','$password')");
		
		if($sql > 0){
			header('Location: ./admin.php?hlm=master&sub=siswa');
			die();
		} else {
			echo 'ERROR! Periksa penulisan querynya.';
		}
	} else {
?>
<h2>Tambah Siswa</h2>
<hr>
<form method="post" action="admin.php?hlm=master&sub=siswa&aksi=baru" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="nis" class="col-sm-2 control-label">NIS</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="nis" name="nis" placeholder="Nomor Induk Siswa" required autofocus>
		</div>
	</div>
	<div class="form-group">
		<label for="nama" class="col-sm-2 control-label">Nama siswa</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
		</div>
	</div>
	<div class="form-group">
		<label for="alamat" class="col-sm-2 control-label">Alamat</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat" required>
		</div>
	</div>
	<div class="form-group">
		<label for="kls" class="col-sm-2 control-label">Kelas</label>
		<div class="col-sm-4">
			<select name="kls" class="form-control">
			<?php
			$qprodi = mysqli_query($koneksi,"SELECT * FROM kelas ORDER BY idkelas");
			while(list($idprodi,$prodi)=mysqli_fetch_array($qprodi)){
				echo '<option value="'.$idprodi.'">'.$prodi.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="angkatan" class="col-sm-2 control-label">Angkatan</label>
		<div class="col-sm-4">
			<select name="angkatan" class="form-control">
			<?php
			$qangkatan = mysqli_query($koneksi,"SELECT * FROM angkatan ORDER BY idangkatan");
			while(list($idangkatan,$angkatan)=mysqli_fetch_array($qangkatan)){
				echo '<option value="'.$idangkatan.'">'.$angkatan.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
		</div>
	</div>
	<div class="form-group">
		<label for="password" class="col-sm-2 control-label">Password</label>
		<div class="col-sm-4">
			<input type="password" class="form-control" id="password" name="password" placeholder="Password" >
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="submit" class="btn btn-default">Simpan</button>
			<a href="./admin.php?hlm=master&sub=siswa" class="btn btn-link">Batal</a>
		</div>
	</div>
</form>
<?php
	}
}
?>