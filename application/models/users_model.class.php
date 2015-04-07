<?php


class Users_Model extends Trident_Abstract_Model
{

    public function get_all()
    {
        $query = new Trident_Query_MySql();
        $query->select('users');
        $query = $this->database->run_query($query);
        if ($query->success && $query->row_count > 0)
        {
            $result = [];
            foreach ($query->result_set as $user)
            {
                $user_instance = new User_Entity();
                $user_instance->data_from_array($user, 'user_');
                $result[] = $user_instance;
            }
            return $result;
        }
        return [];
    }
} 