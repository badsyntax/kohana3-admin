<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Activity extends Controller_Admin_Base {

	public function action_index()
	{
		$this->template->title = __('Admin - Activity');
		$this->template->content = View::factory('admin/page/activity/index')
			->bind('activities', $activities);

		$activities = ORM::factory('activity')
			->order_by('date', 'desc')
			->find_all();
	}

} // End Controller_Admin_Activity
