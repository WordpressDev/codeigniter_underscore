<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Academic Free License version 3.0
 *
 * This source file is subject to the Academic Free License (AFL 3.0) that is
 * bundled with this package in the files license_afl.txt / license_afl.rst.
 * It is also available through the world wide web at this URL:
 * http://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2012, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/AFL-3.0 Academic Free License (AFL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

class Welcome extends CI_Controller {

	// simple test function to ensure everything is set up correctly 
	// and you know how it works
	public function index()
	{

		require_once(APPPATH.'libraries/underscore.php');

		$result =  __()->map(array(1, 2, 3), function($n) { return $n * 2; });
		var_dump($result);

	}

	// if you don't want to just import the sql, create models here
	public function create_models()
	{
		$this->load->helper('string');
		for ($i=0; $i < 5000; $i++) { 
			$insert['name'] = random_string('alpha', 11);
			$this->db->insert('model', $insert);
		}
	}

	// if you don't want to just import the sql, create items here
	public function create_items()
	{
		$this->load->helper('string');
		for ($i=0; $i < 5000; $i++) {

			$item_count = rand(0,5);
			for ($j=0; $j < $item_count; $j++) {
			 	$insert['model_id'] = $i;
				$insert['name'] = random_string('alpha', 11);
				$this->db->insert('item', $insert);
			}
		}	
	}

	//Base time on function with join
	public function test_1()
	{
		$this->output->enable_profiler(TRUE);

		$sql = 'SELECT * FROM model m JOIN item i ON (m.id = i.model_id)';
		$query = $this->db->query($sql)->result_array();
	}

	// Time a "machine gun" approach
	public function test_2()
	{
		$this->output->enable_profiler(TRUE);

		$sql = 'SELECT * FROM model m';
		$query = $this->db->query($sql)->result_array();

		foreach($query as $model)
		{
			$sql = 'SELECT * FROM item i WHERE model_id = '.$model['id'];
			$inner = $this->db->query($sql)->result_array();			
		}	
	}

	// Use a single query and Underscore.php
	public function test_3()
	{

		$this->output->enable_profiler(TRUE);

		require_once(APPPATH.'libraries/underscore.php');

		$sql = 'SELECT *, m.name AS model_name FROM model m JOIN item i ON (m.id = i.model_id)';
		$query = $this->db->query($sql)->result_array();

		$grouped_result = __()->groupBy($query, 'model_name');

		var_dump($grouped_result);
	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */