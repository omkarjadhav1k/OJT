CREATE TABLE users(
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(100) NOT NULL,
    role ENUM('Admin','Customer')
    DEFAULT 'Customer'
);

CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    customer_phone VARCHAR(15) NOT NULL,
    customer_address TEXT
    
);
CREATE TABLE sales(
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2)NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    sale_date TIMESTAMP DEFAULT
    CURRENT_TIMESTAMP,
    payment_status ENUM('Paid','Pending')
    DEFAULT 'Pending',
    FOREIGN KEY (customer_id) REFERENCES 
    customer(customer_id),
    FOREIGN KEY (product_id) REFERENCES
    product(product_id),
);