-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 5.6.20 - MySQL Community Server (GPL)
-- OS Server:                    Win32
-- HeidiSQL Versi:               9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for fpsmbd1
CREATE DATABASE IF NOT EXISTS `fpsmbd1` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `fpsmbd1`;


-- Dumping structure for table fpsmbd1.angkatan
CREATE TABLE IF NOT EXISTS `angkatan` (
  `idangkatan` int(11) NOT NULL AUTO_INCREMENT,
  `angkatan` varchar(50) DEFAULT NULL,
  `biaya_spp` int(11) DEFAULT NULL,
  PRIMARY KEY (`idangkatan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table fpsmbd1.angkatan: ~3 rows (approximately)
/*!40000 ALTER TABLE `angkatan` DISABLE KEYS */;
INSERT INTO `angkatan` (`idangkatan`, `angkatan`, `biaya_spp`) VALUES
	(1, '2018', 70000),
	(2, '2019', 60000),
	(3, '2020', 50000);
/*!40000 ALTER TABLE `angkatan` ENABLE KEYS */;


-- Dumping structure for function fpsmbd1.function_getspp
DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `function_getspp`(`jumlah` int) RETURNS int(11)
BEGIN
DECLARE angkat int; CASE
WHEN (jumlah = 2018) THEN SET angkat = 70000;
WHEN (jumlah = 2019) THEN SET angkat = 60000;
WHEN (jumlah = 2020) THEN SET angkat = 50000;
ELSE SET angkat = 0; END CASE;
RETURN angkat;
END//
DELIMITER ;


-- Dumping structure for table fpsmbd1.kelas
CREATE TABLE IF NOT EXISTS `kelas` (
  `idkelas` int(11) NOT NULL AUTO_INCREMENT,
  `kelas` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idkelas`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table fpsmbd1.kelas: ~8 rows (approximately)
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` (`idkelas`, `kelas`) VALUES
	(1, 'X-RPL1'),
	(2, 'X-RPL2'),
	(3, 'XI-RPL1'),
	(4, 'XI-RPL2'),
	(5, 'XII-RPL'),
	(6, 'X-TKJ'),
	(9, 'X-ipa'),
	(10, 'X-ips');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;


-- Dumping structure for table fpsmbd1.pembayaran
CREATE TABLE IF NOT EXISTS `pembayaran` (
  `idpembayaran` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(50) NOT NULL DEFAULT '0',
  `bulan` varchar(50) NOT NULL DEFAULT '0',
  `tgl_bayar` date NOT NULL DEFAULT '0000-00-00',
  `jumlah` double NOT NULL DEFAULT '0',
  `idpetugas` int(11) DEFAULT NULL,
  `idkelas` int(11) DEFAULT NULL,
  PRIMARY KEY (`idpembayaran`),
  KEY `nis` (`nis`),
  KEY `idpetugas` (`idpetugas`),
  KEY `idkelas` (`idkelas`),
  CONSTRAINT `FK_pembayaran_kelas` FOREIGN KEY (`idkelas`) REFERENCES `kelas` (`idkelas`),
  CONSTRAINT `FK_pembayaran_petugas` FOREIGN KEY (`idpetugas`) REFERENCES `petugas` (`iduser`),
  CONSTRAINT `FK_pembayaran_siswa` FOREIGN KEY (`nis`) REFERENCES `siswa` (`nis`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table fpsmbd1.pembayaran: ~2 rows (approximately)
/*!40000 ALTER TABLE `pembayaran` DISABLE KEYS */;
INSERT INTO `pembayaran` (`idpembayaran`, `nis`, `bulan`, `tgl_bayar`, `jumlah`, `idpetugas`, `idkelas`) VALUES
	(1, '441100141', 'January', '2021-05-31', 70000, 1, 1),
	(2, '441100141', 'February', '2021-05-31', 70000, 1, 1),
	(3, '441100140', 'January', '2021-05-31', 50000, 1, 5),
	(4, '441100142', 'January', '2021-06-02', 70000, 2, 5),
	(5, '2014201503', 'January', '2021-06-03', 60000, 1, 4);
/*!40000 ALTER TABLE `pembayaran` ENABLE KEYS */;


-- Dumping structure for table fpsmbd1.petugas
CREATE TABLE IF NOT EXISTS `petugas` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '0',
  `password` varchar(50) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `fullname` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table fpsmbd1.petugas: ~5 rows (approximately)
/*!40000 ALTER TABLE `petugas` DISABLE KEYS */;
INSERT INTO `petugas` (`iduser`, `username`, `password`, `admin`, `fullname`) VALUES
	(1, 'ali', '2', 1, 'Ali Gufron'),
	(2, 'fiqih', '123', 1, 'Fiqih Afrizal'),
	(3, 'Alfina', '0', 1, 'Alfinaimah'),
	(4, 'Harun', '1', 1, 'Alfian Harun'),
	(5, 'anang', '1', 1, 'Anang Makruf'),
	(7, 'abet', '1', 0, '11');
/*!40000 ALTER TABLE `petugas` ENABLE KEYS */;


-- Dumping structure for procedure fpsmbd1.prosedur_kelas
DELIMITER //
CREATE DEFINER=`root`@`localhost` PROCEDURE `prosedur_kelas`()
BEGIN
SELECT kelas.kelas, kelas.idkelas, count(siswa.idkelas) as jml, siswa.idangkatan 
FROM kelas, siswa WHERE kelas.idkelas=siswa.idkelas  GROUP BY kelas.kelas;
END//
DELIMITER ;


-- Dumping structure for table fpsmbd1.siswa
CREATE TABLE IF NOT EXISTS `siswa` (
  `nis` varchar(50) NOT NULL,
  `nama_siswa` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `idkelas` int(11) DEFAULT NULL,
  `idangkatan` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`nis`),
  KEY `idkelas` (`idkelas`),
  KEY `idangkatan` (`idangkatan`),
  CONSTRAINT `FK_siswa_angkatan` FOREIGN KEY (`idangkatan`) REFERENCES `angkatan` (`idangkatan`),
  CONSTRAINT `FK_siswa_kelas` FOREIGN KEY (`idkelas`) REFERENCES `kelas` (`idkelas`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fpsmbd1.siswa: ~5 rows (approximately)
/*!40000 ALTER TABLE `siswa` DISABLE KEYS */;
INSERT INTO `siswa` (`nis`, `nama_siswa`, `alamat`, `idkelas`, `idangkatan`, `username`, `password`) VALUES
	('2014201503', 'cherly', 'jkt', 4, 2, 'cherly', '1'),
	('441100140', 'Emilia', 'Sampang', 5, 1, 'emi', '1'),
	('441100141', 'Anisa Rahma', 'Tambak Madya Surabaya', 1, 3, 'anisa', '1'),
	('441100142', 'faried', 'pamekasan', 5, 3, 'faried', '1'),
	('441100144', 'Daved', 'bangkalan', 6, 2, 'daved', '1');
/*!40000 ALTER TABLE `siswa` ENABLE KEYS */;


-- Dumping structure for table fpsmbd1.siswa_hapus
CREATE TABLE IF NOT EXISTS `siswa_hapus` (
  `nis` varchar(50) NOT NULL,
  `nama_siswa` varchar(50) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `idkelas` int(11) DEFAULT NULL,
  `idangkatan` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `tgl_perubahan` datetime DEFAULT NULL,
  `nama_user` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`nis`),
  KEY `idkelas` (`idkelas`),
  KEY `idangkatan` (`idangkatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table fpsmbd1.siswa_hapus: ~0 rows (approximately)
/*!40000 ALTER TABLE `siswa_hapus` DISABLE KEYS */;
INSERT INTO `siswa_hapus` (`nis`, `nama_siswa`, `alamat`, `idkelas`, `idangkatan`, `username`, `password`, `tgl_perubahan`, `nama_user`) VALUES
	('1', NULL, NULL, NULL, NULL, NULL, NULL, '2021-06-05 19:59:45', 'root@localhost');
/*!40000 ALTER TABLE `siswa_hapus` ENABLE KEYS */;


-- Dumping structure for view fpsmbd1.view_siswa
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `view_siswa` (
	`nis` VARCHAR(50) NOT NULL COLLATE 'latin1_swedish_ci',
	`nama_siswa` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`alamat` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`idkelas` INT(11) NULL,
	`idangkatan` INT(11) NULL,
	`username` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci',
	`password` VARCHAR(50) NULL COLLATE 'latin1_swedish_ci'
) ENGINE=MyISAM;


-- Dumping structure for trigger fpsmbd1.trigger_hapus_siswa
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trigger_hapus_siswa` AFTER DELETE ON `siswa` FOR EACH ROW BEGIN
  INSERT INTO siswa_hapus
  VALUES
        (       OLD.nis,
                OLD.nama_siswa,
                OLD.alamat,
                OLD.idkelas,
                OLD.idangkatan,
                OLD.username,
                OLD.password,
                SYSDATE(),
                CURRENT_USER
        );
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;


-- Dumping structure for view fpsmbd1.view_siswa
-- Menghapus tabel sementara dan menciptakan struktur VIEW terakhir
DROP TABLE IF EXISTS `view_siswa`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` VIEW `view_siswa` AS SELECT * FROM siswa ORDER BY nis ;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
