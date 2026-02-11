<?php

namespace Models;

use Config\Database;
use PDO;

class LogModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function saveDailyLog($data) {
        // Check if log exists for date
        $stmt = $this->conn->prepare("SELECT id FROM daily_logs WHERE user_id = :uid AND log_date = :date");
        $stmt->bindParam(':uid', $data['user_id']);
        $stmt->bindParam(':date', $data['log_date']);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Update
            $sql = "UPDATE daily_logs SET 
                    weight = :weight, bp_systolic = :bps, bp_diastolic = :bpd, pulse = :pulse, temperature = :temp,
                    nausea_score = :nausea, vomit_count = :vomit, diarrhea_count = :diarrhea, is_constipated = :constip,
                    stool_type = :stool, has_blood_stool = :blood, pain_score = :pain, bloating_score = :bloat,
                    numbness_score = :numbness, fatigue_score = :fatigue, appetite_score = :appetite,
                    mouth_sore_score = :mouth, sleep_score = :sleep, mood_score = :mood, notes = :notes
                    WHERE user_id = :uid AND log_date = :date";
        } else {
            // Insert
            $sql = "INSERT INTO daily_logs (
                    user_id, log_date, weight, bp_systolic, bp_diastolic, pulse, temperature,
                    nausea_score, vomit_count, diarrhea_count, is_constipated, stool_type, has_blood_stool,
                    pain_score, bloating_score, numbness_score, fatigue_score, appetite_score,
                    mouth_sore_score, sleep_score, mood_score, notes
                ) VALUES (
                    :uid, :date, :weight, :bps, :bpd, :pulse, :temp,
                    :nausea, :vomit, :diarrhea, :constip, :stool, :blood,
                    :pain, :bloat, :numbness, :fatigue, :appetite,
                    :mouth, :sleep, :mood, :notes
                )";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $data['user_id']);
        $stmt->bindParam(':date', $data['log_date']);
        $stmt->bindParam(':weight', $data['weight']);
        $stmt->bindParam(':bps', $data['bp_systolic']);
        $stmt->bindParam(':bpd', $data['bp_diastolic']);
        $stmt->bindParam(':pulse', $data['pulse']);
        $stmt->bindParam(':temp', $data['temperature']);
        $stmt->bindParam(':nausea', $data['nausea_score']);
        $stmt->bindParam(':vomit', $data['vomit_count']);
        $stmt->bindParam(':diarrhea', $data['diarrhea_count']);
        $stmt->bindParam(':constip', $data['is_constipated']);
        $stmt->bindParam(':stool', $data['stool_type']);
        $stmt->bindParam(':blood', $data['has_blood_stool']);
        $stmt->bindParam(':pain', $data['pain_score']);
        $stmt->bindParam(':bloat', $data['bloating_score']);
        $stmt->bindParam(':numbness', $data['numbness_score']);
        $stmt->bindParam(':fatigue', $data['fatigue_score']);
        $stmt->bindParam(':appetite', $data['appetite_score']);
        $stmt->bindParam(':mouth', $data['mouth_sore_score']);
        $stmt->bindParam(':sleep', $data['sleep_score']);
        $stmt->bindParam(':mood', $data['mood_score']);
        $stmt->bindParam(':notes', $data['notes']);
        
        return $stmt->execute();
    }

    public function saveDietLog($data) {
        $stmt = $this->conn->prepare("SELECT id FROM diet_logs WHERE user_id = :uid AND log_date = :date");
        $stmt->bindParam(':uid', $data['user_id']);
        $stmt->bindParam(':date', $data['log_date']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $sql = "UPDATE diet_logs SET 
                    water_intake = :water, meal_count = :meals, content_summary = :content, risk_foods = :risk
                    WHERE user_id = :uid AND log_date = :date";
        } else {
            $sql = "INSERT INTO diet_logs (user_id, log_date, water_intake, meal_count, content_summary, risk_foods) 
                    VALUES (:uid, :date, :water, :meals, :content, :risk)";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $data['user_id']);
        $stmt->bindParam(':date', $data['log_date']);
        $stmt->bindParam(':water', $data['water_intake']);
        $stmt->bindParam(':meals', $data['meal_count']);
        $stmt->bindParam(':content', $data['content_summary']);
        $stmt->bindParam(':risk', $data['risk_foods']);
        return $stmt->execute();
    }

    public function saveExerciseLog($data) {
        // Exercise logs might have multiple entries per day? 
        // Schema constraints might be separate. 
        // But prompt implies "One page form". 
        // My schema has no Unique key on exercise_logs(user_id, date), but usually distinct entries.
        // For simplicity in "One Page Form", we might just add a new entry or update "Today's total"?
        // Let's assume Add New for simplicity or just Update specific fields if we want 1 entry.
        // Prompt said "Minutes", "Intensity".
        // Let's just INSERT a new record every time for exercise or update if we treat it as "Daily Total".
        // Let's go with INSERT for now, but the "One Page Form" might imply editing "Today's Log". 
        // If editing, we need to know WHICH log.
        // To keep logic simple for "Daily Form", let's assume one main exercise entry per day or just append.
        // I'll implement INSERT.
        
        $sql = "INSERT INTO exercise_logs (user_id, log_date, exercise_type, duration_minutes, intensity, notes) 
                VALUES (:uid, :date, :type, :mins, :isd, :notes)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $data['user_id']);
        $stmt->bindParam(':date', $data['log_date']);
        $stmt->bindParam(':type', $data['exercise_type']);
        $stmt->bindParam(':mins', $data['duration_minutes']);
        $stmt->bindParam(':isd', $data['intensity']);
        $stmt->bindParam(':notes', $data['notes']);
        return $stmt->execute();
    }

    public function delete($user_id, $date) {
        try {
            $this->conn->beginTransaction();
            
            $stmt1 = $this->conn->prepare("DELETE FROM daily_logs WHERE user_id = :uid AND log_date = :date");
            $stmt1->execute([':uid' => $user_id, ':date' => $date]);

            $stmt2 = $this->conn->prepare("DELETE FROM diet_logs WHERE user_id = :uid AND log_date = :date");
            $stmt2->execute([':uid' => $user_id, ':date' => $date]);

            $stmt3 = $this->conn->prepare("DELETE FROM exercise_logs WHERE user_id = :uid AND log_date = :date");
            $stmt3->execute([':uid' => $user_id, ':date' => $date]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    public function getHistory($user_id, $days = 30) {
        // Updated default days to 30 for history list
        $date = date('Y-m-d', strtotime("-$days days"));
        $stmt = $this->conn->prepare("SELECT * FROM daily_logs WHERE user_id = :uid AND log_date >= :date ORDER BY log_date DESC"); // DESC for history list
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Helper to get data for a date
    public function getDailyData($user_id, $date) {
        $data = [];
        
        // Daily
        $stmt = $this->conn->prepare("SELECT * FROM daily_logs WHERE user_id = :uid AND log_date = :date");
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        $data['daily'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Diet
        $stmt = $this->conn->prepare("SELECT * FROM diet_logs WHERE user_id = :uid AND log_date = :date");
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        $data['diet'] = $stmt->fetch(PDO::FETCH_ASSOC);

        // Exercise (fetch latest or all? Let's just get all for list, or latest for filling form)
        // For the "Daily Form", usually we want to see what we entered. 
        $stmt = $this->conn->prepare("SELECT * FROM exercise_logs WHERE user_id = :uid AND log_date = :date");
        $stmt->bindParam(':uid', $user_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        $data['exercise'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $data;
    }
}
