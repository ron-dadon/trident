<?php
class User_Entity extends Trident_Abstract_Entity
{
	public $id;
	public $name;
	public $password;
	public $salt;
	public $first_name;
	public $last_name;
	public $email;
	public $privilege;
	public $delete;
}