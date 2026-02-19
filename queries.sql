CREATE TABLE `users` (
    `id` int NOT NULL AUTO_INCREMENT,
    `name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `role` enum('admin','manager','user') COLLATE utf8mb4_general_ci NOT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
);

CREATE TABLE `requests` (
    `id` int NOT NULL AUTO_INCREMENT,
    `user_id` int DEFAULT NULL,
    `title` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `description` text COLLATE utf8mb4_general_ci,
    `category` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `priority` enum('Low','Medium','High') COLLATE utf8mb4_general_ci DEFAULT NULL,
    `status` enum('Submitted','Approved','Rejected','Needs Clarification','Closed','Reopened','No Action') COLLATE utf8mb4_general_ci DEFAULT 'Submitted',
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
);

CREATE TABLE `request_logs` (
    `id` int NOT NULL AUTO_INCREMENT,
    `request_id` int DEFAULT NULL,
    `old_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `new_status` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `changed_by` int DEFAULT NULL,
    `role` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
    `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `request_id` (`request_id`),
    KEY `changed_by` (`changed_by`)
);

CREATE TABLE `status_transitions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `current_status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `next_status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `allowed_role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
);

INSERT INTO status_transitions (current_status, next_status, allowed_role) VALUES
('Submitted','Approved','manager'),
('Submitted','Rejected','manager'),
('Submitted','Needs Clarification','manager'),

('Approved','Closed','admin'),
('Closed','Reopened','admin'),

('Needs Clarification','Submitted','user');