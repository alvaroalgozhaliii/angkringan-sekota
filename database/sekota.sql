-- ============================================================
-- DATABASE: sekota
-- Web: Angkringan Sekota
-- Charset: utf8mb4
-- Collation: utf8mb4_0900_ai_ci
-- Engine: InnoDB
-- Generated: 2026-11-04
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+07:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- ============================================================
-- Buat database
-- ============================================================
CREATE DATABASE IF NOT EXISTS `sekota`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_0900_ai_ci;

USE `sekota`;

-- ============================================================
--  BAGIAN 1: TABEL BAWAAN LARAVEL
-- ============================================================

-- Tabel: users
CREATE TABLE `users` (
  `id`                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`              VARCHAR(255)    NOT NULL,
  `email`             VARCHAR(255)    NOT NULL,
  `email_verified_at` TIMESTAMP       NULL DEFAULT NULL,
  `password`          VARCHAR(255)    NOT NULL,
  `remember_token`    VARCHAR(100)    NULL DEFAULT NULL,
  `created_at`        TIMESTAMP       NULL DEFAULT NULL,
  `updated_at`        TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: password_reset_tokens
CREATE TABLE `password_reset_tokens` (
  `email`      VARCHAR(255) NOT NULL,
  `token`      VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP    NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: sessions
CREATE TABLE `sessions` (
  `id`            VARCHAR(255)    NOT NULL,
  `user_id`       BIGINT UNSIGNED NULL DEFAULT NULL,
  `ip_address`    VARCHAR(45)     NULL DEFAULT NULL,
  `user_agent`    TEXT            NULL DEFAULT NULL,
  `payload`       LONGTEXT        NOT NULL,
  `last_activity` INT             NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index`       (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: cache
CREATE TABLE `cache` (
  `key`        VARCHAR(255) NOT NULL,
  `value`      MEDIUMTEXT   NOT NULL,
  `expiration` INT          NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: cache_locks
CREATE TABLE `cache_locks` (
  `key`        VARCHAR(255) NOT NULL,
  `owner`      VARCHAR(255) NOT NULL,
  `expiration` INT          NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: jobs
CREATE TABLE `jobs` (
  `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `queue`        VARCHAR(255)     NOT NULL,
  `payload`      LONGTEXT         NOT NULL,
  `attempts`     TINYINT UNSIGNED NOT NULL,
  `reserved_at`  INT UNSIGNED     NULL DEFAULT NULL,
  `available_at` INT UNSIGNED     NOT NULL,
  `created_at`   INT UNSIGNED     NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: job_batches
CREATE TABLE `job_batches` (
  `id`             VARCHAR(255) NOT NULL,
  `name`           VARCHAR(255) NOT NULL,
  `total_jobs`     INT          NOT NULL,
  `pending_jobs`   INT          NOT NULL,
  `failed_jobs`    INT          NOT NULL,
  `failed_job_ids` LONGTEXT     NOT NULL,
  `options`        MEDIUMTEXT   NULL DEFAULT NULL,
  `cancelled_at`   INT          NULL DEFAULT NULL,
  `created_at`     INT          NOT NULL,
  `finished_at`    INT          NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: failed_jobs
CREATE TABLE `failed_jobs` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid`       VARCHAR(255)    NOT NULL,
  `connection` TEXT            NOT NULL,
  `queue`      TEXT            NOT NULL,
  `payload`    LONGTEXT        NOT NULL,
  `exception`  LONGTEXT        NOT NULL,
  `failed_at`  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabel: migrations
CREATE TABLE `migrations` (
  `id`        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch`     INT          NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `migrations` (`migration`, `batch`) VALUES
  ('0001_01_01_000000_create_users_table',              1),
  ('0001_01_01_000001_create_cache_table',              1),
  ('0001_01_01_000002_create_jobs_table',               1),
  ('2026_11_04_000001_create_categories_table',         2),
  ('2026_11_04_000002_create_menu_items_table',         2),
  ('2026_11_04_000003_create_gallery_table',            2),
  ('2026_11_04_000004_create_operating_hours_table',    2),
  ('2026_11_04_000005_create_contacts_table',           2),
  ('2026_11_04_000006_create_testimonials_table',       2),
  ('2026_11_04_000007_create_site_settings_table',      2);


-- ============================================================
--  BAGIAN 2: TABEL KHUSUS ANGKRINGAN SEKOTA
-- ============================================================

-- Tabel: categories
CREATE TABLE `categories` (
  `id`          BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100)     NOT NULL COMMENT 'Nama kategori',
  `slug`        VARCHAR(100)     NOT NULL COMMENT 'Slug untuk URL',
  `icon`        VARCHAR(100)     NULL DEFAULT NULL,
  `sort_order`  TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1)       NOT NULL DEFAULT 1,
  `created_at`  TIMESTAMP        NULL DEFAULT NULL,
  `updated_at`  TIMESTAMP        NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Kategori menu Angkringan Sekota';

INSERT INTO `categories` (`name`, `slug`, `icon`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  ('Nasi Kucing',   'nasi-kucing',   NULL, 1, 1, NOW(), NOW()),
  ('Gorengan',      'gorengan',      NULL, 2, 1, NOW(), NOW()),
  ('Sate',          'sate',          NULL, 3, 1, NOW(), NOW()),
  ('Minuman Panas', 'minuman-panas', NULL, 4, 1, NOW(), NOW()),
  ('Minuman Dingin','minuman-dingin',NULL, 5, 1, NOW(), NOW()),
  ('Jajanan',       'jajanan',       NULL, 6, 1, NOW(), NOW());

-- Tabel: menu_items
CREATE TABLE `menu_items` (
  `id`           BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `category_id`  BIGINT UNSIGNED  NOT NULL,
  `name`         VARCHAR(150)     NOT NULL,
  `slug`         VARCHAR(150)     NOT NULL,
  `description`  TEXT             NULL DEFAULT NULL,
  `price`        INT UNSIGNED     NOT NULL DEFAULT 0 COMMENT 'Harga dalam Rupiah',
  `image`        VARCHAR(255)     NULL DEFAULT NULL,
  `is_featured`  TINYINT(1)       NOT NULL DEFAULT 0,
  `is_available` TINYINT(1)       NOT NULL DEFAULT 1,
  `sort_order`   TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`   TIMESTAMP        NULL DEFAULT NULL,
  `updated_at`   TIMESTAMP        NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menu_items_slug_unique` (`slug`),
  KEY `menu_items_category_id_index` (`category_id`),
  KEY `menu_items_is_featured_index` (`is_featured`),
  CONSTRAINT `menu_items_category_id_foreign`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Daftar menu makanan dan minuman';

INSERT INTO `menu_items` (`category_id`, `name`, `slug`, `description`, `price`, `image`, `is_featured`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES
  (1, 'Nasi Kucing Tempe',   'nasi-kucing-tempe',   'Nasi putih dibungkus daun pisang dengan lauk tempe bacem dan sambal', 3000, 'menu/nasi_kucing_tempe.jpg', 1, 1, 1, NOW(), NOW()),
  (1, 'Nasi Kucing Teri',    'nasi-kucing-teri',    'Nasi putih dibungkus daun pisang dengan lauk teri goreng dan sambal',3000, 'menu/nasi_kucing_teri.jpg',  1, 1, 2, NOW(), NOW()),
  (1, 'Nasi Kucing Ayam',    'nasi-kucing-ayam',    'Nasi putih dibungkus daun pisang dengan lauk suwir ayam pedas',      4000, 'menu/nasi_kucing_ayam.jpg', 1, 1, 3, NOW(), NOW()),
  (2, 'Tempe Goreng',        'tempe-goreng',        'Tempe tepung crispy, digoreng hingga keemasan',                      1500, 'menu/tempe_goreng.jpg',     1, 1, 1, NOW(), NOW()),
  (2, 'Tahu Goreng',         'tahu-goreng',         'Tahu putih digoreng dengan tepung bumbu gurih',                      1500, 'menu/tahu_goreng.jpg',      1, 1, 2, NOW(), NOW()),
  (2, 'Bakwan Jagung',       'bakwan-jagung',       'Bakwan dengan isian jagung manis, wortel, dan daun bawang',          2000, 'menu/bakwan_jagung.jpg',    1, 1, 3, NOW(), NOW()),
  (2, 'Pisang Goreng',       'pisang-goreng',       'Pisang kepok digoreng dengan balutan tepung tipis gurih',            2000, 'menu/pisang_goreng.jpg',    0, 1, 4, NOW(), NOW()),
  (3, 'Sate Usus',           'sate-usus',           'Usus ayam yang dibakar dengan kecap manis dan bumbu pilihan',        1500, 'menu/sate_usus.jpg',        1, 1, 1, NOW(), NOW()),
  (3, 'Sate Telur Puyuh',    'sate-telur-puyuh',    'Telur puyuh rebus dibakar dengan bumbu kecap pedas manis',           2000, 'menu/sate_telur_puyuh.jpg', 1, 1, 2, NOW(), NOW()),
  (3, 'Sate Kulit',          'sate-kulit',          'Kulit ayam gurih dibakar di atas bara dengan bumbu pilihan',         2000, 'menu/sate_kulit.jpg',       0, 1, 3, NOW(), NOW()),
  (4, 'Wedang Jahe',         'wedang-jahe',         'Minuman jahe hangat dengan rempah pilihan, menghangatkan badan',     5000, 'menu/wedang_jahe.jpg',      1, 1, 1, NOW(), NOW()),
  (4, 'Teh Poci',            'teh-poci',            'Teh tubruk diseduh dalam poci tanah liat dengan batu gula',          4000, 'menu/teh_poci.jpg',         1, 1, 2, NOW(), NOW()),
  (4, 'Kopi Hitam',          'kopi-hitam',          'Kopi hitam tubruk khas angkringan, pekat dan nikmat',                4000, 'menu/kopi_hitam.jpg',       0, 1, 3, NOW(), NOW()),
  (4, 'Wedang Uwuh',         'wedang-uwuh',         'Minuman tradisional dari rempah-rempah pilihan, kaya antioksidan',   6000, 'menu/wedang_uwuh.jpg',      1, 1, 4, NOW(), NOW()),
  (5, 'Es Teh',              'es-teh',              'Teh manis dingin segar, pemanis hari-hari',                          3000, 'menu/es_teh.jpg',           0, 1, 1, NOW(), NOW()),
  (5, 'Es Jeruk',            'es-jeruk',            'Air perasan jeruk segar dicampur es batu',                           4000, 'menu/es_jeruk.jpg',         0, 1, 2, NOW(), NOW()),
  (6, 'Roti Bakar',          'roti-bakar',          'Roti tawar dibakar dengan olesan mentega dan meses cokelat',         5000, 'menu/roti_bakar.jpg',       0, 1, 1, NOW(), NOW()),
  (6, 'Lemper',              'lemper',              'Ketan isi ayam suwir berbumbu, dibungkus daun pisang',               3000, 'menu/lemper.jpg',           0, 1, 2, NOW(), NOW());

-- Tabel: gallery
CREATE TABLE `gallery` (
  `id`         BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `title`      VARCHAR(150)     NULL DEFAULT NULL,
  `image`      VARCHAR(255)     NOT NULL,
  `alt_text`   VARCHAR(255)     NULL DEFAULT NULL,
  `sort_order` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `is_active`  TINYINT(1)       NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP        NULL DEFAULT NULL,
  `updated_at` TIMESTAMP        NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Galeri foto Angkringan Sekota';

INSERT INTO `gallery` (`title`, `image`, `alt_text`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
  ('Suasana Malam',       'gallery/suasana_malam.jpg',  'Suasana malam Angkringan Sekota',  1, 1, NOW(), NOW()),
  ('Deretan Sate',        'gallery/menu_sate.jpg',      'Berbagai pilihan sate angkringan', 2, 1, NOW(), NOW()),
  ('Nasi Kucing',         'gallery/nasi_kucing.jpg',    'Nasi kucing khas angkringan',      3, 1, NOW(), NOW()),
  ('Wedang Hangat',       'gallery/wedang_hangat.jpg',  'Minuman wedang tradisional',       4, 1, NOW(), NOW()),
  ('Gorengan Crispy',     'gallery/gorengan.jpg',       'Gorengan crispy angkringan',       5, 1, NOW(), NOW()),
  ('Momen Kebersamaan',   'gallery/kebersamaan.jpg',    'Pelanggan menikmati angkringan',   6, 1, NOW(), NOW());

-- Tabel: operating_hours
CREATE TABLE `operating_hours` (
  `id`         BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `day_name`   VARCHAR(20)      NOT NULL COMMENT 'Nama hari',
  `day_index`  TINYINT UNSIGNED NOT NULL COMMENT '0=Senin ... 6=Minggu',
  `open_time`  TIME             NULL DEFAULT NULL,
  `close_time` TIME             NULL DEFAULT NULL,
  `is_closed`  TINYINT(1)       NOT NULL DEFAULT 0,
  `note`       VARCHAR(100)     NULL DEFAULT NULL,
  `created_at` TIMESTAMP        NULL DEFAULT NULL,
  `updated_at` TIMESTAMP        NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `operating_hours_day_index_unique` (`day_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Jam operasional warung';

INSERT INTO `operating_hours` (`day_name`, `day_index`, `open_time`, `close_time`, `is_closed`, `note`, `created_at`, `updated_at`) VALUES
  ('Senin',  0, '17:00:00', '00:00:00', 0, NULL,   NOW(), NOW()),
  ('Selasa', 1, '17:00:00', '00:00:00', 0, NULL,   NOW(), NOW()),
  ('Rabu',   2, NULL,       NULL,       1, 'Libur', NOW(), NOW()),
  ('Kamis',  3, '17:00:00', '00:00:00', 0, NULL,   NOW(), NOW()),
  ('Jumat',  4, '17:00:00', '01:00:00', 0, NULL,   NOW(), NOW()),
  ('Sabtu',  5, '16:00:00', '01:00:00', 0, NULL,   NOW(), NOW()),
  ('Minggu', 6, '16:00:00', '00:00:00', 0, NULL,   NOW(), NOW());

-- Tabel: contacts
CREATE TABLE `contacts` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100)    NOT NULL,
  `email`      VARCHAR(150)    NULL DEFAULT NULL,
  `phone`      VARCHAR(20)     NULL DEFAULT NULL,
  `message`    TEXT            NOT NULL,
  `is_read`    TINYINT(1)      NOT NULL DEFAULT 0,
  `read_at`    TIMESTAMP       NULL DEFAULT NULL,
  `ip_address` VARCHAR(45)     NULL DEFAULT NULL,
  `created_at` TIMESTAMP       NULL DEFAULT NULL,
  `updated_at` TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_is_read_index`    (`is_read`),
  KEY `contacts_created_at_index` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Pesan masuk dari form kontak';

-- Tabel: testimonials
CREATE TABLE `testimonials` (
  `id`            BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `customer_name` VARCHAR(100)     NOT NULL,
  `avatar`        VARCHAR(255)     NULL DEFAULT NULL,
  `review`        TEXT             NOT NULL,
  `rating`        TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `source`        VARCHAR(50)      NULL DEFAULT NULL,
  `is_featured`   TINYINT(1)       NOT NULL DEFAULT 0,
  `is_active`     TINYINT(1)       NOT NULL DEFAULT 1,
  `sort_order`    TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `created_at`    TIMESTAMP        NULL DEFAULT NULL,
  `updated_at`    TIMESTAMP        NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Testimoni pelanggan';

INSERT INTO `testimonials` (`customer_name`, `avatar`, `review`, `rating`, `source`, `is_featured`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
  ('Budi Santoso',    NULL, 'Nasi kucingnya enak banget, harganya juga sangat terjangkau! Jadi langganan tiap malem.',                          5, 'Google',    1, 1, 1, NOW(), NOW()),
  ('Sari Dewi',       NULL, 'Wedang jahenya mantap, bikin badan hangat. Suasana angkringannya juga nyaman, cocok buat nongkrong bareng temen.', 5, 'Instagram', 1, 1, 2, NOW(), NOW()),
  ('Reza Firmansyah', NULL, 'Gorengannya crispy dan gak berminyak. Sate ususnya juara! Harga murah meriah, puas banget.',                       5, 'Google',    1, 1, 3, NOW(), NOW()),
  ('Lina Puspita',    NULL, 'Teh poci-nya authentic banget, serasa di Jogja. Tempatnya bersih dan pelayanannya ramah.',                         5, 'Google',    1, 1, 4, NOW(), NOW()),
  ('Doni Prasetyo',   NULL, 'Angkringan favorit sekeluarga! Anak-anak suka gorengannya, orang tua suka wedangnya. Recommended!',                5, 'Instagram', 1, 1, 5, NOW(), NOW());

-- Tabel: site_settings
CREATE TABLE `site_settings` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `key`        VARCHAR(100)    NOT NULL,
  `value`      TEXT            NULL DEFAULT NULL,
  `type`       VARCHAR(20)     NOT NULL DEFAULT 'text',
  `label`      VARCHAR(150)    NULL DEFAULT NULL,
  `created_at` TIMESTAMP       NULL DEFAULT NULL,
  `updated_at` TIMESTAMP       NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
  COMMENT='Pengaturan umum website';

INSERT INTO `site_settings` (`key`, `value`, `type`, `label`, `created_at`, `updated_at`) VALUES
  ('site_name',        'Angkringan Sekota',                                                 'text',  'Nama Website',      NOW(), NOW()),
  ('site_tagline',     'Cita Rasa Kota, Harga Angkringan',                                  'text',  'Tagline',           NOW(), NOW()),
  ('site_description', 'Angkringan Sekota hadir membawa cita rasa autentik angkringan Jawa di tengah kota.', 'text', 'Deskripsi', NOW(), NOW()),
  ('address',          'Jl. Raya Sekota No. 88, Kota',                                      'text',  'Alamat',            NOW(), NOW()),
  ('phone',            '+62 812-3456-7890',                                                  'text',  'Nomor Telepon',     NOW(), NOW()),
  ('whatsapp',         '6281234567890',                                                      'text',  'Nomor WhatsApp',    NOW(), NOW()),
  ('email',            'halo@angkringansekota.id',                                           'text',  'Email',             NOW(), NOW()),
  ('instagram',        '@angkringansekota',                                                  'text',  'Instagram',         NOW(), NOW()),
  ('facebook',         'Angkringan Sekota',                                                  'text',  'Facebook',          NOW(), NOW()),
  ('tiktok',           '@angkringansekota',                                                  'text',  'TikTok',            NOW(), NOW()),
  ('maps_link',        'https://maps.google.com',                                            'text',  'Link Google Maps',  NOW(), NOW()),
  ('hero_title',       'Selamat Datang di Angkringan Sekota',                               'text',  'Judul Hero',        NOW(), NOW()),
  ('hero_subtitle',    'Menikmati malam dengan cita rasa nusantara yang autentik, harga bersahabat, dan suasana yang hangat.', 'text', 'Subjudul Hero', NOW(), NOW()),
  ('about_title',      'Cerita di Balik Angkringan Kami',                                   'text',  'Judul About',       NOW(), NOW()),
  ('about_description','Angkringan Sekota berdiri sejak 2015, berawal dari tekad sederhana untuk menghadirkan cita rasa angkringan Jogja yang autentik di tengah kota.', 'text', 'Deskripsi About', NOW(), NOW()),
  ('hero_image',       'images/hero_bg.jpg',                                                'image', 'Gambar Hero',       NOW(), NOW()),
  ('about_image',      'images/about_photo.jpg',                                            'image', 'Gambar About',      NOW(), NOW());


-- ============================================================
--  BAGIAN 3: USER ADMIN DEFAULT
--  Password: admin123
--  (Ganti password setelah pertama kali login!)
-- ============================================================
INSERT INTO `users` (`name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
  ('Admin Sekota', 'admin@angkringansekota.id', NOW(), '$2y$12$TBlqJKBH9v7q.RiRNE0Zz.YD/Nwz0VVXatVOzSXfE1g9EMzfVaoe', NULL, NOW(), NOW());


-- ============================================================
-- Selesai - Siap import ke phpMyAdmin
-- ============================================================
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
