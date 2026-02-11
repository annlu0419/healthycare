<?php

namespace Controllers;

class Controller {
    protected function view($viewPath, $data = []) {
        extract($data);
        
        // Start output buffering
        ob_start();
        
        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            echo "View not found: " . $viewPath;
        }
        
        // Get content
        $content = ob_get_clean();
        
        // Render layout
        // Check if it's a login page to avoid sidebar/navbar if needed, or just include standard layout
        // For simplicity, we wrap everything in main layout, but login might need a simpler one
        // We can pass a flag or check viewPath
        
        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . "/" . ltrim($url, '/'));
        exit;
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
}
