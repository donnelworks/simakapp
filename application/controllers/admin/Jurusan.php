<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurusan extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		tidak_login();
		tidak_login_ta();
		$this->load->model('admin/jurusan_m');
	}

	public function index()
	{
		$data['judul'] = 'Jurusan';
		$this->load->view('admin/jurusan', $data);
	}

  public function load_data()
  {
		$post = $this->input->post();
    header('Content-Type: application/json');
    echo $this->jurusan_m->load_data($post);
  }

	public function tambah()
  {
    $post = $this->input->post();
		$data = array ('sukses' => false, 'error' => array());

    $this->form_validation->set_rules('kode', 'Kode', 'trim|required|callback_kode');
    $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_error_delimiters('<span class="text-danger invalid-message">', '</span>');

		$this->form_validation->set_message('required', '{field} wajib diisi!');
		$this->form_validation->set_message('numeric', '{field} diisi dengan angka!');

		if ($this->form_validation->run()) {
			$this->jurusan_m->tambah($post);
			$data['sukses'] = true;
		} else {
			foreach ($post as $key => $value) {
			 	$data['error'][$key] = form_error($key);
			}
		}
		echo json_encode($data);
  }

	public function edit()
  {
    $post = $this->input->post();
		$data = array ('sukses' => false, 'error' => array());

    $this->form_validation->set_rules('kode', 'Kode', 'trim|required|callback_edit_kode');
    $this->form_validation->set_rules('nama', 'Nama', 'trim|required');
		$this->form_validation->set_error_delimiters('<span class="text-danger invalid-message">', '</span>');

		$this->form_validation->set_message('required', '{field} wajib diisi!');
		$this->form_validation->set_message('numeric', '{field} diisi dengan angka!');

		if ($this->form_validation->run()) {
			$this->jurusan_m->edit($post);
			$data['sukses'] = true;
		} else {
			foreach ($post as $key => $value) {
			 	$data['error'][$key] = form_error($key);
			}
		}
		echo json_encode($data);
  }

	public function hapus()
	{
		$id = $this->input->get('id');
		$this->jurusan_m->hapus($id);
	}

	public function kode($kode)
	{
		$ta = $this->fungsi->ta()->id_ta;
		$where = array ('kode_jurusan' => $kode, 'id_ta_jurusan' => $ta);
		$cek = $this->jurusan_m->cek_data($where, 1);
	  if ($cek){
		  $this->form_validation->set_message('kode', '{field} sudah terdaftar!');
		  return FALSE;
	  }else{
		  return TRUE;
	  }
  }

	public function edit_kode($kode)
	{
		$ta = $this->fungsi->ta()->id_ta;
		$id = $this->input->post('id');
		$where = array ('kode_jurusan' => $kode, 'id_ta_jurusan' => $ta, 'id_jurusan !=' => $id);
		$cek = $this->jurusan_m->cek_data($where, 1);
	  if ($cek){
		  $this->form_validation->set_message('edit_kode', '{field} sudah terdaftar!');
		  return FALSE;
	  }else{
		  return TRUE;
	  }
  }

}
