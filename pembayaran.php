<?php
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	/* tahapan pembayaran SPP
		1. masukkan nis
		2. tampilkan histori pembayaran (jika ada) dan form pembayaran
		3. proses pembayaran, kembali ke nomor 2
	*/
	echo '<h2>Pembayaran SPP</h2><hr>';
	
	if(isset($_REQUEST['submit'])){
		//proses pembayaran secara bertahap
		$user = $_SESSION['iduser'];
		$submit = $_REQUEST['submit'];
		$nis = $_REQUEST['nis'];
		
		//proses simpan pembayaran
		if($submit=='bayar'){
			$kls = $_REQUEST['kls'];
			$bln = $_REQUEST['bln'];
			$tgl = $_REQUEST['tgl'];
			$jml = $_REQUEST['jml'];
			
			$qbayar = mysqli_query($koneksi,"INSERT INTO pembayaran VALUES('','$nis','$bln','$tgl','$jml','$user','$kls')");
			
			if($qbayar > 0){
				header('Location: ./admin.php?hlm=bayar&submit=v&nis='.$nis);
				die();
			} else {
				echo 'ada ERROR dg query';
			}
		}
	
		
		//tampilkan data siswa
		$qsiswa = mysqli_query($koneksi,"SELECT * FROM siswa WHERE nis='$nis'");
		list($nis,$nama,$idprodi) = mysqli_fetch_array($qsiswa);
		

		$angkatan = mysqli_query($koneksi,"SELECT a.idangkatan, b.angkatan, b.biaya_spp FROM siswa a,angkatan b WHERE a.idangkatan = b.idangkatan and nis='$nis'");
		list($ida,$ang,$spp)=mysqli_fetch_array($angkatan);

      echo '<div class="row">';
		echo '<div class="col-sm-9"><table class="table table-bordered">';
		echo '<tr><td colspan="2">Nomor Induk</td><td colspan="4">'.$nis.'</td>';
      echo '<td><a href="./cetak.php?nis='.$nis.'" target="_blank" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> cetak semua</a></td></tr>';
		echo '<tr><td colspan="2">Nama Siswa</td><td colspan="5">'.$nama.'</td></tr>';
		echo '<tr><td colspan="2">Angkatan</td><td colspan="5">'.$ang.'</td></tr>';      
		echo '<tr><td colspan="2">Pembayaran</td><td colspan="5">';
?>
<form class="form-inline" role="form" method="post" action="./admin.php?hlm=bayar">
  <input type="hidden" name="nis" value="<?php echo $nis; ?>">
  <input type="hidden" name="tgl" value="<?php echo date("Y-m-d"); ?>">
  <div class="form-group">
      <label class="sr-only" for="kls">Kelas</label>
	  <select name="kls" class="form-control" id="kls">
	  <?php
		$qkelas = mysqli_query($koneksi,"SELECT a.idkelas, b.kelas FROM siswa a,kelas b WHERE a.idkelas = b.idkelas and nis='$nis'");
		while(list($kelas,$thn)=mysqli_fetch_array($qkelas)){
			echo '<option value="'.$kelas.'">'.$thn.'</option>';
		}
	  ?>
	  </select>
  </div>
  <div class="form-group">
    <label class="sr-only" for="bln">Bulan</label>
	<select name="bln" id="bln" class="form-control">
	<?php
		for($i=1;$i<=12;$i++){
			$b = date('F',mktime(0,0,0,$i,10));
			echo '<option value="'.$b.'">'.$b.'</option>';
		}
	?>
	</select>
  </div>
  <div class="form-group">
	<label class="sr-only" for="jml">Jumlah</label>
	<div class="input-group">
		<div class="input-group-addon">Rp.</div>
		<?php
		$angkatan1 = mysqli_query($koneksi,"SELECT `function_getspp`('$ang')");
		list($spp1)=mysqli_fetch_array($angkatan1);
		?>
		<input type="text" class="form-control" id="jml" name="jml" value="<?php echo $spp1; ?>" readonly>
	</div>
  </div>
  <button type="submit" class="btn btn-default" name="submit" value="bayar">Bayar</button>
</form>
<?php
		echo '</td></tr>';
		echo '<tr class="info"><th width="50">#</th><th width="100">Kelas</th><th>Bulan</th><th>Tanggal Bayar</th><th>Jumlah</th><th>Petugas</th>';
		echo '<th>&nbsp;</th>';
		echo '</tr>';
		
		//tampilkan histori pembayaran, jika ada
		$qbayar = mysqli_query($koneksi,"SELECT b.kelas,a.bulan,a.tgl_bayar,a.jumlah, c.fullname
							FROM pembayaran a, kelas b, petugas c 
							WHERE a.idkelas = b.idkelas and a.idpetugas = c.iduser and nis='$nis' ORDER BY idpembayaran DESC");
		if(mysqli_num_rows($qbayar) > 0){
			$no = 1;
			while(list($kelas,$bulan,$tgl,$jumlah,$petugas) = mysqli_fetch_array($qbayar)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$kelas.'</td>';
				echo '<td>'.$bulan.'</td>';
				echo '<td>'.$tgl.'</td>';
				echo '<td>'.$jumlah.'</td>';
				echo '<td>'.$petugas.'</td><td>';
				
			
            echo ' <a href="./cetak.php?submit=nota&kls='.$kelas.'&nis='.$nis.'&bln='.$bulan.'" target="_blank" class="btn btn-success btn-xs"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></a>';
				echo '</td></tr>';
				
				$no++;
			}
		} else {
			echo '<tr><td colspan="7"><em>Belum ada data!</em></td></tr>';
		}
		echo '</table></div></div>';
		
		//tabel jumlah bayar
		$sql = mysqli_query($koneksi,"SELECT * FROM angkatan ORDER BY angkatan DESC");
		
		echo '<h3>Jumlah Pembayaran</h3><hr>';
      echo '<div class="row">';
		echo '<div class="col-md-7"><table class="table table-bordered">';
		echo '<tr class="info"><th>#</th><th>Tingkat</th><th>Jumlah Nominal</th>';
		
		if(mysqli_num_rows($sql) > 0){
			$no = 1;
			while(list($tapel,$tingkat,$jumlah) = mysqli_fetch_array($sql)){
				echo '<tr><td>'.$no.'</td>';
				echo '<td>'.$tingkat.'</td><td>Rp <span class="pull-right">'.$jumlah.'</span></td>';
				echo '</tr>';
				$no++;
			}
		} else {
			echo '<tr><td colspan="5"><em>Belum ada data!</em></td></tr>';
		}
		
	} else {
?>
<!-- form input nomor induk siswa -->
<form class="form-horizontal" role="form" method="post" action="./admin.php?hlm=bayar">


  
    <div class="form-group">
      <label for="nis" class="col-sm-2 control-label" >Nomor Induk Siswa</label>
	  <div class="col-sm-3">
	  <select name="nis" class="form-control" id="nis">
	  <?php
		$qkelas = mysqli_query($koneksi,"SELECT * FROM siswa");
		while(list($nis,$nm)=mysqli_fetch_array($qkelas)){
			echo '<option value="'.$nis.'">'.$nis.' || '.$nm.'</option>';
		}
	  ?>
	  </select>
	  </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-3">
      <button type="submit" name="submit" class="btn btn-default">Lanjut</button>
    </div>
  </div>
</form>
<?php
	}
}
?>