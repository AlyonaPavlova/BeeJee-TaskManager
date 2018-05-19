<?php

class Controller_handler extends Controller
{

    function __construct()
    {
        $this->model = new Model_Handler();
        $this->view = new View();
    }

    function action_auth()
    {
        $data = $this->model->check_auth($_POST['login'], $_POST['password'], session_id());
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    function action_out()
    {
        $this->model->sign_out($_POST['login'], session_id());
        echo json_encode($_POST);
    }

    function action_register ()
    {
        $data = $this->model->register($_POST['email'], $_POST['login'], $_POST['password']);
        echo json_encode($data);
    }

    function action_task_status ()
    {
        $data = $this->model->set_task_status($_POST["state"], $_POST["id"]);
        echo json_encode($data);
    }

}

