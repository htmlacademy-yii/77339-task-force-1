CREATE DATABASE task_force_anton
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE task_force_anton;

CREATE TABLE `categories` (
  `id` INTEGER AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL UNIQUE,
  `symbol_code` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY(`id`)
);

CREATE TABLE `cities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('customer', 'executor') NOT NULL,
  `city_id` INT,
  `avatar` VARCHAR(255),
  `date_create` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (city_id) REFERENCES cities(id)
);

CREATE TABLE `specializations` (
  `id` INT AUTO_INCTEMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `category_id` INT NOT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE `tasks` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category_id` INT NOT NULL,
  `budget` FLOAT,
  `status` ENUM('new', 'canceled', 'in_progress', 'completed', 'failed') NOT NULL DEFAULT 'new',
  `city_id` INT,
  `latitude` DECIMAL(10, 7),
  `longitude` DECIMAL(10, 7),
  `date_end` DATE,
  `customer_id` INT NOT NULL,
  `executor_id` INT,
  `date_create` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (city_id) REFERENCES cities(id),
  FOREIGN KEY (customer_id) REFERENCES users(id),
  FOREIGN KEY (executor_id) REFERENCES users(id)
);

CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `task_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `executor_id` INT NOT NULL,
  `rating` TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
  `info` TEXT,
  `date_create` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (task_id) REFERENCES tasks(id),
  FOREIGN KEY (customer_id) REFERENCES users(id),
  FOREIGN KEY (executor_id) REFERENCES users(id)
);
CREATE TABLE `responses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `task_id` INT NOT NULL,
  `executor_id` INT NOT NULL,
  `price` INT,
  `comment` TEXT,
  `date_create` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (task_id) REFERENCES tasks(id),
  FOREIGN KEY (executor_id) REFERENCES users(id)
);

CREATE TABLE `files` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `task_id` INT NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (task_id) REFERENCES tasks(id)
);

ALTER TABLE `tasks`
ADD FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
ON UPDATE NO ACTION ON DELETE CASCADE;

ALTER TABLE `tasks`
ADD FOREIGN KEY(`category_id`) REFERENCES `category`(`id`)
ON UPDATE NO ACTION ON DELETE CASCADE;
