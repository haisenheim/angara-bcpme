CREATE TABLE `producteurs` (
 `id` bigint unsigned NOT NULL AUTO_INCREMENT,
 `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 `male` tinyint(1) NOT NULL DEFAULT '1',
 `tenant_id` int NOT NULL DEFAULT '0',
 `active` tinyint(1) NOT NULL DEFAULT '1',
 `region_id` int NOT NULL DEFAULT '0',
 `departement_id` int NOT NULL DEFAULT '0',
 `arrondissement_id` int NOT NULL DEFAULT '0',
 `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`id`)
)
