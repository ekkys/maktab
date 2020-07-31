<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		// cek session yang login, jika session status tidak sama dengan session admin_login, maka halaman akan di alihkan kembali ke halaman login.

		//kenapa dicek di construct agar pengecekan dilkakukan sebelum index dijalankan

		if ($this->session->userdata('status') != 'admin_login') {
			redirect(base_url().'login?alert=belum_login');
		} 
	}

	function index()
	{
		$this->load->view('admin/v_header');
		$this->load->view('admin/v_index');
		$this->load->view('admin/v_footer');
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('login/?alert=logout'), 'refresh');
	}

	function ganti_password()
	{
		$this->load->view('admin/v_header');
		$this->load->view('admin/v_ganti_password');
		$this->load->view('admin/v_footer');
	}

	function ganti_password_aksi()
	{
		$baru = $this->input->post('password_baru');
		$ulang = $this->input->post('password_ulang');

		$this->form_validation->set_rules('password_baru','Password Baru','required|matches[password_ulang]');
		$this->form_validation->set_rules('password_ulang', 'Ulangi Password','required');

		if ($this->form_validation->run() != false) 
		{
			$id = $this->session->userdata('id');

			$where = array('id' => $id );
			$data  = array('password' => md5($baru) );


			echo"Password Baru". var_dump($baru)."<br>";
			echo"Password Ulang".var_dump($ulang)."<br>";
			echo"Admin Id".var_dump($id)."<br>";
			echo"Password db".var_dump($data)."<br>";
			echo"Admin Id".var_dump($where)."<br>";
			die();

			$this->m_data->update_data($where, $data, 'admin'); 

			redirect(base_url('admin/ganti_password/?alert=sukses'));
			// WHERE id = ' ', password baru, nama tabel 

		}else{
			$this->load->view('admin/v_header');
			$this->load->view('admin/v_ganti_password');
			$this->load->view('admin/v_footer');	
		}
	}


	//CRUD Petugas

	function petugas()
	{
		// mengambil data dari database

		$data['petugas'] = $this->m_data->get_data('petugas')->result();

		$this->load->view('admin/v_header');
		$this->load->view('admin/v_petugas', $data);
		$this->load->view('admin/v_footer'); 
	}

	function petugas_tambah()
	{
		$this->load->view('admin/v_header');
		$this->load->view('admin/v_petugas_tambah');
		$this->load->view('admin/v_footer'); 
	}

	function petugas_tambah_aksi()
	{
		//menampung inputan dari elemen input
		$nama = $this->input->post('nama');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		//menampung data ke dalam array
		$data = array(
			'nama' => $nama,
			'username' => $username,
			'password' => md5($password)
		);

		//insert data ke database
		$this->m_data->insert_data($data, 'petugas');

		//mengalihkan halaman ke hal data petugas
		redirect(base_url('admin/petugas'));
	}

	function petugas_edit($id)
	{
		$where = array('id' => $id );

		//mengambil data dari database sesuai id
		$data['petugas'] = $this->m_data->edit_data($where, 'petugas')->result();
		$this->load->view('admin/v_header');
		$this->load->view('admin/v_petugas_edit',$data);
		$this->load->view('admin/v_footer');
	}

	function petugas_update()
	{
		

		//menampung inputan dari elemen input
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$where = array('id' => $id ); 

		//cek apakah form password di isi atau tidak

		if ($password=="") 
		{
			//menampung data ke dalam array
			$data = array(
				'nama' => $nama,
				'username' => $username
			);

			//update data ke database
			$this->m_data->update_data($where, $data, 'petugas');

			//mengalihkan ke halaman data petugas
			redirect(base_url('admin/petugas'));

		}else{
			//menampung data ke dalam array
			$data = array(
				'nama' => $nama,
				'username' => $username,
				'password' => md5($password)
			);

			//update data ke database
			$this->m_data->update_data($where, $data, 'petugas');

			//mengalihkan ke halaman data petugas
			redirect(base_url('admin/petugas'));

		}
	}

	function petugas_hapus($id)
	{
		$where = array('id'=> $id);

		//menghapus data petugas dari database sesuai id
		$this->m_data->delete_data('petugas', $where); 

		//mengalihkan halaman ke halaman data petugas
		redirect(base_url('admin/petugas'));
	}
	//Akhir CRUD petugas 

	//Anggota
	function anggota()
	{
		//mengambil data dari database
		$data['anggota'] = $this->m_data->get_data('anggota')->result();

		//ini viewnya
		$this->load->view('admin/v_header');
		$this->load->view('admin/v_anggota',$data);
		$this->load->view('admin/v_footer');
	}

	function anggota_kartu($id)
	{
		//menampung id kartu yang di pilih
		$where = array('id' => $id );

		//mengambil daya dari database berdasarkan id
		$data['anggota'] = $this->m_data->edit_data($where, 'anggota')->result();
		$this->load->view('admin/v_anggota_kartu', $data);
		
	}
	//akhir anggota

	//Buku
	function buku()
	{
		// mengambil data dari database
		$data['buku'] = $this->m_data->get_data('buku')->result();
		$this->load->view('admin/v_header');
		$this->load->view('admin/v_buku',$data);
		$this->load->view('admin/v_footer');
	}
	//Akhir Buku

//peminjaman
	function peminjaman_laporan()
	{
		if (isset($_GET['tanggal_mulai']) && isset($_GET['tanggal_sampai'])) 
		{
			$mulai = $this->input->get('tanggal_mulai');
			$sampai = $this->input->get('tanggal_sampai');

			//mengambil data peminjaman berdasarkan tanggal mulai dan tanggal sampai
			$data['peminjaman'] = $this->db->query("SELECT * FROM peminjaman,buku,anggota 
				where peminjaman.peminjaman_buku=buku.id 
				AND peminjaman.peminjaman_anggota=anggota.id 
				AND date(peminjaman_tanggal_mulai) >='$mulai' 
				AND date(peminjaman_tanggal_mulai) <= '$sampai' 
				ORDER BY peminjaman_id	DESC")->result();
		}else{
			// mengambil data peminjaman buku dari database | dan mengurutkan data dari id peminjaman terbesar ke terkecil (desc)
			$data['peminjaman'] = $this->db->query("SELECT * FROM peminjaman,buku,anggota 
				WHERE peminjaman.peminjaman_buku=buku.id 
				AND peminjaman.peminjaman_anggota=anggota.id 
				ORDER BY peminjaman_id DESC")->result();
		}

		$this->load->view('admin/v_header');
		$this->load->view('admin/v_peminjaman_laporan',$data);
		$this->load->view('admin/v_footer');
	}

	function peminjaman_cetak()
	{
		if(isset($_GET['tanggal_mulai']) && isset($_GET['tanggal_sampai']))
		{
			
			$mulai = $this->input->get('tanggal_mulai');
			$sampai = $this->input->get('tanggal_sampai');
			
			// mengambil data peminjaman berdasarkan tanggal mulai sampai tanggalsampai
			$data['peminjaman'] = $this->db->query("SELECT * FROM peminjaman,buku,anggota 
				WHERE peminjaman.peminjaman_buku=buku.id 
				AND peminjaman.peminjaman_anggota=anggota.id 
				AND  date(peminjaman_tanggal_mulai) >= '$mulai' 
				AND date(peminjaman_tanggal_mulai) <= '$sampai' 
				ORDER BY peminjaman_id DESC")->result();
			$this->load->view('admin/v_peminjaman_cetak',$data);

		}else{

			redirect(base_url('admin/peminjaman'));
		}
//Akhir peminjaman

	}
}

	/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */