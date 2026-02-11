<?php

namespace Models;

use Config\Database;
use PDO;

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT id, username, password_hash FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password_hash'])) {
                return $row;
            }
        }
        return false;
    }

    public function register($username, $password) {
        // Simple registration for setup (single user)
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password_hash) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hash);
        
        try {
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function getProfile($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM user_profile WHERE user_id = :id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($user_id, $data) {
        // Check if profile exists
        $current = $this->getProfile($user_id);
        
        if ($current) {
            $sql = "UPDATE user_profile SET 
                    cancer_stage = :stage,
                    tnm_stage = :tnm,
                    biomarkers = :bio,
                    regimen_name = :regimen,
                    has_stoma = :stoma,
                    surgery_date = :surgery
                    WHERE user_id = :id";
        } else {
            $sql = "INSERT INTO user_profile (cancer_stage, tnm_stage, biomarkers, regimen_name, has_stoma, surgery_date, user_id) 
                    VALUES (:stage, :tnm, :bio, :regimen, :stoma, :surgery, :id)";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':stage', $data['cancer_stage']);
        $stmt->bindParam(':tnm', $data['tnm_stage']);
        $stmt->bindParam(':bio', $data['biomarkers']);
        $stmt->bindParam(':regimen', $data['regimen_name']);
        $stmt->bindParam(':stoma', $data['has_stoma']);
        $stmt->bindParam(':surgery', $data['surgery_date']);
        $stmt->bindParam(':id', $user_id);
        
        return $stmt->execute();
    }
}
