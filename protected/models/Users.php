<?php

class Users extends CActiveRecord
{
	public function tableName()
	{
		return 'users';
	}

	public function rules()
	{
		return [
			['username, email, password', 'required'],
			['email', 'email'],
			['salt', 'makeSalt', 'on' => 'insert'],
			['password', 'makePassword', 'skipOnError' => true, 'on' => 'insert'],
			['username, email', 'unique', 'className' => 'Users'],
		];
	}

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function checkPassword($password)
	{
		return $this->password === $this->hashPassword($password);
	}

	private function hashPassword($password)
	{
		if($this->salt === null)
			$this->makeSalt();

		return md5(sprintf('%s%s%s', $this->salt, $password, $this->salt));
	}

	public function makeSalt()
	{
		$this->salt = md5(microtime(true));
	}

	public function makePassword()
	{
		$this->password = $this->hashPassword($this->password);
	}
}