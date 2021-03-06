<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kelas_m extends CI_Model {

  function load_data($post)
  {
    $ta = $this->fungsi->ta()->id_ta;
    $this->datatables->from('kelas');
    $this->datatables->where('id_ta_kelas', $ta);
    // Filter
    if ($post['nama'] != null) {
      $this->datatables->like('nama_kelas', $post['nama']);
    }

    return $this->datatables->generate();
  }

  function tambah($post)
  {
    $ta = $this->fungsi->ta()->id_ta;
    $this->db->insert('kelas', [
      'id_ta_kelas' => $ta,
      'nama_kelas' => $post['nama'],
    ]);
  }

  function edit($post)
  {
    $this->db->where('id_kelas', $post['id']);
    $this->db->update('kelas', [
      'nama_kelas' => $post['nama'],
    ]);
  }

  function hapus($id)
  {
    $this->db->where('id_kelas', $id);
    $this->db->delete('kelas');
  }

  function cek_data($where, $limit)
  {
    $query = $this->db->get_where('kelas', $where, $limit);
    if($query->num_rows() > 0){
      return $query->result();
    }else{
      return false;
    }
  }


}
