<?php
class Controller1 extends CI_Controller {
    public function index()
    {
        $this->load->library('view');
        $this->view->render();
    }
}