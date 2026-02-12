-- MySQL dump 10.13  Distrib 8.0.39, for Win64 (x86_64)
--
-- Host: localhost    Database: citizen_complaint
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aduan`
--

DROP TABLE IF EXISTS `aduan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `aduan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `no_aduan` varchar(255) NOT NULL,
  `tanggal_lapor` datetime NOT NULL,
  `isi_aduan` text NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `masyarakat_id` bigint unsigned NOT NULL,
  `kategori_aduan_id` bigint unsigned NOT NULL,
  `akses_aduan_id` bigint unsigned NOT NULL,
  `status_aduan_id` bigint unsigned NOT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `aduan_masyarakat_id_foreign` (`masyarakat_id`),
  KEY `aduan_kategori_aduan_id_foreign` (`kategori_aduan_id`),
  KEY `aduan_akses_aduan_id_foreign` (`akses_aduan_id`),
  KEY `aduan_status_aduan_id_foreign` (`status_aduan_id`),
  CONSTRAINT `aduan_akses_aduan_id_foreign` FOREIGN KEY (`akses_aduan_id`) REFERENCES `akses_aduan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `aduan_kategori_aduan_id_foreign` FOREIGN KEY (`kategori_aduan_id`) REFERENCES `kategori_aduan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `aduan_masyarakat_id_foreign` FOREIGN KEY (`masyarakat_id`) REFERENCES `masyarakat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `aduan_status_aduan_id_foreign` FOREIGN KEY (`status_aduan_id`) REFERENCES `status_aduan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aduan`
--

