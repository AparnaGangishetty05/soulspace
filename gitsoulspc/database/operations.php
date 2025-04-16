<?php
require_once 'config.php';

// User Operations
class UserOperations {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createUser($email, $password, $firstName, $lastName, $phone) {
        $hash = hash_password($password);
        $sql = "INSERT INTO users (email, password_hash, first_name, last_name, phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$email, $hash, $firstName, $lastName, $phone]);
    }

    public function getUserById($userId) {
        $sql = "SELECT user_id, email, first_name, last_name, phone FROM users WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Appointment Operations
class AppointmentOperations {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createAppointment($userId, $therapistId, $packageId, $date, $time) {
        $sql = "INSERT INTO appointments (user_id, therapist_id, package_id, appointment_date, start_time) 
               VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $therapistId, $packageId, $date, $time]);
    }

    public function getAppointmentsByUser($userId) {
        $sql = "SELECT a.*, t.first_name as therapist_first_name, t.last_name as therapist_last_name, 
               p.name as package_name, p.duration_minutes 
               FROM appointments a 
               JOIN therapists t ON a.therapist_id = t.therapist_id 
               JOIN session_packages p ON a.package_id = p.package_id 
               WHERE a.user_id = ? 
               ORDER BY a.appointment_date, a.start_time";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateAppointmentStatus($appointmentId, $status) {
        $sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $appointmentId]);
    }
}

// Therapist Operations
class TherapistOperations {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAvailableTherapists($date) {
        $sql = "SELECT t.*, ta.start_time, ta.end_time 
               FROM therapists t 
               JOIN therapist_availability ta ON t.therapist_id = ta.therapist_id 
               WHERE ta.day_of_week = DAYNAME(?) 
               AND ta.is_available = true";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTherapistById($therapistId) {
        $sql = "SELECT * FROM therapists WHERE therapist_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$therapistId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Session Package Operations
class PackageOperations {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getActivePackages() {
        $sql = "SELECT * FROM session_packages WHERE is_active = true";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Payment Operations
class PaymentOperations {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function createPayment($appointmentId, $amount, $paymentMethod) {
        $sql = "INSERT INTO payments (appointment_id, amount, payment_status, payment_method) 
               VALUES (?, ?, 'pending', ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$appointmentId, $amount, $paymentMethod]);
    }

    public function updatePaymentStatus($paymentId, $status, $transactionId = null) {
        $sql = "UPDATE payments SET payment_status = ?, transaction_id = ? WHERE payment_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $transactionId, $paymentId]);
    }
}