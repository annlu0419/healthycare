<?php

namespace Controllers;

use Models\LogModel;
use Models\User;
use Models\ChemoModel;

class ReportController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if (isset($_GET['type'])) {
            $type = $_GET['type'];
            if ($type === 'csv') {
                $this->exportCsv();
            } elseif ($type === 'print') {
                $this->printView();
            }
        } else {
             $this->view('reports/index');
        }
    }

    private function exportCsv() {
        $user_id = $_SESSION['user_id'];
        $logModel = new LogModel();
        // Get all history
        $data = $logModel->getHistory($user_id, 365);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=patient_logs.csv');
        $output = fopen('php://output', 'w');
        
        // BOM for Excel to read UTF-8
        fputs($output, "\xEF\xBB\xBF");

        fputcsv($output, ['日期', '體重', '體溫', '噁心', '嘔吐', '腹瀉', '便祕', '麻木', '疲勞', '備註']);
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['log_date'],
                $row['weight'],
                $row['temperature'],
                $row['nausea_score'],
                $row['vomit_count'],
                $row['diarrhea_count'],
                $row['is_constipated'] ? '是' : '否',
                $row['numbness_score'],
                $row['fatigue_score'],
                $row['notes']
            ]);
        }
        fclose($output);
        exit;
    }

    private function printView() {
        $user_id = $_SESSION['user_id'];
        $days = $_GET['days'] ?? 14;
        
        $logModel = new LogModel();
        $userModel = new User();
        $chemoModel = new ChemoModel();
        
        $logs = $logModel->getHistory($user_id, $days);
        $profile = $userModel->getProfile($user_id);
        $chemo = $chemoModel->getAll($user_id); // Get all, maybe limit later
        
        // Calculate averages/max
        $stats = [
            'avg_diarrhea' => 0,
            'max_temp' => 0,
            'max_numbness' => 0,
            'avg_fatigue' => 0,
            'weight_change' => 0
        ];

        if (!empty($logs)) {
            $diarrhea_sum = 0;
            $fatigue_sum = 0;
            $temps = [];
            $numbness = [];
            $weights = [];

            foreach ($logs as $l) {
                $diarrhea_sum += $l['diarrhea_count'];
                $fatigue_sum += $l['fatigue_score'];
                $temps[] = $l['temperature'];
                $numbness[] = $l['numbness_score'];
                if ($l['weight'] > 0) $weights[] = $l['weight'];
            }
            
            $count = count($logs);
            $stats['avg_diarrhea'] = round($diarrhea_sum / $count, 1);
            $stats['avg_fatigue'] = round($fatigue_sum / $count, 1);
            $stats['max_temp'] = max($temps);
            $stats['max_numbness'] = max($numbness);
            
            if (count($weights) >= 2) {
                $first = $weights[0];
                $last = end($weights);
                $stats['weight_change'] = $last - $first;
            }
        }

        // We render a special print layout
        extract(['logs' => $logs, 'profile' => $profile, 'chemo' => $chemo, 'stats' => $stats, 'days' => $days]);
        require __DIR__ . '/../Views/reports/print.php';
        exit; // Stop standard layout
    }
}
