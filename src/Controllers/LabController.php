<?php

namespace Controllers;

use Models\LogModel;
use Config\Database;
use PDO;

class LabController extends Controller {
    private $db;

    public function __construct() {
        $db = new Database();
        $this->db = $db->getConnection();
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $stmt = $this->db->prepare("SELECT * FROM lab_results WHERE user_id = :uid ORDER BY test_date DESC");
        $stmt->bindParam(':uid', $_SESSION['user_id']);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->view('logs/lab', ['results' => $results]);
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if ($this->isPost()) {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                // Update
                $sql = "UPDATE lab_results SET test_date=:date, wbc=:wbc, anc=:anc, hb=:hb, platelet=:plt, 
                        ast=:ast, alt=:alt, creatinine=:cr, cea=:cea, notes=:notes WHERE id=:id AND user_id=:uid";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':id', $_POST['id']);
            } else {
                // Insert
                $sql = "INSERT INTO lab_results (user_id, test_date, wbc, anc, hb, platelet, ast, alt, creatinine, cea, notes) 
                        VALUES (:uid, :date, :wbc, :anc, :hb, :plt, :ast, :alt, :cr, :cea, :notes)";
                $stmt = $this->db->prepare($sql);
            }
            
            // Helper to handle empty strings as NULL
            $nullable = function($val) {
                return ($val === '') ? null : $val;
            };

            $date = $_POST['test_date'];
            $wbc = $nullable($_POST['wbc']);
            $anc = $nullable($_POST['anc']);
            $hb = $nullable($_POST['hb']);
            $plt = $nullable($_POST['platelet']);
            $ast = $nullable($_POST['ast']);
            $alt = $nullable($_POST['alt']);
            $cr = $nullable($_POST['creatinine']);
            $cea = $nullable($_POST['cea']);
            $notes = $_POST['notes'];

            $stmt->bindParam(':uid', $_SESSION['user_id']);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':wbc', $wbc);
            $stmt->bindParam(':anc', $anc);
            $stmt->bindParam(':hb', $hb);
            $stmt->bindParam(':plt', $plt);
            $stmt->bindParam(':ast', $ast);
            $stmt->bindParam(':alt', $alt);
            $stmt->bindParam(':cr', $cr);
            $stmt->bindParam(':cea', $cea);
            $stmt->bindParam(':notes', $notes);
            
            $stmt->execute();
            $this->redirect('lab');
        }
    }

    public function delete() {
        if (!isset($_SESSION['user_id'])) $this->redirect('login');
        if ($this->isPost() && isset($_POST['id'])) {
            $stmt = $this->db->prepare("DELETE FROM lab_results WHERE id = :id AND user_id = :uid");
            $stmt->execute([':id' => $_POST['id'], ':uid' => $_SESSION['user_id']]);
            $this->redirect('lab');
        }
    }
}
