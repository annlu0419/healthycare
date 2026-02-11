<?php

namespace Controllers;

use Models\LogModel;
use Models\User;

class DashboardController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $user_id = $_SESSION['user_id'];
        $logModel = new LogModel();
        $userModel = new User();
        
        // Get last 14 days history for charts
        $history = $logModel->getHistory($user_id, 30); // Get 30 days for trend
        $profile = $userModel->getProfile($user_id);

        // Analyze alerts
        $alerts = [];
        $today = date('Y-m-d');
        $todayData = $logModel->getDailyData($user_id, $today)['daily'];

        if ($todayData) {
            if ($todayData['temperature'] >= 38.0) {
                $alerts[] = ['type' => 'danger', 'msg' => '今日體溫 ≥ 38°C，請注意感染風險，必要時回診！'];
            }
            if ($todayData['diarrhea_count'] >= 6) {
                $alerts[] = ['type' => 'warning', 'msg' => '今日腹瀉次數 ≥ 6 次，請注意脫水風險，補充水分與電解質。'];
            }
            if ($todayData['vomit_count'] >= 3) {
                $alerts[] = ['type' => 'warning', 'msg' => '今日嘔吐次數偏高，請嘗試少量進食並預防脫水。'];
            }
        }
        
        // Prepare Chart Data
        $chartData = [
            'dates' => [],
            'weight' => [],
            'temp' => [],
            'diarrhea' => [],
            'nausea' => [],
            'fatigue' => [],
            'numbness' => []
        ];

        // Reverse history for charts (Oldest to Newest)
        $chartHistory = array_reverse($history);

        foreach ($chartHistory as $row) {
            $chartData['dates'][] = substr($row['log_date'], 5); // mm-dd
            $chartData['weight'][] = $row['weight'];
            $chartData['temp'][] = $row['temperature'];
            $chartData['diarrhea'][] = $row['diarrhea_count'];
            $chartData['nausea'][] = $row['nausea_score'];
            $chartData['fatigue'][] = $row['fatigue_score'];
            $chartData['numbness'][] = $row['numbness_score']; // Special for FOLFOX
        }

        $this->view('dashboard/index', [
            'history' => $history,
            'chartData' => $chartData,
            'alerts' => $alerts,
            'profile' => $profile
        ]);
    }
}
