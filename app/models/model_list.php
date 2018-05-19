<?php

class Model_List extends Model
{

    function __construct()
    {
        $this->db = new SafeMySQL(array('user' => 'pavlovaap', 'pass' => '', 'db' => 'alena_p', 'port' => '3306'));
    }

    public function test($cur_page, $sort_type, $sort_direction){
        $per_page = 3;
        $start = ($cur_page - 1) * $per_page;
        $sql  = 'SELECT SQL_CALC_FOUND_ROWS tasklist.id, description, email, login, isdone, imgpath 
                FROM tasklist LEFT JOIN users ON author=users.id ORDER BY ?n '.$sort_direction.' LIMIT ?i OFFSET ?i';
        $data = $this->db->getAll($sql, $sort_type, $per_page, $start);
        return $data;
    }

    public function get_page_count(){
        $per_page = 3;
        $sql  = 'SELECT SQL_CALC_FOUND_ROWS * FROM tasklist';
        $data = $this->db->getAll($sql);
        $rows = $this->db->getOne("SELECT FOUND_ROWS()");
        $page_count = ceil($rows / $per_page);
        return $page_count ;
    }

    public function page_validation($cur_page){
        if($cur_page > 0){
            return true;
        } else {
            return false;
        }
    }

    public function direction_validation($sort_direction){
        if($sort_direction === "DESC"){
            return true;
        } else if ($sort_direction === "ASC"){
            return true;
        } else {
            return false;
        }
    }

    public function type_validation($sort_type){
        if($sort_type === "id"){
            return true;
        } else if ($sort_type === "login"){
            return true;
        } else if ($sort_type === "email"){
            return true;
        } else if ($sort_type === "isdone"){
            return true;
        } else {
            return false;
        }
    }

    public function get_tasks_on_page($cur_page, $sort_type, $sort_direction){
        if($this->page_validation($cur_page) === true && $this->direction_validation($sort_direction) === true &&
            $this->type_validation($sort_type) === true) {
            $per_page = 3;
            $start = ($cur_page - 1) * $per_page;
            $sql = 'SELECT SQL_CALC_FOUND_ROWS tasklist.id, description, email, login, isdone, imgpath 
                FROM tasklist LEFT JOIN users ON author=users.id ORDER BY ?n ' . $sort_direction . ' LIMIT ?i OFFSET ?i';
            $data = $this->db->getAll($sql, $sort_type, $per_page, $start);
            $task_string = '';

            foreach ($data as $row) {
                $check_status = '';
                if ($row["isdone"] === "1") {
                    $check_status = 'checked="checked"';
                }
                $task_string = $task_string . '<li class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <img src="images/userpic/' . $row["imgpath"] . '" width="320" height="240">
                                        </div>
                                        <div class="col-md-6">
                                            <h2 class="text-primary pt-3">Task №<span class="taskId"> ' . $row["id"] . '  </span></h2>
                                            <p>  ' . $row["description"] . '  </p>
                                            <p>User: <span class="taskAuthor">  ' . $row["login"] . '</span></p> 
                                            <p> Email:   ' . $row["email"] . '  </p>
                                        </div> 
                                        <div class="col-md-1"> 
                                            <p class="campaign-signup with-tooltip mb-5"> 
                                                <label> 
                                                    <input type="checkbox" class="checkbox addStatus"  ' . $check_status . '>
                                                    <span class="label-text"> 
                                                        <a href="#" class="icon-question-sign" 
                                                            data-original-title="Mark the task is done" 
                                                            data-placement="right" data-toggle="tooltip">
                                                        </a>
                                                    </span> 
                                                </label> 
                                            </p> 
                                            <p>
                                                <button type="button" class="close mt-5" data-dismiss="modal" aria-label="Close"> 
                                                    <span class="icon-trash" aria-hidden="true"></span>
                                                </button>
                                            </p> 
                                        </div> 
                                    </div> 
                                </li>';
            }

            return $task_string;
        }
    }

    public function add_task($description, $login, $imagename)
    {
        $sql = 'INSERT INTO tasklist SET description=?s, author=(SELECT id FROM users WHERE login=?s), imgpath=?s;';
        $this->db->query($sql, $description, $login, $imagename);
    }

    public function delete_task ($taskid, $sessionkey, $currentuser, $taskauthor)
    {
        $selectedTask = $this->db->getAll('SELECT * FROM ?n WHERE ?n=?s', 'tasklist', 'id', $taskid);
        if ($selectedTask) {
            $verifyedUser = $this->db->getAll('SELECT * FROM ?n WHERE ?n=?s AND ?n=?s', 'users', 'login', $currentuser,
                'sessionkey', $sessionkey);
            if ($verifyedUser && $verifyedUser[0]['isadmin'] == '1') {
                $this->db->query('DELETE FROM tasklist WHERE ?n=?s',  'id', $taskid);
                return "ok";
            } else if ($verifyedUser && $currentuser == $taskauthor){
                $this->db->query('DELETE FROM tasklist WHERE ?n=?s',  'id', $taskid);
                return "ok";
            } else if (($taskauthor == 'anonymous') && ($selectedTask[0]['authorsessionkey'] == $sessionkey)){
                $this->db->query('DELETE FROM tasklist WHERE ?n=?s',  'id', $taskid);
                return "ok";
            } else {
                return "error";
            }

        } else {
            return 'Selected task is already deleted';
        }
    }
}