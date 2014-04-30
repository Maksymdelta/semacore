<?php

namespace Admin;

class Controller {
    public function renderPage($f3, $pageName) {
        $uiPath = $f3->get('UI');

        $f3->set('content', "../${uiPath}admin/pages/${pageName}.html");
        echo \Template::instance($f3)->render("../${uiPath}admin/main.html", 'text/html');
    }

    public function renderJSON($f3, $data) {
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
    }
}
