<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		//Do your magic here
	}

	public function index()
	{
		$this->load->view('v_login');
	}

	public function login_aksi()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$sebagai = $this->input->post('sebagai');

		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() != false) 
		{
			//cek form ada atau nggak 
			$where  = array(
				'username' => $username,
				'password' => md5($password)
			);

			//kondisi 1 : jika sebagai admin
			if ($sebagai == "admin") 
			{
				$cek = $this->m_data->cek_login('admin',$where)->num_rows();
				$data = $this->m_data->cek_login('admin', $where)->row();

				if ($cek > 0) 
				{
					$data_session = array(
						'id' => $data->id,
						'username' => $data->username,
						'status' => 'admin_login'
					);

					$this->session->set_userdata($data_session);
					
					redirect(base_url().'admin'); // direct ke controller admin

				}else{ redirect(base_url().'login?alert=gagal');}

				//jika tidak maka kondisi 2: sebagai petugas
			}elseif ($sebagai == 'petugas') 
			{
				$cek = $this->m_data->cek_login('petugas',$where)->num_rows();
				$data = $this->m_data->cek_login('petugas',$where)->row();

				if ($cek > 0 ) 
				{
					$data_session  = array(
						'id' => $data->id, 
						'nama' => $data->nama, 
						'username' => $data->username, 
						'status' => 'petugas_login', 
					);

					$this->session->set_userdata($data_session);

						redirect(base_url().'petugas'); //direct ke controller petugas
					}else{ redirect(base_url().'login?alert=gagal');}
				}
			}else{ $this->load->view('v_login');}
		}


	}



	/* End of file Login.php */
/* Location: ./application/controllers/Login.php */