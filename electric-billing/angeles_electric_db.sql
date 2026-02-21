CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_number VARCHAR(20) UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    dob DATE,
    address VARCHAR(255),
    contact_no VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    month VARCHAR(50),
    amount DECIMAL(10,2),
    due_date DATE,
    status ENUM('Paid','Unpaid') DEFAULT 'Unpaid',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    bill_id INT,
    amount DECIMAL(10,2),
    payment_method VARCHAR(50),
    reference_number VARCHAR(50),
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (bill_id) REFERENCES bills(id)
) ENGINE=InnoDB;

CREATE TABLE advisories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    category VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO admins (admin_id, password)
VALUES ('1001', 'admin_123');

INSERT INTO advisories (title, category, description) VALUES
('Billing Advisory', 'Billing Advisories', 'Updates and notices related to your electricity bill.'),
('Maintenance Schedule', 'Maintenance Schedule', 'Upcoming scheduled maintenance activities.'),
('Power Interruption Notice', 'Power Interruption Notice', 'Announcements about scheduled power outages.');
