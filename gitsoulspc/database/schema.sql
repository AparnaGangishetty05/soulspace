-- Database Schema for SoulSpace Mental Wellness Platform

-- Users Table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Therapists Table
CREATE TABLE therapists (
    therapist_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    specialization TEXT,
    bio TEXT,
    photo_url VARCHAR(255),
    rating DECIMAL(3,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Session Packages Table
CREATE TABLE session_packages (
    package_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    duration_minutes INT NOT NULL,
    number_of_sessions INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    is_active BOOLEAN DEFAULT true
);

-- Appointments Table
CREATE TABLE appointments (
    appointment_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    therapist_id INT NOT NULL,
    package_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    start_time TIME NOT NULL,
    status ENUM('scheduled', 'completed', 'cancelled', 'rescheduled') NOT NULL DEFAULT 'scheduled',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (therapist_id) REFERENCES therapists(therapist_id),
    FOREIGN KEY (package_id) REFERENCES session_packages(package_id)
);

-- Therapist Availability Table
CREATE TABLE therapist_availability (
    availability_id INT PRIMARY KEY AUTO_INCREMENT,
    therapist_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    is_available BOOLEAN DEFAULT true,
    FOREIGN KEY (therapist_id) REFERENCES therapists(therapist_id)
);

-- Payment Transactions Table
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL,
    payment_method VARCHAR(50),
    transaction_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id)
);

-- User Progress Table
CREATE TABLE user_progress (
    progress_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    mood_rating INT CHECK (mood_rating BETWEEN 1 AND 5),
    notes TEXT,
    recorded_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);