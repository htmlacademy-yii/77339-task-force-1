CREATE DATABASE task_force_natalia
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE task_force_natalia;

CREATE TABLE cities (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(255) NOT NULL UNIQUE,
                      latitude DECIMAL(10, 8),
                      longitude DECIMAL(11, 8)
);

CREATE TABLE categories (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          name VARCHAR(255) NOT NULL UNIQUE,
                          icon VARCHAR(50) NULL
);

CREATE TABLE users (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      name VARCHAR(255) NOT NULL,
                      email VARCHAR(255) NOT NULL UNIQUE,
                      password_hash VARCHAR(255) NOT NULL,
                      role ENUM('customer', 'executor') NOT NULL,
                      city_id INT,
                      avatar VARCHAR(255),
                      telegram VARCHAR(255),
                      phone VARCHAR(20),
                      show_contacts BOOLEAN DEFAULT TRUE,
                      birthday DATE,
                      info TEXT,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (city_id) REFERENCES cities(id)
);

CREATE TABLE user_specializations (
                                    user_id INT NOT NULL,
                                    category_id INT NOT NULL,
                                    PRIMARY KEY (user_id, category_id),
                                    FOREIGN KEY (user_id) REFERENCES users(id),
                                    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE tasks (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      title VARCHAR(255) NOT NULL,
                      description TEXT NOT NULL,
                      category_id INT NOT NULL,
                      budget FLOAT,
                      status ENUM('new', 'in_progress', 'completed', 'failed', 'canceled') NOT NULL DEFAULT 'new',
                      city_id INT,
                      latitude DECIMAL(10, 8),
                      longitude DECIMAL(11, 8),
                      ended_at DATE,
                      customer_id INT NOT NULL,
                      executor_id INT,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (category_id) REFERENCES categories(id),
                      FOREIGN KEY (city_id) REFERENCES cities(id),
                      FOREIGN KEY (customer_id) REFERENCES users(id),
                      FOREIGN KEY (executor_id) REFERENCES users(id)
);

CREATE TABLE responses (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        task_id INT NOT NULL,
                        executor_id INT NOT NULL,
                        price INT,
                        comment TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (task_id) REFERENCES tasks(id),
                        FOREIGN KEY (executor_id) REFERENCES users(id)
);

CREATE TABLE reviews (
                      id INT AUTO_INCREMENT PRIMARY KEY,
                      task_id INT NOT NULL,
                      customer_id INT NOT NULL,
                      executor_id INT NOT NULL,
                      rating TINYINT NOT NULL CHECK (rating >= 1 AND rating <= 5),
                      comment TEXT,
                      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (task_id) REFERENCES tasks(id),
                      FOREIGN KEY (customer_id) REFERENCES users(id),
                      FOREIGN KEY (executor_id) REFERENCES users(id)
);

CREATE TABLE files (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    task_id INT NOT NULL,
                    path VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (task_id) REFERENCES tasks(id)
);