LOCK TABLES `aduan` WRITE;
/*!40000 ALTER TABLE `aduan` DISABLE KEYS */;
INSERT INTO `aduan` VALUES (1,'ADU-20260207-0001','2026-02-07 10:27:33','fdasfsafsdafsdfdasfdsafsafdsf asfdsfsdafsafsda fsdafsafsa fsafsdafsdafsafsa sfsafsdafsafsdaf sfdasfsafsa','-6.8840475, 107.611538',-6.8840475,107.6115380,'aduan/YShkD81blMoMoVWfpWtImgyqjYzNYNlK47scB3c5.png',1,1,1,4,NULL,'2026-02-07 03:27:33','2026-02-11 08:11:14'),(2,'ADU-20260207-0002','2026-02-07 11:17:53','dfasfsafdsfddfasfsa dfasfdsafdsafdsf dasdfa','Gagal mendapatkan alamat',-6.8840698,107.6115387,'aduan/0QuSfSAHM1zbpR7RdVnLZr9NiyiBP4NrXmcLrn2k.jpg',1,4,1,1,NULL,'2026-02-07 04:17:53','2026-02-07 04:17:53'),(3,'ADU-20260212-0001','2026-02-12 08:32:48','dfasfsafdfasfsafdsfdsfd','Jalan Dipatiukur, Lebak Gede, Kota Bandung, Jawa Barat',-6.8872790,107.6152345,'upload_aduan/ADU-20260212-0001/ADU-20260212-0001_1770885168.png',2,1,2,1,NULL,'2026-02-12 01:32:48','2026-02-12 01:32:48'),(4,'ADU-20260212-0002','2026-02-12 08:33:43','dfasfsafdasfds asaf sadfsaf saf sdafsdafsdafds','Jalan Dipatiukur, Lebak Gede, Kota Bandung, Jawa Barat',-6.8872790,107.6152345,'upload_aduan/ADU-20260212-0002/ADU-20260212-0002_1770885223.png',2,1,1,1,NULL,'2026-02-12 01:33:43','2026-02-12 01:33:43');
/*!40000 ALTER TABLE `aduan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `akses_aduan`
--

DROP TABLE IF EXISTS `akses_aduan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `akses_aduan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_akses_aduan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `akses_aduan`
--

LOCK TABLES `akses_aduan` WRITE;
/*!40000 ALTER TABLE `akses_aduan` DISABLE KEYS */;
INSERT INTO `akses_aduan` VALUES (1,'Publik','Aduan dapat dilihat oleh semua orang','2026-02-07 10:23:13','2026-02-07 10:23:13'),(2,'Privat','Aduan hanya dapat dilihat oleh pelapor dan admin','2026-02-07 10:23:13','2026-02-07 10:23:13');
/*!40000 ALTER TABLE `akses_aduan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hak_akses`
--

DROP TABLE IF EXISTS `hak_akses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hak_akses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `peran_id` bigint unsigned NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `hak_akses_menu_id_foreign` (`menu_id`),
  KEY `hak_akses_peran_id_foreign` (`peran_id`),
  CONSTRAINT `hak_akses_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE,
  CONSTRAINT `hak_akses_peran_id_foreign` FOREIGN KEY (`peran_id`) REFERENCES `peran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hak_akses`
--

LOCK TABLES `hak_akses` WRITE;
/*!40000 ALTER TABLE `hak_akses` DISABLE KEYS */;
/*!40000 ALTER TABLE `hak_akses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_aduan`
--

DROP TABLE IF EXISTS `kategori_aduan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_aduan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_aduan`
--

LOCK TABLES `kategori_aduan` WRITE;
/*!40000 ALTER TABLE `kategori_aduan` DISABLE KEYS */;
INSERT INTO `kategori_aduan` VALUES (1,'Infrastruktur Jalan','Laporan terkait jalan rusak, berlubang, atau perlu perbaikan','2026-02-07 10:23:13','2026-02-07 10:23:13'),(2,'Penerangan Jalan','Laporan lampu jalan mati atau rusak','2026-02-07 10:23:13','2026-02-07 10:23:13'),(3,'Sampah & Kebersihan','Laporan terkait sampah menumpuk, kebersihan lingkungan','2026-02-07 10:23:13','2026-02-07 10:23:13'),(4,'Air Bersih','Laporan terkait masalah air PAM atau air bersih','2026-02-07 10:23:13','2026-02-07 10:23:13'),(5,'Drainase & Saluran','Laporan saluran air tersumbat, banjir, genangan','2026-02-07 10:23:13','2026-02-07 10:23:13'),(6,'Fasilitas Umum','Laporan terkait taman, Terminal, pasar, dll','2026-02-07 10:23:13','2026-02-07 10:23:13'),(7,'Keamanan & Ketertiban','Laporan terkait keamanan lingkungan, premanisme','2026-02-07 10:23:13','2026-02-07 10:23:13'),(8,'Kesehatan','Laporan terkait pelayanan kesehatan, Puskesmas, RS','2026-02-07 10:23:13','2026-02-07 10:23:13'),(9,'Pendidikan','Laporan terkait fasilitas sekolah, pelayanan pendidikan','2026-02-07 10:23:13','2026-02-07 10:23:13'),(10,'Administrasi Kependudukan','Laporan terkait KTP, KK, Akta Kelahiran, dll','2026-02-07 10:23:13','2026-02-07 10:23:13'),(11,'Lainnya','Kategori lain yang tidak termasuk di atas','2026-02-07 10:23:13','2026-02-07 10:23:13');
/*!40000 ALTER TABLE `kategori_aduan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_aduan_opd`
--

DROP TABLE IF EXISTS `kategori_aduan_opd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori_aduan_opd` (
  `kategori_aduan_id` bigint unsigned NOT NULL,
  `opd_id` bigint unsigned NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kategori_aduan_id`,`opd_id`),
  KEY `kategori_aduan_opd_opd_id_foreign` (`opd_id`),
  CONSTRAINT `kategori_aduan_opd_kategori_aduan_id_foreign` FOREIGN KEY (`kategori_aduan_id`) REFERENCES `kategori_aduan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kategori_aduan_opd_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opd` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_aduan_opd`
--

LOCK TABLES `kategori_aduan_opd` WRITE;
/*!40000 ALTER TABLE `kategori_aduan_opd` DISABLE KEYS */;
INSERT INTO `kategori_aduan_opd` VALUES (1,1,'2026-02-07 10:23:14','2026-02-07 10:23:14'),(2,1,'2026-02-07 10:23:14','2026-02-07 10:23:14'),(3,2,'2026-02-07 10:23:14','2026-02-07 10:23:14'),(5,1,'2026-02-07 10:23:14','2026-02-07 10:23:14'),(6,1,'2026-02-07 10:23:14','2026-02-07 10:23:14'),(8,3,'2026-02-07 10:23:14','2026-02-07 10:23:14'),(9,4,'2026-02-07 10:23:14','2026-02-07 10:23:14'),(10,5,'2026-02-07 10:23:14','2026-02-07 10:23:14');
/*!40000 ALTER TABLE `kategori_aduan_opd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `masyarakat`
--

DROP TABLE IF EXISTS `masyarakat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `masyarakat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nik` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `alamat` text,
  `pengguna_id` bigint unsigned NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `masyarakat_pengguna_id_foreign` (`pengguna_id`),
  CONSTRAINT `masyarakat_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `masyarakat`
--

LOCK TABLES `masyarakat` WRITE;
/*!40000 ALTER TABLE `masyarakat` DISABLE KEYS */;
INSERT INTO `masyarakat` VALUES (1,'3212323232323232','Agus Sidiq Perman',NULL,NULL,3,'2026-02-07 03:24:23','2026-02-07 03:24:23'),(2,'3217011408920001','Robi','61413123232','dfasfdsfdsafsaf',4,'2026-02-12 00:16:16','2026-02-12 00:16:16');
/*!40000 ALTER TABLE `masyarakat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id_induk` bigint unsigned DEFAULT NULL,
  `nama_menu` varchar(255) NOT NULL,
  `ikon` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `menu_menu_id_induk_foreign` (`menu_id_induk`),
  CONSTRAINT `menu_menu_id_induk_foreign` FOREIGN KEY (`menu_id_induk`) REFERENCES `menu` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_create_pengguna_table',1),(5,'2024_01_01_000002_create_peran_table',1),(6,'2024_01_01_000003_create_opd_table',1),(7,'2024_01_01_000004_create_kategori_aduan_table',1),(8,'2024_01_01_000005_create_akses_aduan_table',1),(9,'2024_01_01_000006_create_status_aduan_table',1),(10,'2024_01_01_000007_create_reset_kata_sandi_table',1),(11,'2024_01_01_000008_create_peran_pengguna_table',1),(12,'2024_01_01_000009_create_menu_table',1),(13,'2024_01_01_000010_create_hak_akses_table',1),(14,'2024_01_01_000011_create_masyarakat_table',1),(15,'2024_01_01_000012_create_opd_pengguna_table',1),(16,'2024_01_01_000013_create_kategori_aduan_opd_table',1),(17,'2024_01_01_000014_create_aduan_table',1),(18,'2024_01_01_000015_create_riwayat_status_aduan_table',1),(19,'2024_01_01_000016_create_tanggapan_aduan_table',1),(20,'2024_01_01_000008_create_unit_opd_table',2),(21,'2026_02_08_000002_add_penanggung_jawab_to_unit_opd_table',2),(22,'2026_02_08_000003_add_nama_pengguna_to_unit_opd_table',2),(23,'2026_02_09_000001_add_unit_opd_id_to_riwayat_status_aduan_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opd`
--

DROP TABLE IF EXISTS `opd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `opd` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_opd` varchar(255) NOT NULL,
  `no_telp` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opd`
--

LOCK TABLES `opd` WRITE;
/*!40000 ALTER TABLE `opd` DISABLE KEYS */;
INSERT INTO `opd` VALUES (1,'Dinas Pekerjaan Umum','0284-321234','Jl. Pemuda No. 1, Pemalang','2026-02-07 10:23:14','2026-02-07 10:23:14'),(2,'Dinas Lingkungan Hidup','0284-321235','Jl. Pemuda No. 2, Pemalang','2026-02-07 10:23:14','2026-02-07 10:23:14'),(3,'Dinas Kesehatan','0284-321236','Jl. Pemuda No. 3, Pemalang','2026-02-07 10:23:14','2026-02-07 10:23:14'),(4,'Dinas Pendidikan','0284-321237','Jl. Pemuda No. 4, Pemalang','2026-02-07 10:23:14','2026-02-07 10:23:14'),(5,'Dinas Kependudukan & Catatan Sipil','0284-321238','Jl. Pemuda No. 5, Pemalang','2026-02-07 10:23:14','2026-02-07 10:23:14');
/*!40000 ALTER TABLE `opd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `opd_pengguna`
--

DROP TABLE IF EXISTS `opd_pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `opd_pengguna` (
  `pengguna_id` bigint unsigned NOT NULL,
  `opd_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`pengguna_id`,`opd_id`),
  KEY `opd_pengguna_opd_id_foreign` (`opd_id`),
  CONSTRAINT `opd_pengguna_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opd` (`id`) ON DELETE CASCADE,
  CONSTRAINT `opd_pengguna_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `opd_pengguna`
--

LOCK TABLES `opd_pengguna` WRITE;
/*!40000 ALTER TABLE `opd_pengguna` DISABLE KEYS */;
INSERT INTO `opd_pengguna` VALUES (2,1);
/*!40000 ALTER TABLE `opd_pengguna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengguna` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_pengguna` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status_verifikasi` tinyint(1) NOT NULL DEFAULT '0',
  `email_verifikasi` datetime DEFAULT NULL,
  `token_verifikasi` varchar(255) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengguna_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengguna`
--

LOCK TABLES `pengguna` WRITE;
/*!40000 ALTER TABLE `pengguna` DISABLE KEYS */;
INSERT INTO `pengguna` VALUES (1,'Administrator','admin@pemalang.go.id','$2y$12$UEHFXAGrBQGPUXiUkPM7Bub14B6erIIIfdCgafufIpQNo58rtKOo.',1,'2026-02-07 10:23:14',NULL,1,'2026-02-07 03:23:14','2026-02-07 03:23:14'),(2,'OPD Dinas PU','pu@pemalang.go.id','$2y$12$5v./ALwvJ5GmHiGsVBhX2.nq7elwa5F4xtpVaYQy.VXRIAS/svKmS',1,'2026-02-07 10:23:14',NULL,1,'2026-02-07 03:23:14','2026-02-07 03:23:14'),(3,'Agus Sidiq Perman','sidiqagus.as@gmail.com','$2y$12$Mx6PiYNlKsir352WVbtfQ.c5nLA1wI4thjmVer80Ae8MXaJuQIdP6',0,NULL,NULL,1,'2026-02-07 03:24:23','2026-02-07 03:24:23'),(4,'Robi','robi@gmail.com','$2y$12$YmDITI.ULgIF.kdymnLwLOiNMa6HEk2uBUaoZNELBh/JXdA5DVI/O',0,NULL,NULL,1,'2026-02-12 00:16:16','2026-02-12 00:16:16');
/*!40000 ALTER TABLE `pengguna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peran`
--

DROP TABLE IF EXISTS `peran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_peran` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peran`
--

LOCK TABLES `peran` WRITE;
/*!40000 ALTER TABLE `peran` DISABLE KEYS */;
INSERT INTO `peran` VALUES (1,'Admin'),(2,'OPD'),(3,'Masyarakat');
/*!40000 ALTER TABLE `peran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peran_pengguna`
--

DROP TABLE IF EXISTS `peran_pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peran_pengguna` (
  `pengguna_id` bigint unsigned NOT NULL,
  `peran_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`pengguna_id`,`peran_id`),
  KEY `peran_pengguna_peran_id_foreign` (`peran_id`),
  CONSTRAINT `peran_pengguna_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  CONSTRAINT `peran_pengguna_peran_id_foreign` FOREIGN KEY (`peran_id`) REFERENCES `peran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peran_pengguna`
--

LOCK TABLES `peran_pengguna` WRITE;
/*!40000 ALTER TABLE `peran_pengguna` DISABLE KEYS */;
INSERT INTO `peran_pengguna` VALUES (1,1),(2,2),(3,3),(4,3);
/*!40000 ALTER TABLE `peran_pengguna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reset_kata_sandi`
--

DROP TABLE IF EXISTS `reset_kata_sandi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reset_kata_sandi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pengguna_id` bigint unsigned NOT NULL,
  `token_reset` varchar(255) NOT NULL,
  `waktu_kadaluarsa` datetime NOT NULL,
  `status_reset` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reset_kata_sandi_pengguna_id_foreign` (`pengguna_id`),
  CONSTRAINT `reset_kata_sandi_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reset_kata_sandi`
--

LOCK TABLES `reset_kata_sandi` WRITE;
/*!40000 ALTER TABLE `reset_kata_sandi` DISABLE KEYS */;
/*!40000 ALTER TABLE `reset_kata_sandi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riwayat_status_aduan`
--

DROP TABLE IF EXISTS `riwayat_status_aduan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riwayat_status_aduan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `waktu_status_aduan` datetime NOT NULL,
  `catatan` text NOT NULL,
  `aduan_id` bigint unsigned NOT NULL,
  `status_aduan_id` bigint unsigned NOT NULL,
  `unit_opd_id` bigint unsigned DEFAULT NULL,
  `pengguna_id` bigint unsigned NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `riwayat_status_aduan_aduan_id_foreign` (`aduan_id`),
  KEY `riwayat_status_aduan_status_aduan_id_foreign` (`status_aduan_id`),
  KEY `riwayat_status_aduan_pengguna_id_foreign` (`pengguna_id`),
  KEY `riwayat_status_aduan_unit_opd_id_foreign` (`unit_opd_id`),
  CONSTRAINT `riwayat_status_aduan_aduan_id_foreign` FOREIGN KEY (`aduan_id`) REFERENCES `aduan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `riwayat_status_aduan_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE,
  CONSTRAINT `riwayat_status_aduan_status_aduan_id_foreign` FOREIGN KEY (`status_aduan_id`) REFERENCES `status_aduan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `riwayat_status_aduan_unit_opd_id_foreign` FOREIGN KEY (`unit_opd_id`) REFERENCES `unit_opd` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riwayat_status_aduan`
--

LOCK TABLES `riwayat_status_aduan` WRITE;
/*!40000 ALTER TABLE `riwayat_status_aduan` DISABLE KEYS */;
INSERT INTO `riwayat_status_aduan` VALUES (1,'2026-02-11 00:00:00','fdasfds',1,2,NULL,2,'2026-02-11 01:10:43','2026-02-11 01:10:43'),(2,'2026-02-11 08:11:06','Status diubah oleh OPD',1,2,NULL,2,'2026-02-11 08:11:06','2026-02-11 08:11:06'),(3,'2026-02-11 08:11:14','Status diubah oleh OPD',1,4,NULL,2,'2026-02-11 08:11:14','2026-02-11 08:11:14'),(4,'2026-02-12 08:32:48','Aduan berhasil diajukan',3,1,NULL,4,'2026-02-12 01:32:48','2026-02-12 01:32:48'),(5,'2026-02-12 08:33:43','Aduan berhasil diajukan',4,1,NULL,4,'2026-02-12 01:33:43','2026-02-12 01:33:43');
/*!40000 ALTER TABLE `riwayat_status_aduan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('jihtbQmo0gzLUDY19RWMmQbqgzqS6UcYFOAth7qq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTozOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiWjl1NEpyWmx2NHZLcUxmOWZKOE9oRUFla2NBZDVkUzZGMjB6NXpsNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7fX0=',1770885138),('mweMqWkPOM8UFaT84jGp0Hr1uDD6LgNZb1ij67oq',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YToyOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoiRmNMWE8zNk5lMWtxMlhPRXVkNWZoZTRLY2hiUFlSOGptVEMzSzBPRyI7fQ==',1770885865);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `status_aduan`
--

DROP TABLE IF EXISTS `status_aduan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `status_aduan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_status` varchar(255) NOT NULL,
  `urutan` int NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `status_aduan`
--

LOCK TABLES `status_aduan` WRITE;
/*!40000 ALTER TABLE `status_aduan` DISABLE KEYS */;
INSERT INTO `status_aduan` VALUES (1,'Diajukan',1,'2026-02-07 10:23:13','2026-02-07 10:23:13'),(2,'Diverifikasi',2,'2026-02-07 10:23:13','2026-02-07 10:23:13'),(3,'Ditolak',3,'2026-02-07 10:23:13','2026-02-07 10:23:13'),(4,'Diproses',4,'2026-02-07 10:23:13','2026-02-07 10:23:13'),(5,'Selesai',5,'2026-02-07 10:23:13','2026-02-07 10:23:13');
/*!40000 ALTER TABLE `status_aduan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tanggapan_aduan`
--

DROP TABLE IF EXISTS `tanggapan_aduan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tanggapan_aduan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_tanggapan` datetime NOT NULL,
  `aduan_id` bigint unsigned NOT NULL,
  `pengguna_id` bigint unsigned NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tanggapan_aduan_aduan_id_foreign` (`aduan_id`),
  KEY `tanggapan_aduan_pengguna_id_foreign` (`pengguna_id`),
  CONSTRAINT `tanggapan_aduan_aduan_id_foreign` FOREIGN KEY (`aduan_id`) REFERENCES `aduan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tanggapan_aduan_pengguna_id_foreign` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tanggapan_aduan`
--

LOCK TABLES `tanggapan_aduan` WRITE;
/*!40000 ALTER TABLE `tanggapan_aduan` DISABLE KEYS */;
INSERT INTO `tanggapan_aduan` VALUES (1,'2026-02-11 00:00:00',1,2,'2026-02-11 08:10:43');
/*!40000 ALTER TABLE `tanggapan_aduan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unit_opd`
--

DROP TABLE IF EXISTS `unit_opd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit_opd` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `opd_id` bigint unsigned NOT NULL,
  `nama_unit` varchar(255) NOT NULL,
  `kode_unit` varchar(255) DEFAULT NULL,
  `penanggung_jawab` varchar(255) DEFAULT NULL,
  `nama_pengguna` varchar(255) DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `unit_opd_opd_id_foreign` (`opd_id`),
  CONSTRAINT `unit_opd_opd_id_foreign` FOREIGN KEY (`opd_id`) REFERENCES `opd` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_opd`
--

LOCK TABLES `unit_opd` WRITE;
/*!40000 ALTER TABLE `unit_opd` DISABLE KEYS */;
/*!40000 ALTER TABLE `unit_opd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-12 21:42:56
