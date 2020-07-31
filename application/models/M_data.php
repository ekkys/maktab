<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//Model ini dibuat dengan seefisien mungkin dengan meminimalisir pengulangan kode seperti saran malasngoding.com

class M_data extends CI_Model {

	//FUNGSI CRUD

	//fungsi untuk mengambil data dari database
	function get_data($table)
	{
		return $this->db->get($table);
	}

    //fungsi untuk menginput data ke database
	function insert_data($data, $table)
	{
		$this->db->insert($table, $data);
	}

	//fungsi untuk mengedit data #ini untuk munculin data yang mau diedit
	function edit_data($where, $table)
	{
		return $this->db->get_where($table, $where);
	}

	//fungsi untuk mengupdate atau mengubah data di database
	function update_data($where, $data, $table)
	{
		$this->db->where($where);
		$this->db->update($table, $data);
	}

	//fungsi untuk menghapus data dari database
	function delete_data($table, $where)
	{
		$this->db->delete($table, $where);
	}

	function cek_login($table,$where){
		return $this->db->get_where($table,$where);
	}

	// AKHIR FUNGSI CRUD

}

/* End of file M_data.php */
/* Location: ./application/models/M_data.php */