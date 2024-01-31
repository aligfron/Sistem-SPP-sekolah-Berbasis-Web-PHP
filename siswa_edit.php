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
		$idprodi = $_REQUEST['idprodi'];
		$alm = $_REQUEST['alm'];
		$ang = $_REQUEST['ang'];
		$username = $_REQUEST['username'];
		
		$sql = mysqli_query($koneksi,"UPDATE siswa SET nama_siswa='$nama', idkelas='$idprodi', alamat='$alm', idangkatan='$ang', username='$username' WHERE nis='$nis'");
		
		if($sql > 0){
			header('Location: ./admin.php?hlm=master&sub=siswa');
			die();
		} else {
			echo 'ERROR! Periksa penulisan querynya.';
		}
	} else {
		$nis = $_REQUEST['nis'];
		$sql = mysqli_query($koneksi,"SELECT * FROM siswa WHERE nis='$nis'");
		list($nis,$nama,$a,$idprodi,$idang,$username) = mysqli_fetch_array($sql);
?>
<h2>Edit Data Siswa</h2>
<hr>
<form method="post" action="admin.php?hlm=master&sub=siswa&aksi=edit" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="nis" class="col-sm-2 control-label">NIS</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="nis" name="nis" value="<?php echo $nis; ?>" readonly>
		</div>
	</div>
	<div class="form-group">
		<label for="nama" class="col-sm-2 control-label">Nama siswa</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="alm" class="col-sm-2 control-label">Alamat</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="alm" name="alm" value="<?php echo $a; ?>">
		</div>
	</div>
	<div class="form-group">
		<label for="prodi" class="col-sm-2 control-label">Kelas</label>
		<div class="col-sm-4">
			<select name="idprodi" class="form-control">
			<?php
			$qprodi = mysql_query("SELECT * FROM kelas ORDER BY idkelas");
			while(list($id,$prodi)=mysql_fetch_array($qprodi)){
				echo '<option value="'.$id.'"';
				echo ($id==$idprodi) ? 'selected' : '';
				echo '>'.$prodi.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="ang" class="col-sm-2 control-label">Kelas</label>
		<div class="col-sm-4">
			<select name="ang" class="form-control">
			<?php
			$qprodi = mysqli_query($koneksi,"SELECT * FROM angkatan ORDER BY idangkatan");
			while(list($id,$ang)=mysqli_fetch_array($qprodi)){
				echo '<option value="'.$id.'"';
				echo ($id==$idang) ? 'selected' : '';
				echo '>'.$ang.'</option>';
			}
			?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="username" class="col-sm-2 control-label">Username</label>
		<div class="col-sm-4">
			<input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
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