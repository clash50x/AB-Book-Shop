-- AB Book Shop - MySQL Schema
-- Compatible with InfinityFree / phpMyAdmin
SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  logo VARCHAR(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(500) DEFAULT NULL,
  category_id INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS addresses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  full_address VARCHAR(500) NOT NULL,
  city VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  address_id INT NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  status ENUM('Pending','Confirmed','Shipped','Delivered') DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (address_id) REFERENCES addresses(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS=1;

-- Seed admin (password: Haseeb9898)
INSERT INTO users (name, email, password, role) VALUES
('Haseeb', 'haseebrana5357@gmail.com', '$2y$12$I7RPXLpufWlbBVKAxHPXfOFWGX6zDdiGhw7CquTdTDwe5nGtazRtS', 'admin');

-- Seed categories
INSERT INTO categories (name, logo) VALUES
('Novels','https://cdn-icons-png.flaticon.com/512/29/29302.png'),
('Academic','https://cdn-icons-png.flaticon.com/512/2232/2232688.png'),
('Entry Test','https://cdn-icons-png.flaticon.com/512/3976/3976625.png'),
('Islamic','https://cdn-icons-png.flaticon.com/512/3079/3079165.png'),
('Programming','https://cdn-icons-png.flaticon.com/512/1077/1077012.png');

-- Sample products
INSERT INTO products (title, description, price, image, category_id) VALUES
('The Great Gatsby','A classic American novel by F. Scott Fitzgerald.',1200,'https://covers.openlibrary.org/b/id/7222246-L.jpg',1),
('Atomic Habits','Tiny Changes, Remarkable Results by James Clear.',1800,'https://covers.openlibrary.org/b/id/10523338-L.jpg',1),
('Physics for FSc','Comprehensive Physics textbook for FSc students.',950,'https://covers.openlibrary.org/b/id/8231856-L.jpg',2),
('MDCAT Guide','Complete preparation guide for MDCAT entry test.',1500,'https://covers.openlibrary.org/b/id/12000000-L.jpg',3),
('Riyad us Saliheen','Authentic collection of hadiths.',1100,'https://covers.openlibrary.org/b/id/8775117-L.jpg',4),
('Clean Code','A Handbook of Agile Software Craftsmanship by Robert C. Martin.',2500,'https://covers.openlibrary.org/b/id/8101019-L.jpg',5),
('Eloquent JavaScript','A modern introduction to programming.',2200,'https://covers.openlibrary.org/b/id/9261564-L.jpg',5);
