<?php

namespace Controllers;

use Models\LogModel;

class LogController extends Controller {
    public function history() {
        if (!isset($_SESSION['user_id'])) $this->redirect('login');
        
        $logModel = new LogModel();
        $logs = $logModel->getHistory($_SESSION['user_id'], 90); // Last 90 days
        
        $this->view('logs/list', ['logs' => $logs]);
    }

    public function delete() {
        if (!isset($_SESSION['user_id'])) $this->redirect('login');
        if ($this->isPost() && isset($_POST['date'])) {
            $logModel = new LogModel();
            $logModel->delete($_SESSION['user_id'], $_POST['date']);
            $this->redirect('logs/history');
        }
    }

    // Daily Entry Form (Also handles Edit if date is passed)
    public function daily() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $date = $_GET['date'] ?? date('Y-m-d');
        
        $logModel = new LogModel();
        $data = $logModel->getDailyData($_SESSION['user_id'], $date);
        
        $this->view('logs/daily', ['date' => $date, 'data' => $data]);
    }

    public function save() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if ($this->isPost()) {
            $user_id = $_SESSION['user_id'];
            $log_date = $_POST['log_date'];
            $logModel = new LogModel();

            // SAVE DAILY LOGS
            $dailyData = [
                'user_id' => $user_id,
                'log_date' => $log_date,
                'weight' => $_POST['weight'] ?: null,
                'bp_systolic' => $_POST['bp_systolic'] ?: null,
                'bp_diastolic' => $_POST['bp_diastolic'] ?: null,
                'pulse' => $_POST['pulse'] ?: null,
                'temperature' => $_POST['temperature'] ?: null,
                'nausea_score' => $_POST['nausea_score'] ?? 0,
                'vomit_count' => $_POST['vomit_count'] ?? 0,
                'diarrhea_count' => $_POST['diarrhea_count'] ?? 0,
                'is_constipated' => isset($_POST['is_constipated']) ? 1 : 0,
                'stool_type' => $_POST['stool_type'] ?: null,
                'has_blood_stool' => isset($_POST['has_blood_stool']) ? 1 : 0,
                'pain_score' => $_POST['pain_score'] ?? 0,
                'bloating_score' => $_POST['bloating_score'] ?? 0,
                'numbness_score' => $_POST['numbness_score'] ?? 0,
                'fatigue_score' => $_POST['fatigue_score'] ?? 0,
                'appetite_score' => $_POST['appetite_score'] ?? 0,
                'mouth_sore_score' => $_POST['mouth_sore_score'] ?? 0,
                'sleep_score' => $_POST['sleep_score'] ?? 0,
                'mood_score' => $_POST['mood_score'] ?? 0,
                'notes' => $_POST['daily_notes'] ?? ''
            ];
            $logModel->saveDailyLog($dailyData);

            // SAVE DIET LOGS
            $dietData = [
                'user_id' => $user_id,
                'log_date' => $log_date,
                'water_intake' => $_POST['water_intake'] ?? 0,
                'meal_count' => $_POST['meal_count'] ?? 0,
                'content_summary' => $_POST['diet_content'] ?? '',
                'risk_foods' => isset($_POST['risk_foods']) ? implode(',', $_POST['risk_foods']) : ''
            ];
            $logModel->saveDietLog($dietData);

            // SAVE EXERCISE LOGS (Only if filled)
            if (!empty($_POST['exercise_type']) && !empty($_POST['duration_minutes'])) {
                $exerciseData = [
                    'user_id' => $user_id,
                    'log_date' => $log_date,
                    'exercise_type' => $_POST['exercise_type'],
                    'duration_minutes' => $_POST['duration_minutes'],
                    'intensity' => $_POST['intensity'] ?? 'Low',
                    'notes' => $_POST['exercise_notes'] ?? ''
                ];
                $logModel->saveExerciseLog($exerciseData);
            }

            $this->redirect("logs/daily?date=$log_date&saved=1");
        }
    }
}
