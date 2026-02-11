<?php

namespace Controllers;

use Models\User;

class ProfileController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $userModel = new User();
        $profile = $userModel->getProfile($_SESSION['user_id']);
        
        $this->view('auth/profile', ['profile' => $profile]);
    }

    public function update() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if ($this->isPost()) {
            $data = [
                'cancer_stage' => $_POST['cancer_stage'] ?? '',
                'tnm_stage' => $_POST['tnm_stage'] ?? '',
                'biomarkers' => isset($_POST['biomarkers']) ? implode(',', $_POST['biomarkers']) : '',
                'regimen_name' => $_POST['regimen_name'] ?? '',
                'has_stoma' => isset($_POST['has_stoma']) ? 1 : 0,
                'surgery_date' => !empty($_POST['surgery_date']) ? $_POST['surgery_date'] : null
            ];

            $userModel = new User();
            if ($userModel->updateProfile($_SESSION['user_id'], $data)) {
                $this->redirect('profile'); // Success
            } else {
                echo "Update failed"; // Simple error handling for now
            }
        }
    }
}
