<?php

namespace Models;

use Config\Database;
use PDO;

class ChemoModel {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    public function getAll($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM chemo_cycles WHERE user_id = :uid ORDER BY cycle_no DESC");
        $stmt->bindParam(':uid', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO chemo_cycles (user_id, cycle_no, infusion_date, regimen, dose_notes, notes) 
                VALUES (:uid, :cno, :date, :regimen, :dnotes, :notes)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':uid', $data['user_id']);
        $stmt->bindParam(':cno', $data['cycle_no']);
        $stmt->bindParam(':date', $data['infusion_date']);
        $stmt->bindParam(':regimen', $data['regimen']);
        $stmt->bindParam(':dnotes', $data['dose_notes']);
        $stmt->bindParam(':notes', $data['notes']);
        return $stmt->execute();
    }

    public function update($data) {
        $sql = "UPDATE chemo_cycles SET 
                cycle_no = :cno, infusion_date = :date, regimen = :regimen, 
                dose_notes = :dnotes, notes = :notes 
                WHERE id = :id AND user_id = :uid";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $data['id']);
        $stmt->bindParam(':uid', $data['user_id']);
        $stmt->bindParam(':cno', $data['cycle_no']);
        $stmt->bindParam(':date', $data['infusion_date']);
        $stmt->bindParam(':regimen', $data['regimen']);
        $stmt->bindParam(':dnotes', $data['dose_notes']);
        $stmt->bindParam(':notes', $data['notes']);
        return $stmt->execute();
    }

    public function delete($id, $user_id) {
        $stmt = $this->conn->prepare("DELETE FROM chemo_cycles WHERE id = :id AND user_id = :uid");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':uid', $user_id);
        return $stmt->execute();
    }
}
