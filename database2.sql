CREATE TABLE categories(
ct_id INT AUTO_INCREMENT PRIMARY KEY,
ct_name VARCHAR(100) NOT NULL UNIQUE,
ct_description TEXT,
ct_status ENUM('')
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



CREATE TABLE orders(
 id INT AUTO_INCREMENT PRIMARY KEY,
 customer_id INT NOT NULL,
 order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 total_amount DECIMAL(10,2) NOT NULL,
 payment_method ENUM('Cash','UPI','Card')DEFAULT
 'Pending',
 order_status ENUM('Pending','Processing','Completed','Cancelled')DEFAULT'Pending'

 FOREIGN KEY (customer_id) REFERENCES 
customers(id)

);



CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 product_name VARCHAR(100)NOT NULL,
 category_id INT,
 price DECIMAL(10,2) NOT NULL,
 quantity INT NOT NULL DEFAULT 0,
 description TEXT,
 availibility ENUM('Available','Unavailable') DEFAULT
 'Available',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

 FOREIGN KEY (category_id) REFERENCES categories(id)

);







)