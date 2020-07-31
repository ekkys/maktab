<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Petugas extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// cek session yang login, jika session status tidak sama dengan session petugas_login, maka halaman akan di alihkan kembali ke halaman login.

		//kenapa dicek di construct agar pengecekan dilkakukan sebelum index dijalankan

		if ($this->session->userdata('status') != 'petugas_login') {
			redirect(base_url().'login?alert=belum_login');
		} 
	}

	public function index()
	{
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_index');
		$this->load->view('petugas/v_footer');
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('login/?alert=logout'));
	}

	function ganti_password()
	{
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_ganti_password');
		$this->load->view('petugas/v_footer');
	}

	function ganti_password_aksi()
	{
		//menampung input
		$baru = $this->input->post('password_baru');
		$ulang = $this->input->post('password_baru');

		//cek kesesuaian password baru dengan password ulang
		$this->form_validation->set_rules('password_baru','Password Baru', 'required|matches[password_ulang]');
		$this->form_validation->set_rules('password_ulang', 'Password Ulang', 'required');

		if ($this->form_validation->run() != false) {
			
			//ambil id dari session bukan dari url ok
			$id = $this->session->userdata('id');

			$where = array('id' => $id );

			$data  = array('password' => md5($baru) );

			$this->m_data->update_data($where, $data, 'petugas');

			redirect(base_url('petugas/ganti_password/?alert=sukses'));
		}else{
			$this->load->view('petugas/v_header');
			$this->load->view('petugas/v_ganti_password');
			$this->load->view('petugas/v_footer');
		}
	}

//CRUD anggota

	function anggota()
	{
		//mengambil data 
		$data['anggota'] = $this->m_data->get_data('anggota')->result();
		//ini load viewnya
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_anggota',$data); 
		$this->load->view('petugas/v_footer');
	}

	function anggota_tambah()
	{
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_anggota_tambah');
		$this->load->view('petugas/v_footer');
	}

	function anggota_tambah_aksi()
	{
		//menampung data inputan
		$nama = $this->input->post('nama');
		$nik = $this->input->post('nik');
		$alamat = $this->input->post('alamat');

		//ditampung di array
		$data = array(
			'nama' => $nama,
			'nik' => $nik,
			'alamat' => $alamat
		);

		//insert data ke database
		$this->m_data->insert_data($data, 'anggota');

		//mengalihkan ke halaman data anggota
		redirect(base_url('petugas/anggota'));

	}

	function anggota_edit($id)
	{
		//menangkap id dari url simpan dalam array
		$where  = array('id' => $id );

		//mengambil data dari database sesuai id 
		$data['anggota'] = $this->m_data->edit_data($where, 'anggota')->result();
		//ini viewnya
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_anggota_edit',$data);
		$this->load->view('petugas/v_footer'); 
	}

	function anggota_update()
	{
		// menampung inputan dari halaman edit
		$id = $this->input->post('id');
		$nama = $this->input->post('nama');
		$nik = $this->input->post('nik');
		$alamat = $this->input->post('alamat');

		//di tampung di array

			//di pisah agar mudah digunakan untuk load data dari model
		$where  = array('id' => $id );

		$data = array(
			'nama' => $nama , 
			'nik' => $nik , 
			'alamat' => $alamat  
		);

		// update data ke database
		$this->m_data->update_data($where, $data, 'anggota');

		//mengalihkan halaman ke halaman data anggota
		redirect(base_url('petugas/anggota'));
	}

	function anggota_hapus($id)
	{
		//menampung id dalam array
		$where = array('id' => $id );

		//menghapus data anggota dari database sesuai id
		$this->m_data->delete_data('anggota', $where);

		// mengalihkan halaman ke halaman data anggota
		redirect(base_url().'petugas/anggota');

	}

	function anggota_kartu($id)
	{
		//menampung id ke dalam array
		$where = array('id' => $id);

		//mengambil data sesuai id
		$data['anggota'] = $this->m_data->edit_data($where, 'anggota')->result();

		//view untuk cetak kartu anggota
		$this->load->view('petugas/v_anggota_kartu', $data );

	}
//Akhir CRUD Anggota


