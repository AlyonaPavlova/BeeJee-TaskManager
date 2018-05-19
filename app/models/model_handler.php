<?php

class Model_Handler extends Model
{

    function __construct()
    {
        $this->db = new SafeMySQL(array('user' => 'pavlovaap', 'pass' => '', 'db' => 'alena_p', 'port' => '3306'));
    }

    public function check_auth($login, $pwd, $sessionkey)
    {
        $sessionedUser = NULL;
        if (sizeof($sessionkey)) { // Если присутствует ключ сессии
            $sessionedUser = $this->get_sessioned_user($sessionkey); // Делаем выборку из БД по ключу сессии
        }
        if ($sessionedUser != NULL){ // Если есть пользователь с активной сессией
            return array("status" => "okSession", "userData" => $sessionedUser);
        }
        $userData = $this->get_user_by_login_password($login, $pwd, $sessionkey); // Делаем выборку по логину-паролю
        return $userData;
    }

    // Проверяем, есть ли активная сессия у пользователя
    public function get_sessioned_user($sessionkey)
    {
        $sessionedUser = $this->db->getAll('SELECT ?n, ?n FROM ?n WHERE ?n=?s ', 'login', 'email', 'users', 'sessionkey', $sessionkey);
        if (sizeof($sessionedUser)){
            return $sessionedUser;
        }
        return NULL;
    }

    // Проверяем логин и пароль пользователя
    public function get_user_by_login_password($login, $pwd, $sessionkey)
    {
        $userData = $this->db->getAll('SELECT ?n, ?n, ?n FROM ?n WHERE ?n=?s ', 'login', 'email','password' , 'users', 'login', $login);
        if (sizeof($userData)) {
            if ($userData[key($userData)]['password'] == $pwd){
                $this->db->query("UPDATE ?n SET ?n=?s WHERE login = ?s", 'users', 'sessionkey', $sessionkey, $login);
                return array("status" => "okLogin", "userData" => $userData);
            }
            return array("status" => "pwderror", "userData" => "Wrong password");
        }
        return array("status" => "loginerror", "userData" => "Login does not exist");
    }

    // Затираем ключ сессии
    public function sign_out($login, $sessionkey)
    {
        $sessionedUser = $this->db->getInd('id','SELECT * FROM ?n WHERE ?n=?s AND ?n=?s','users',
            'sessionkey', $sessionkey, 'login', $login);
        if (sizeof($sessionedUser)){
            $this->db->query("UPDATE ?n SET ?n=?s WHERE login = ?s", 'users', 'sessionkey', NULL, $login);
        }
    }

    public function register ($email, $login, $password)
    {
        // Check if e-mail address syntax is valid or not
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return "Invalid Email.";
        } else {
            $existedUser = $this->db->getInd('id', 'SELECT * FROM ?n WHERE ?n=?s OR ?n=?s', 'users', 'login', $login, 'email', $email);
            if (sizeof($existedUser)) {
                return "User with same login or email already registered";
            } else {
                $query = $this->db->query("INSERT INTO users(?n, ?n, ?n) VALUES (?s, ?s, ?s)", 'login', 'email', 'password',  $login, $email, $password);
                if($query){
                    return "You have Successfully Registered.";
                }else
                {
                    return "Error.";
                }
            }
        }
    }

    public function set_task_status ($state, $id)
    {
        $this->db->query("UPDATE ?n SET ?n=?s WHERE id = ?s", 'tasklist', 'isdone', $state, $id);
        return "ok";
    }
}