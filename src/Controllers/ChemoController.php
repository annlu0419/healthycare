<?php

namespace Controllers;

use Models\ChemoModel;

class ChemoController extends Controller {
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        $chemoModel = new ChemoModel();
        $cycles = $chemoModel->getAll($_SESSION['user_id']);

        $this->view('chemo/index', ['cycles' => $cycles]);
    }

    public function store() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }

        if ($this->isPost()) {
            $chemoModel = new ChemoModel();
            
            $data = [
                'user_id' => $_SESSION['user_id'],
                'cycle_no' => $_POST['cycle_no'],
                'infusion_date' => $_POST['infusion_date'],
                'regimen' => $_POST['regimen'],
                'dose_notes' => $_POST['dose_notes'],
                'notes' => $_POST['notes']
            ];

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                 // Update
                 $data['id'] = $_POST['id'];
                 $chemoModel->update($data);
            } else {
                 // Insert
                 $chemoModel->create($data);
            }
            
            $this->redirect('chemo');
        }
    }

    public function delete() {
        if (!isset($_SESSION['user_id'])) $this->redirect('login');
        if ($this->isPost() && isset($_POST['id'])) {
            $chemoModel = new ChemoModel();
            $chemoModel->delete($_POST['id'], $_SESSION['user_id']);
            $this->redirect('chemo');
        }
    }
}
