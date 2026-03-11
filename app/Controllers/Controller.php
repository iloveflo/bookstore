<?php

namespace App\Controllers;

use League\Plates\Engine;

class Controller
{
	protected $view;

	public function __construct()
	{
		$this->view = new Engine(ROOTDIR . 'views');
	}

	public function sendPage($page, array $data = [])
	{
		exit($this->view->render($page, $data));
	}

	// Lưu các giá trị của form được cho trong $data vào $_SESSION 
	protected function saveFormValues(array $data, array $except = [])
	{
		$form = [];
		foreach ($data as $key => $value) {
			if (!in_array($key, $except, true)) {
				$form[$key] = $value;
			}
		}
		$_SESSION['form'] = $form;
	}

	protected function getSavedFormValues()
	{
		return session_get_once('form', []);
	}

	// Lưu các giá trị của form được cho trong $data vào $_SESSION update
	protected function saveUpdateFormValues(array $data, array $except = [])
	{
		$form = [];
		foreach ($data as $key => $value) {
			if (!in_array($key, $except, true)) {
				$form[$key] = $value;
			}
		}
		$_SESSION['update-form'] = $form;
	}

	protected function getSavedUpdateFormValues()
	{
		return session_get_once('update-form', []);
	}
}
