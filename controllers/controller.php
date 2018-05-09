<?php

namespace App;


use Exception;

class Controller {
    public function index() {
        // get data from database
        try {
            $db = new Price();

            $data = $db->index();
        }
        catch (Exception $e) {
            file_put_contents(__DIR__.'/../log/Errors.txt',date('Y-m-d H:i').' [Error] '.$e->getMessage()."\r\n",FILE_APPEND);
        }

        // call view
        require_once __DIR__."/../views/view.php";
    }
}