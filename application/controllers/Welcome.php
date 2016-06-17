<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct() { 
		parent::__construct(); 
		$this->load->library('session'); 
		$this->load->helper('form'); 
	}

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
	public function index()
	{
    	$this->load->library('form_validation');
    	
		//$this->load->view('welcome_message');
		//$this->form_validation->set_rules('name','Name','trim|required');

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('company', 'Company', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
 
	    if($this->form_validation->run()===FALSE)
	    {
	    	$this->load->view('welcome_message');
	    }
	    else
	    {
	    	$config = Array(
			    'protocol' => 'smtp',
			    'smtp_host' => 'ssl://smtp.googlemail.com',
			    'smtp_port' => 465,
			    'smtp_user' => 'chithewebdeveloper@gmail.com',
			    'smtp_pass' => 'sm1l1ngs4thew3Ak',
			    'mailtype'  => 'html', 
			    'charset'   => 'iso-8859-1'
			);
			$this->load->library('email', $config);
			$this->email->set_newline("\r\n");
	    	
	    	$from_email = "chithewebdeveloper@gmail.com"; 
        	$to_email = $this->input->post('email');
	    	//Load email library 
			

			$this->email->from($from_email, 'Erts Representative'); 
			$this->email->to($to_email);
			$this->email->subject('Email Test'); 
			$this->email->message('Testing the email class.');
	      	//echo 'passed';
	      	//Send mail 
			if($this->email->send()) 
				$this->session->set_flashdata("email_sent","Email sent successfully."); 
			else 
				$this->session->set_flashdata("email_sent","Error in sending Email."); 
			$this->load->view('welcome_message');
	    }
	}

	public function recaptcha($str='')
	{
		$google_url="https://www.google.com/recaptcha/api/siteverify";
		$secret='6Ld_piITAAAAAOPmoxEIihf3J1zSoEXhd33wDqpd';
		$ip=$_SERVER['REMOTE_ADDR'];
		$url=$google_url."?secret=".$secret."&response=".$str."&remoteip=".$ip;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
		$res = curl_exec($curl);
		curl_close($curl);
		$res= json_decode($res, true);
		//reCaptcha success check
		if($res['success'])
		{
			return TRUE;
		}
		else
		{
			$this->form_validation->set_message('recaptcha', 'The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?');
			return FALSE;
		}
	}

	public function contact_us()
	{
		//$this->load
	}
}
