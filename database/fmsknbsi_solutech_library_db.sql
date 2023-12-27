-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 27, 2023 at 01:54 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `solutech_library_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `isbn` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `subcategory_id` bigint(20) UNSIGNED NOT NULL,
  `description` text DEFAULT NULL,
  `page` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'AVAILABLE',
  `image` varchar(255) DEFAULT NULL,
  `added_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `name`, `publisher`, `isbn`, `category_id`, `subcategory_id`, `description`, `page`, `status`, `image`, `added_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4174815, 'All about Allexander', 'Dan', '12336', 802314, 236767, 'Dan talks all bout Allexander in ancient Egypt ...', 45, 'AVAILABLE', 'IMAGES/BOOKS/4174815', 6198781, '2023-12-27 09:26:22', '2023-12-27 09:26:22', NULL),
(4938844, 'Ai and software developmet', 'Jack', '67423', 217876, 788472, 'Artificial intelligence is a nowdays taking the top rang of .....', 25, 'AVAILABLE', 'IMAGES/BOOKS/4938844', 6198781, '2023-12-27 09:36:53', '2023-12-27 09:36:53', NULL),
(5154120, 'All about Cns Plateforme', 'Ezechiel', '23412', 290391, 261215, 'The cns plateforme leading by a team of 6 dedicated young', 16, 'AVAILABLE', 'IMAGES/BOOKS/5154120', 6198781, '2023-12-27 09:33:40', '2023-12-27 09:33:40', NULL),
(6865223, 'Call of duties', 'Brown', '12345', 290391, 261215, 'Call of duties is a book rwitten by Brwon, In this book he illustrates', 45, 'AVAILABLE', 'IMAGES/BOOKS/6865223', 6198781, '2023-12-27 09:24:01', '2023-12-27 09:24:01', NULL),
(7851887, 'Human and Gyms', 'Phil', '12423', 870890, 731498, 'Phil, the pricipal actor and writer of this history ...', 150, 'AVAILABLE', 'IMAGES/BOOKS/7851887', 6198781, '2023-12-27 09:29:50', '2023-12-27 09:29:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `book_loans`
--

CREATE TABLE `book_loans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `book_id` bigint(20) UNSIGNED NOT NULL,
  `loan_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `extended` tinyint(1) NOT NULL DEFAULT 0,
  `extension_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `penalty_amount` decimal(8,2) DEFAULT 0.00,
  `penalty_status` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'PENDING',
  `added_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `book_loans`
--

INSERT INTO `book_loans` (`id`, `user_id`, `book_id`, `loan_date`, `return_date`, `extended`, `extension_date`, `due_date`, `penalty_amount`, `penalty_status`, `status`, `added_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(6198792, 821598, 4938844, '2023-12-27', NULL, 0, NULL, '2024-02-15', NULL, 'NOT_CHARGED', 'APPROVED', 6198781, '2023-12-27 09:38:24', '2023-12-27 10:02:39', NULL),
(6198794, 821598, 7851887, '2023-12-27', NULL, 0, NULL, '2024-01-11', 2.00, 'CHARGED', 'REJECTED', 6198781, '2023-12-27 09:49:57', '2023-12-27 10:02:21', NULL),
(6198795, 821598, 6865223, '2023-12-27', NULL, 0, NULL, '2024-01-17', 1.00, 'CHARGED', 'PENDING', 821598, '2023-12-27 09:50:15', '2023-12-27 09:50:15', NULL),
(6198796, 821598, 5154120, '2023-12-27', NULL, 0, NULL, '2024-02-15', NULL, 'NOT_CHARGED', 'REJECTED', 6198781, '2023-12-27 09:50:52', '2023-12-27 10:02:33', NULL),
(6198797, 821598, 4174815, '2023-12-27', NULL, 0, NULL, '2024-02-06', NULL, 'NOT_CHARGED', 'REJECTED', 6198781, '2023-12-27 09:51:07', '2023-12-27 10:02:25', NULL),
(6198798, 259804, 4938844, '2023-12-06', NULL, 0, NULL, '2023-12-08', NULL, 'NOT_CHARGED', 'APPROVED', 6198781, '2023-12-27 09:52:50', '2023-12-27 10:02:12', NULL),
(6198799, 259804, 5154120, '2023-12-20', NULL, 0, NULL, '2023-12-23', 3.00, 'CHARGED', 'APPROVED', 6198781, '2023-12-27 09:53:07', '2023-12-27 10:02:09', NULL),
(6198800, 259804, 7851887, '2023-12-20', NULL, 0, NULL, '2023-12-29', 5.00, 'CHARGED', 'APPROVED', 6198781, '2023-12-27 09:53:32', '2023-12-27 10:02:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(217876, 'Computer Science', '2023-12-22 09:40:57', '2023-12-22 09:40:57'),
(290391, 'Novels', '2023-12-27 08:57:11', '2023-12-27 08:57:11'),
(802314, 'Histories', '2023-12-27 08:57:55', '2023-12-27 08:57:55'),
(870890, 'Entertainments', '2023-12-22 06:12:29', '2023-12-22 06:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2023_01_16_105002_create_user_tokens_table', 1),
(5, '2023_12_09_130837_create_user_table', 1),
(6, '2023_12_16_082921_create_roles_table', 1),
(7, '2023_12_16_114738_create_books_table', 2),
(8, '2023_12_16_131325_create_categories_table', 2),
(9, '2023_12_16_131339_create_subcategories_table', 2),
(10, '2023_12_16_190327_create_book_loans_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(127, 'App\\Models\\User', 6198781, 'key', '333a124e406f6a14fd891faf6bac0d7ff02521cd9ba81ecca5ad8464694e6fd9', '[\"*\"]', '2023-12-27 10:10:53', NULL, '2023-12-27 09:58:07', '2023-12-27 10:10:53');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'ACTIVE', NULL, NULL),
(2, 'User', 'ACTIVE', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `category_id`, `created_at`, `updated_at`) VALUES
(236767, 'Ancient Egypt', 802314, '2023-12-27 08:59:13', '2023-12-27 08:59:13'),
(261215, 'The lengends', 290391, '2023-12-27 09:00:00', '2023-12-27 09:00:00'),
(731498, 'Sports', 870890, '2023-12-27 08:58:12', '2023-12-27 08:58:12'),
(788472, 'High tech', 217876, '2023-12-27 09:34:20', '2023-12-27 09:34:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `profile` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'ACTIVE',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `profile`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(259804, 'Jacob', 'jacob@gmail.com', NULL, '$2y$10$ziEVyqzfATpDwHGIxr0jbu06Kp3WhbHglba3jFYl.tWs542MuhUn2', 2, 'IMAGES/PROFILE/259804', 'ACTIVE', NULL, '2023-12-27 09:09:52', '2023-12-27 09:09:52', NULL),
(821598, 'Barack', 'barack@gmail.com', NULL, '$2y$10$iwEKeGFWPxjbilx/C1D1sugWrT7Ycpp6Gxuby6hZlxbTS.Gi/W4aG', 2, 'IMAGES/PROFILE/821598', 'ACTIVE', NULL, '2023-12-27 09:15:55', '2023-12-27 09:15:55', NULL),
(6198781, 'Phil Tsongo', 'philippetsongo90@gmail.com', NULL, '$2y$10$LchPppMlk0TSUb3ac6HBmOW4pkQgV7SyD/keDy77V5tVyVbjtdZtu', 1, 'IMAGES/PROFILE/813236', 'ACTIVE', NULL, '2023-12-16 08:17:05', '2023-12-20 14:58:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `created_at`, `updated_at`) VALUES
(114, 6198781, '114|tm2SL0mXIMOGuNln4VU3LpwWXazQdP40ZAo8lvfI195507e3', '2023-12-27 07:36:51', '2023-12-27 07:36:51'),
(115, 6198781, '115|1X45sSlHJLkhgz5G77oYTJP1dMcI6DWuKTNoo8MQ24cc9e16', '2023-12-27 07:44:28', '2023-12-27 07:44:28'),
(116, 6198781, '116|WniIUlfJYVgnYCkSCJWTWAdjf1xRaLak8Y3vhyXscb7483f8', '2023-12-27 07:49:23', '2023-12-27 07:49:23'),
(117, 6198781, '117|sdgnyRbMsU4JzuYqZRSSogfszh7fqfmojhxAovHy63f360fc', '2023-12-27 08:11:09', '2023-12-27 08:11:09'),
(118, 6198781, '118|1pzTtMEd1lYU3B4DUsEjd1CY5uNQH0Kv6UQl1chY64bedd1c', '2023-12-27 08:13:41', '2023-12-27 08:13:41'),
(119, 6198781, '119|j4ScpgZV8RimCMoYRTgqzmlrmUE1JXaxnEqgyO6F06095d9d', '2023-12-27 08:16:26', '2023-12-27 08:16:26'),
(121, 6198781, '121|WO0YPgMFK0lOjQr1BkUNXD2r2ZvH1gfsX8S8rhIq6a58a5b0', '2023-12-27 08:36:13', '2023-12-27 08:36:13'),
(122, 6198781, '122|OvA9eqhP1JDx9UohaIb9mNC0EtW0q6UE2h4MD3C4d424ec7e', '2023-12-27 08:56:05', '2023-12-27 08:56:05'),
(125, 6198781, '125|UkUHAL1IyXiV8INsJsVe9X2bBfVGkDUGPS05XGWO36a8a697', '2023-12-27 09:54:52', '2023-12-27 09:54:52'),
(127, 6198781, '127|X5Dk1JDE1Oahnf5qu68MaAmubmlHEHYurRubUaLMdd1c72ea', '2023-12-27 09:58:07', '2023-12-27 09:58:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `books_isbn_unique` (`isbn`);

--
-- Indexes for table `book_loans`
--
ALTER TABLE `book_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_loans_added_by_foreign` (`added_by`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7928704;

--
-- AUTO_INCREMENT for table `book_loans`
--
ALTER TABLE `book_loans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6198801;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6259383403087565244;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5809712;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4203937150726198785;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `book_loans`
--
ALTER TABLE `book_loans`
  ADD CONSTRAINT `book_loans_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
