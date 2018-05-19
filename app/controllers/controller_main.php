<?php

class Controller_Main extends Controller
{
    function __construct()
    {
        $this->model = new Model_List();
        $this->view = new View();
    }

    function action_index()
    {
        $data = $this->model->test(1, 'id', 'DESC');
        $page = 0;
        $page_count = $this->model->get_page_count();
        require_once ('app/views/main_view.php');
    }

    function action_get_task()
    {
        $data = $this->model->get_tasks_on_page($_POST['page'], $_POST['sort_type'], $_POST['sort_direction']);
        echo $data;
    }

    function action_add_task()
    {
        if(isset($_FILES) && $_FILES['picture']['error'] == 0){ // Проверяем, загрузил ли пользователь файл
            $destiation_dir = 'images/userpic/'.$_FILES['picture']['name']; // Директория для размещения файла
            if ($_POST['login'] != 'Anonymous') {
                $this->model->add_task($_POST['task'], $_POST['login'], $_FILES['picture']['name']);
            } else {
                $this->model->add_task($_POST['task'], 'Anonymous', $_FILES['picture']['name']);
            }
            move_uploaded_file($_FILES['picture']['tmp_name'], $destiation_dir ); // Перемещаем файл в желаемую директорию
            echo 'File Uploaded'; // Оповещаем пользователя об успешной загрузке файла
        }
        else{
            echo 'No File Uploaded'; // Оповещаем пользователя о том, что файл не был загружен
        }
    }

    function action_delete_task ()
    {
        $data = $this->model->delete_task($_POST['task_id'], session_id(), $_POST['current_user'], $_POST['task_author']);
        echo $data;
    }
}