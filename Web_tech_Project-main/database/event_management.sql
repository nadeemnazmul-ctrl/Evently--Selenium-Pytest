CREATE DATABASE IF NOT EXISTS event_management;

USE event_management;

CREATE TABLE users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Organizer', 'Attendee') NOT NULL DEFAULT 'Attendee',
    gender VARCHAR(20),
    photo VARCHAR(255),
    is_verified TINYINT(1) DEFAULT 1,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) UNIQUE NOT NULL,
    description VARCHAR(255)
);

CREATE TABLE events(
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizer_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    location VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    ticket_type VARCHAR(50) DEFAULT 'General',
    ticket_price DECIMAL(10, 2) DEFAULT 0,
    total_seats INT NOT NULL,
    available_seats INT NOT NULL,
    status ENUM('Upcoming', 'Completed', 'Cancelled') DEFAULT 'Upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(organizer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY(category_id) REFERENCES categories(id)
);

CREATE TABLE bookings(
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    attendee_id INT NOT NULL,
    ticket_quantity INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    booking_code VARCHAR(40) UNIQUE NOT NULL,
    status ENUM('Pending', 'Confirmed', 'Cancelled') DEFAULT 'Confirmed',
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE,
    FOREIGN KEY(attendee_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE payments(
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    method VARCHAR(50) DEFAULT 'Demo Payment',
    transaction_id VARCHAR(80) UNIQUE NOT NULL,
    status ENUM('Success', 'Failed') DEFAULT 'Success',
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(booking_id) REFERENCES bookings(id) ON DELETE CASCADE
);

CREATE TABLE schedules(
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    schedule_time TIME NOT NULL,
    description VARCHAR(255),
    FOREIGN KEY(event_id) REFERENCES events(id) ON DELETE CASCADE
);

CREATE TABLE notifications(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    message VARCHAR(500) NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO
    categories(name, description)
VALUES
('Technology', 'Technology events'),
('Music', 'Music events'),
('Business', 'Business events'),
('Sports', 'Sports events'),
('Workshop', 'Learning workshops');

-- Password for these demo users is 123456. login.php has a demo-account fallback.
INSERT INTO
    users(name, email, password, role, gender)
VALUES
(
        'System Admin',
        'admin@evently.com',
        'demo',
        'Admin',
        'Other'
    ),
(
        'Demo Organizer',
        'organizer@evently.com',
        'demo',
        'Organizer',
        'Other'
    ),
(
        'Demo Attendee',
        'attendee@evently.com',
        'demo',
        'Attendee',
        'Other'
    );

INSERT INTO
    events(
        organizer_id,
        category_id,
        name,
        event_date,
        event_time,
        location,
        description,
        ticket_type,
        ticket_price,
        total_seats,
        available_seats
    )
VALUES
(
        2,
        1,
        'Future Tech Summit 2026',
        '2026-10-05',
        '10:00:00',
        'BICC Dhaka',
        'Technology conference about AI, software and digital innovation.',
        'General',
        850,
        100,
        100
    ),
(
        2,
        2,
        'City Lights Music Fest',
        '2026-09-28',
        '18:30:00',
        'Dhaka Arena',
        'Live music and entertainment event.',
        'General',
        1200,
        150,
        150
    ),
(
        2,
        3,
        'Dhaka Business Connect',
        '2026-10-12',
        '11:00:00',
        'Radisson Blu Dhaka',
        'Networking for entrepreneurs and professionals.',
        'General',
        650,
        80,
        80
    );