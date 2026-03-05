CREATE DATABASE IF NOT EXISTS dbhw7 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dbhw7;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    gender VARCHAR(20),
    age INT,
    province VARCHAR(100),
    email VARCHAR(255) UNIQUE,
    role VARCHAR(50)
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    detail TEXT,
    price DECIMAL(10, 2) NOT NULL,
    remain INT DEFAULT 0,
    img_files VARCHAR(255),
    category_id INT,
    CONSTRAINT fk_product_category FOREIGN KEY (category_id) 
        REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    payment VARCHAR(100),
    pay_status VARCHAR(50),
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    bank_transfer VARCHAR(100),
    date_transfer DATE,
    time_transfer TIME,
    delivery VARCHAR(100),
    CONSTRAINT fk_order_user FOREIGN KEY (user_id) 
        REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE order_details (
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    PRIMARY KEY (order_id, product_id),
    CONSTRAINT fk_details_order FOREIGN KEY (order_id) 
        REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_details_product FOREIGN KEY (product_id) 
        REFERENCES products(id) ON DELETE CASCADE
);


INSERT INTO users (username, password, first_name, last_name, gender, age, province, email, role) 
VALUES ('admin', '1234', 'Admin', 'System', 'Male', 30, 'กระบี่', 'admin@system.com', 'admin');