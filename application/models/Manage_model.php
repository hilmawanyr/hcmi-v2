<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		
	}

	/**
	 * Get all assessment year list
	 * 
	 * @return array
	 */
	public function get_assessment_year() : array
	{
		return $this->db->get('assessment_years')->result();
	}

	/**
	 * Get specify assessment year data
	 * @param int $id
	 * @return object
	 */
	public function get_assessment_year_detail(int $id) : object
	{
		$this->db->where('id', $id);
		return $this->db->get('assessment_years', 1)->row();
	}

	/**
	 * Get all departments
	 * 
	 * @return array
	 */
	public function get_all_departments() : array
	{
		$this->db->where('deleted_at');
		return $this->db->get('departements')->result();
	}

	/**
	 * Get section by department
	 * @param int $id
	 * @return array
	 */
	public function section_by_department(int $id) : array
	{
		$this->db->where('deleted_at');
		$this->db->where('dept_id', $id);
		return $this->db->get('sections')->result();
	}

	/**
	 * Get all jobtitles list
	 * @param int $id
	 * @return array
	 */
	public function get_jobtitles_list(int $id) : array
	{
		$this->db->where('section', $id);
		return $this->db->get('job_titles')->result();
	}

	/**
	 * Get specific or all information from informations table
	 * @param int $id; default null
	 * @return object
	 */
	public function get_information(int $id = null) : object
	{
		switch ($id) {
			case null:
				return $this->db->where('deleted_at')->get('informations');
				break;
			
			default:
				return $this->db->where('id', $id)->get('informations');
				break;
		}		
	}
	
}

/* End of file Manage_model.php */
/* Location: ./application/models/Manage_model.php */