<?php
if( empty( $_SESSION['iduser'] ) ){
	$_SESSION['err'] = '<strong>ERROR!</strong> Anda harus login terlebih dahulu.';
	header('Location: ./');
	die();
} else {
	echo "
	<div class='form-group'><form class='form-inline' role='form' method='post' action=''>
    <label class=' control-label' for='bln'>Bulan : </label>

	<select name='bln' id='bln' class='form-control'>";

		for($i=1;$i<=12;$i++){
			$b = date('F',mktime(0,0,0,$i,10));
			echo '<option value="'.$b.'">'.$b.'</option>';
		}

	echo "</select>
  </div> 
  <button type='submit' name='submit' class='btn btn-default'>Tampilkan</button>
  </form>
  ";
  if(isset($_REQUEST['submit'])){
		$submit = $_REQUEST['submit'];
         $bln = $_REQUEST['bln'];
   echo '<h2>Tagihan Pembayaran Bulan '.$bln.'</h2><hr>';
   echo '<a class="noprint pull-right btn btn-default" onclick="fnCetak()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Cetak</a>';
         
		$sql = mysqli_query($koneksi,"SELECT s.nis,s.nama_siswa,k.kelas,p.bulan,p.jumlah FROM (siswa s INNER JOIN kelas k ON s.idkelas = k.idkelas) LEFT JOIN pembayaran p ON s.nis = p.nis and p.bulan = '$bln' ORDER BY k.idkelas, s.nis");
   
   echo '<div class="row">';
   echo '<div class="col-md-7">';
   echo '<table class="table table-bordered">';
   echo '<tr class="info"><th width="50">#</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Bulan</th><th>Jumlah</th></tr>';
   
   $no=1;
   while(list($nis,$nama,$kls,$bln,$jml)=mysqli_fetch_array($sql)){
      echo '<tr><td>'.$no.'</td><td>'.$nis.'</td><td>'.$nama.'</td><td>'.$kls.'</td>';
      if(empty($bln) AND empty($jml)){
         echo '<td>--</td><td>BL</td></tr>';
      } else {
         echo '<td>'.$bln.'</td><td>LUNAS</td></tr>';
      }
      $no++;
   }
   
   echo '</table></div></div>';
}
}

?>