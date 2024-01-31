<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['submit'] )){
		//variabel session ditransfer ke variabel lokal yg lebih mudah diingat penamaannya
		$submit = $_REQUEST['submit'];
		$kelas = $_REQUEST['kelas'];
		$tapel = $_REQUEST['tapel'];

			
		//tabel daftar siswa
		echo '<div class="row">';
		echo '<div class="col-md-9">';
		echo '<table class="table table-bordered">';
		echo '<tr><td colspan="2">Kelas</td><td colspan="2">'.$kelas.'</td></tr>';
		echo '<tr class="info"><th width="20">No.</th><th width="150">NIS</th><th>Nama Siswa</th></tr>';
		
		$qkelas = mysqli_query($koneksi,"SELECT idkelas FROM siswa WHERE idkelas='$tapel'");
		if(mysqli_num_rows($qkelas) > 0){
			$no=1;
			while(list($idkelas)=mysqli_fetch_array($qkelas)){
				$qsiswa = mysqli_query($koneksi,"SELECT nis,nama_siswa FROM siswa WHERE idkelas='$idkelas'");
				}
				while(list($nis,$siswa) = mysqli_fetch_array($qsiswa)){
				echo '<tr><td>'.$no++.'</td>';
				echo '<td>'.$nis.'</td>';
				echo '<td>'.$siswa.'</td></tr>';
					
			}
		} else {
			echo '<tr><td colspan="4"><em>Belum ada data.</em></td></tr>';
		}
		echo '</table></div></div>';
	} else {
?>
<!--
form pertama untuk tahapan menambahkan kelas baru, yaitu:
1. ketikkan nama kelas
2. ketikkan tahun pelajaran, misalnya: 2014/2015 atau 2014-2015
3. pilih prodi yg bersangkutan dg kelas
4. klik tombol [LANJUT]
//-->
<h2>Tambah Kelas</h2><hr>
<form method="post" action="admin.php?hlm=master&sub=kelas&aksi=view" class="form-horizontal" role="form">
	<div class="form-group">
		<label for="kelas" class="col-sm-2 control-label">Kelas</label>
		<div class="col-sm-2">
			<input type="text" class="form-control" id="kelas" name="kelas" placeholder="Kelas" required autofocus>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="submit" value="baru" class="btn btn-default">Lanjut</button>
			<a href="./admin.php?hlm=master&sub=kelas" class="btn btn-link">Batal</a>
		</div>
	</div>
</form>
<?php
	}
}
?>