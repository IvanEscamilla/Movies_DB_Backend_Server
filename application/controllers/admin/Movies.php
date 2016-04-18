<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Movies extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function get_movie_info()
	{
		//Load http request raw data and decoded params to class
        $this->httpData = $this->input->get_post('data');

        if((!is_string($this->httpData)) && ((is_array($this->httpData)) || (is_object($this->httpData))))
        {
            $this->httpParams = json_decode(json_encode($this->httpData));
        }
        else
        {
            $this->httpParams = json_decode($this->httpData);
        }

		$query = $this->httpParams;
		
		$this->load->model("database/get");

		$response["result"] = $this->get->get_movie_data($query->movie_info);
		$response["status"] = "OK";
		die(json_encode(json_decode(json_encode($response), true)));
	}
}
