CREATE TABLE categories(
category_id INT AUTO_INCREMENT PRIMARY KEY,
category_name VARCHAR(100) NOT NULL UNIQUE,
ct_description TEXT,
ct_status ENUM('')
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);