//CRUD Buku

	function buku()
	{
		//mengambil data dari database
		$data['buku'] = $this->m_data->get_data('buku')->result();

		//ini viewnya
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_buku',$data);
		$this->load->view('petugas/v_footer');

	}

	function buku_tambah(){
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_buku_tambah');
		$this->load->view('petugas/v_footer');
	}

	function buku_tambah_aksi()
	{
		$judul = $this->input->post('judul');
		$tahun = $this->input->post('tahun');
		$penulis = $this->input->post('penulis');

		$data = array(
			'judul' => $judul,
			'tahun' => $tahun,
			'penulis' => $penulis,
			'status' => 1 
		);

	  //insert data ke database
		$this->m_data->insert_data($data, 'buku');

	  //mengalihkan halaman ke data buku
		redirect(base_url('petugas/buku'));
	}

	public function buku_edit($id)
	{
		//id ditampung di array
		$where = array('id' => $id );

		//menampilkan data dari database sesuai id
		$data['buku'] = $this->m_data->edit_data( $where, 'buku')->result();

		//view edit data buku + load data nya
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_buku_edit', $data);
		$this->load->view('petugas/v_footer');
	}


	function buku_update()
	{
		//data ditampung di array
		$id = $this->input->post('id');
		$judul = $this->input->post('judul');
		$tahun = $this->input->post('tahun');
		$penulis = $this->input->post('penulis');
		$status = $this->input->post('status');

		$where = array('id' => $id );

		$data = array(
			'judul' => $judul,
			'tahun' => $tahun,
			'penulis' => $penulis,
			'status' => $status
		);

		//update data ke database
		$this->m_data->update_data($where, $data, 'buku');

		//mengalihkan ke halaman data buku
		redirect(base_url('petugas/buku'));
	}

	function buku_hapus($id)
	{
		//menampung id ke variable dgn type data array
		$where = array('id' => $id );

		//menghapus data dari database sesuai id
		$this->m_data->delete_data('buku', $where);

		//mengalihkan halaman ke halaman data buku
		redirect('petugas/buku');
	}
	//Akhir crud buku

	//Proses Transaksi

	public function peminjaman()
	{
		// mengambil data peminjaman buku dari database | 
		// dan mengurutkan data dari id peminjaman terbesar ke terkecil (desc)

		$data['peminjaman'] = $this->db->query("SELECT * FROM peminjaman,buku,anggota WHERE 
			peminjaman.peminjaman_buku=buku.id AND 
			peminjaman.peminjaman_anggota=anggota.id order by peminjaman_id desc")->result();
		//ini viewnya
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_peminjaman',$data);
		$this->load->view('petugas/v_footer');
	}

	function peminjaman_tambah()
	{
		//mengambil data buku berstatus 1 (tersedia) dari database
		$where = array('status' => 1 );
		$data['buku'] = $this->m_data->edit_data($where, 'buku')->result();

		//mengambil data anggota
		$data['anggota'] = $this->m_data->get_data('anggota')->result()	;
		//ini view tambah pinjam
		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_peminjaman_tambah',$data);
		$this->load->view('petugas/v_footer');
	}
	function peminjaman_aksi()
	{

	//# ini isian untuk table peminjaman
		//menampung data dari elemen input
		$buku = $this->input->post('buku');
		$anggota = $this->input->post('anggota');
		$tanggal_mulai = $this->input->post('tanggal_mulai');
		$tanggal_sampai = $this->input->post('tanggal_sampai');

		//data ditampunng di array
		$data = array(
			'peminjaman_buku' => $buku,
			'peminjaman_anggota' => $anggota,
			'peminjaman_tanggal_mulai' => $tanggal_mulai,
			'peminjaman_tanggal_sampai' => $tanggal_sampai,
			'peminjaman_status' => 2
		);

		// insert data ke database
		$this->m_data->insert_data($data,'peminjaman');

		//# mengubah status buku menjadi di pinjam (2) sesuai id di table buku
		// X $w = array('id' => $id );
				// kesalahanya : asal ketik variable id tanpa tahu asalnya
				// penyelesaian : menelusuri -> $buku itu menangkap value dari elemnet select option name = " buku" di v_peminjaman_tambah. valuenya adalah id dari buku walaupunn yang muncul adalag h

		$w = array('id' => $buku );

		$d  = array('status' => 2);

		$this->m_data->update_data($w, $d, 'buku');

		//mengalihkan ke halaman data peminjaman
		redirect(base_url('petugas/peminjaman'));
	}

	function peminjaman_batalkan($id)
	{
		//menampung id peminjaman
		$where = array('peminjaman_id' => $id );

		//mengambil data buku pada peminjaman ber id tersebut
		$data = $this->m_data->edit_data($where, 'peminjaman')->row();
		$buku = $data->peminjaman_buku;

		//mengembalikan status buku menjadi tersedia (1)
		$w  = array('id' => $buku );

		$d  = array('status' => 1 );

		//update data ke database
		$this->m_data->update_data($w, $d, 'buku');

		//mengahapus data peminjaman dari database sesuai id
		$this->m_data->delete_data( 'peminjaman', $where);

		//mengalihkan ke halaman peminjaman buku
		redirect(base_url('petugas/peminjaman'));

	}

	function peminjaman_selesai($id)
	{
		//menampung id untuk update status peminjaman
		//  $where = array('nama_kolom' => $id );
		$where = array('peminjaman_id' => $id );

		//mengambil data buku pada peminjaman ber id tersebut
		$data = $this->m_data->edit_data($where, 'peminjaman')->row();
		$buku = $data->peminjaman_buku; //ini nampung id untuk ubah status buku yang ambil dari data peminjaman

		//mengembalikan status buku kembali ke tersedia (1)
		$w = array('id' => $buku );
		$d = array('status' => 1 );
		$this->m_data->update_data($w, $d, 'buku');

		//mengubah status peminjaman menjadi selesai (1)
		$this->m_data->update_data($where, array('peminjaman_status'=>1), 'peminjaman');

		//mengalihkan ke halaman data buku
		redirect(base_url('petugas/peminjaman'));
	}

	function peminjaman_laporan()
	{
		if (isset($_GET['tanggal_mulai']) && isset($_GET['tanggal_sampai'])) 
		{

			$mulai = $this->input->get('tanggal_mulai');
			$sampai = $this->input->get('tanggal_sampai');

		// mengambil data peminjaman berdasarkan tanggal mulai sampai tanggal sampai

			$data['peminjaman'] = $this->db->query("SELECT * FROM
				peminjaman,buku,anggota 
				WHERE peminjaman.peminjaman_buku=buku.id 
				AND peminjaman.peminjaman_anggota=anggota.id 
				AND date(peminjaman_tanggal_mulai) >='$mulai' 
				AND date(peminjaman_tanggal_mulai) <= '$sampai' 
				ORDER BY peminjaman_id DESC")->result();
		}else{
			// mengambil data peminjaman buku dari database | dan mengurutkan data dari id peminjaman terbesar ke terkecil (desc)

			$data['peminjaman'] = $this->db->query("SELECT * FROM peminjaman,buku,anggota WHERE peminjaman.peminjaman_buku=buku.id 
				AND peminjaman.peminjaman_anggota=anggota.id 
				ORDER BY peminjaman_id DESC")->result();
		}

		$this->load->view('petugas/v_header');
		$this->load->view('petugas/v_peminjaman_laporan',$data);
		$this->load->view('petugas/v_footer');
	}


	public function peminjaman_cetak()
	{
		if (isset($_GET['tanggal_mulai']) && isset($_GET['tanggal_sampai'])) 
		{
			$mulai = $this->input->get('tanggal_mulai');
			$sampai= $this->input->get('tanggal_sampai');

			//mengambil data peminjaman berdasarkan tanggal mulai dan tanggal sampai
			$data['peminjaman']	=  $this->db->query("select * from
				peminjaman,buku,anggota where peminjaman.peminjaman_buku=buku.id and
				peminjaman.peminjaman_anggota=anggota.id and date(peminjaman_tanggal_mulai) >=
				'$mulai' and date(peminjaman_tanggal_mulai) <= '$sampai' order by peminjaman_id
				desc")->result();
			$this->load->view('petugas/v_peminjaman_cetak',$data);
		}else{
			redirect(base_url('petugas/peminjaman'));
		}
	}

}

/* End of file Petugas.php */
/* Location: ./application/controllers/Petugas.php */