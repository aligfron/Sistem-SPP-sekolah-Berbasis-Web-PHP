<?php
if( empty( $_SESSION['iduser'] ) ){
	//session_destroy();
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	if( isset( $_REQUEST['aksi'] )){
		$aksi = $_REQUEST['aksi'];
		
		if($aksi == 'view'){
			//menampilkan daftar siswa dalam kelas
			include 'kelas_baru.php';
		}
		if($aksi == 'hapus'){
			//menghapus kelas beserta siswa yg ada di dalamnya
			include 'kelas_hapus.php';
		}
		
	} else {
		//query untuk menampilkan daftar kelas sesuai dengan tahun pelajaran yg ditentukan, dalam hal ini 2014/2015.
		//tahun pelajaran mestinya bersifat dinamis, temukan cara agar th_pelajaran dapat ditentukan saat menampilkan kelas
		$sql = mysqli_query($koneksi,"CALL `prosedur_kelas`()");
		echo '<h2>Daftar Kelas</h2><hr>';
      echo '<div class="row">';
		echo '<div class="col-md-7"><table class="table table-bordered">';
		echo '<tr class="info"><th width="50">#</th><th>Kelas</th>';
		echo '<th width="200">'; 
		if( $_SESSION['admin'] == 1 ){ 
		echo '<a href="./admin.php?hlm=master&sub=kelas&aksi=view" class="btn btn-default btn-xs">Tambah Data</a>';
		} 
		echo '</th></tr>';
		
		if( mysqli_num_rows($sql) > 0 ){
			$no = 1;
			while(list($kelas,$tapel,$jumlah,$idprodi) = mysqli_fetch_array($sql)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$kelas.' <span class="badge pull-right">'.$jumlah.' siswa</span></td>';
				echo '<td><a href="./admin.php?hlm=master&sub=kelas&aksi=view&kelas='.urlencode($kelas).'&tapel='.$tapel.'&idprodi='.$idprodi.'&submit=y" class="btn btn-success btn-xs">Detail</a> ';
				if( $_SESSION['admin'] == 1 ){
				echo '<a href="./admin.php?hlm=master&sub=kelas&aksi=hapus&kelas='.$kelas.'&tapel='.$tapel.'" class="btn btn-danger btn-xs">Hapus</a></td>';
				}
				echo '</tr>';
				$no++;
			}
		} else {
			echo '<tr><td colspan="4"><em>Belum ada data</em></td></tr>';
		}
		
		echo '</table></div></div>';
	}
}
?>