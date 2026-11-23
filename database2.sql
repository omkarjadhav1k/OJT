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



