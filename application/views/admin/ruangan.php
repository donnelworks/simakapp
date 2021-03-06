<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_part/header');
?>
<div class="content-wrapper">

  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-6">
          <h4 class="m-0"><?= $judul ?></h4>
          <p class="text-muted content-subtitle"><?= $this->fungsi->ta()->tahun_ta ?></p>
        </div>
        <div class="col-6 text-right">
          <!-- Tombol -->
          <button type="button" class="btn btn-rounded btn-teal d-none d-sm-inline btn-tambah">
            <i class="fas fa-plus"></i> Tambah Data
          </button>
          <!--  -->
          <button type="button" class="btn btn-rounded btn-teal d-inline d-sm-none dropdown-toggle" data-toggle="dropdown">
            <i class="fas fa-plus"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item btn-tambah" href="javascript:void(0)">Tambah Data</a>
            <!-- <div class="dropdown-divider"></div> -->
          </div>
          <!-- /Tombol -->
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <div class="row">
        <div class="col-12">
          <div class="card shadow">
            <!-- Filter -->
            <div class="card-body">
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group">
                    <input type="text" class="form-control" id="namaFilter" placeholder="Nama">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <select class="form-control select" id="rombelFilter" style="width: 100%;">
                      <option value="">Semua Rombel</option>
                      <?php foreach ($rombel as $key => $row) { ?>
                        <option value="<?= $row->id_rombel ?>"><?= $row->nama_rombel ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-teal btn-rounded" id="btnFilter"><i class="bx bx-filter-alt"></i> Filter</button>
                  <a href="javascript:void(0)" class="ml-2" id="resetFilter"><b>Reset</b></a>
                </div>
              </div>
            </div>

            <!-- Table List -->
            <div class="card-body p-0">
              <table id="tblData" class="table table-hover table-striped dt-responsive" style="width: 100%;">
                <thead>
                  <tr>
                    <th>Nama</th>
                    <th>Rombel</th>
                    <th>Dibuat</th>
                    <th></th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="mdlData">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-muted"></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <!-- Form Data -->
            <form id="formData" autocomplete="off">
              <div class="modal-body">
                <div class="row">
                  <input type="hidden" name="id" id="id">

                  <div class="col-md-12">
                    <div class="form-group form-nama">
                      <label>Nama</label>
                      <input type="text" name="nama" class="form-control" id="nama">
                    </div>
                    <div class="form-group form-rombel">
                      <label>Rombongan Belajar</label>
                      <select class="form-control select" name="rombel" id="rombel">
                        <option value="">Semua Rombel</option>
                        <?php foreach ($rombel as $key => $row) { ?>
                          <option value="<?= $row->id_rombel ?>"><?= $row->nama_rombel ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <a href="javascript:void(0)" data-dismiss="modal"><b>Batal</b></a>
                <button type="submit" class="btn btn-teal btn-rounded btn-loading" id="btnSimpan"> Simpan</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Konfirmasi -->
      <div class="modal fade" id="mdlKonfirm">
        <div class="modal-dialog modal-sm">
          <div class="modal-content">
            <div class="modal-body">
              <div class="row justify-content-center align-items-center">
                <input type="hidden" name="id" class="form-control" id="idKonfirm">
                <h5 class="text-center text-muted" id="titleKonfirm"></h5>
              </div>
            </div>
            <div class="modal-footer justify-content-between">
              <a href="javascript:void(0)" data-dismiss="modal"><b>Tidak</b></a>
              <span id="btnKonfirm"></span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
  <!-- /Main content -->

</div>

<?php $this->load->view('_part/footer'); ?>
<script type="text/javascript">
  $(document).ready(function(){

    var simpan;
    loadData();

    // Select2
    $('.select').select2();

    // Filter
    $('#btnFilter').click(function(){
      $('#tblData').DataTable().destroy();
      loadData();
    });

    // Reset Filter
    $('#resetFilter').click(function(){
      $('#namaFilter').val("");
      $('#rombelFilter').val("").trigger('change.select2');
      $('#tblData').DataTable().destroy();
      loadData();
    });

    // Tambah Data
    $('.btn-tambah').click(function(){
      simpan = "tambah";
      $('#mdlData').modal('show');
      $('.modal-title').text("Tambah Data");
      $('#formData').trigger('reset');
      $('#rombel').val("").trigger('change.select2');
      $('.form-control').removeClass('is-invalid');
      $('.invalid-message').remove();
      $('#mdlData').on('shown.bs.modal', function(){
        $('#nama').focus();
      });
    });

    // Edit Data
    $('#tblData').on('click', '.edit-data', function(){
      var id = $(this).data('id');
      var nama = $(this).data('nama');
      var rombel = $(this).data('rombel');
      $('#id').val(id);
      $('#nama').val(nama);
      $('#rombel').val(rombel).trigger('change.select2');

      simpan = "edit";
      $('#mdlData').modal('show');
      $('.modal-title').text("Edit Data");
      $('.form-control').removeClass('is-invalid');
      $('.invalid-message').remove();
    });

    // Submit Data
    $('#formData').submit(function(e){
      e.preventDefault();
      var url;
      if (simpan == "tambah") {
        url = "<?= site_url('admin/ruangan/tambah') ?>"
      } else {
        url = "<?= site_url('admin/ruangan/edit') ?>"
      }
      $.ajax({
        url: url,
        type: 'post',
        data: $('#formData').serialize(),
        dataType: 'json',
        beforeSend: function(){
          loadingBtnOn();
        },
        success: function(data){
          loadingBtnOff();
          if(data.sukses == true){
            alertSukses("Berhasil disimpan");
            reloadTable('#tblData');
            if(simpan == 'edit'){
              $('#mdlData').modal('hide');
            }
            $('#nama').focus();
            $('#formData').trigger('reset');
            $('.select').val("").trigger('change.select2');
            $('.form-control').removeClass('is-invalid');
            $('.invalid-message').remove();
          }else{
            alertError("Gagal disimpan");
            $.each(data.error,function(key, value){
              var element = $('[name="'+key+'"]');
              var form = $('.form-'+key+'');
              element.closest('.form-control')
              .removeClass('is-invalid')
              .addClass(value.length > 0 ? 'is-invalid' : '');
              element.closest('.form-group')
              .find('.invalid-message')
              .remove();
              form.append(value);
            });
          }
        }
      });
    });

    // Hapus Data
    $('#tblData').on('click', '.hapus-data', function(){
      var id = $(this).data('id');
      $('#idKonfirm').val(id);
      $('#mdlKonfirm').modal('show');
      $('#titleKonfirm').text('INGIN MENGHAPUS DATA?');
      $('#btnKonfirm').html('<button type="button" class="btn btn-danger btn-rounded btn-loading" id="btnKonfirmHapus"> Hapus</button>');
    });
    $('#mdlKonfirm').on('click','#btnKonfirmHapus',function(){
      var id = $('#idKonfirm').val();
      $.ajax({
        url: '<?= site_url('admin/ruangan/hapus') ?>',
        type: 'get',
        data: {id},
        beforeSend: function(){
          loadingBtnOn();
        },
        success: function(){
          loadingBtnOff();
          reloadTable('#tblData');
          alertSukses("Berhasil dihapus");
          $('#mdlKonfirm').modal('hide');
        }
      });
    });


  });

  function loadData()
  {
    var nama = $('#namaFilter').val();
    var rombel = $('#rombelFilter').val();

    $('#tblData').DataTable({
      ajax: {
        url: '<?= site_url('admin/ruangan/load_data') ?>',
        type: 'post',
        data: {nama, rombel}
      },
      columns:
      [
        {data: 'nama_ruangan'},
        {data: 'nama_rombel'},
        {data: 'buat_ruangan', render: function(data) {
          return tglJam(data);
        }},
        {data: "id_ruangan", orderable: false, searchable: false, render: function(data, type, row) {
          return `<div class="text-center">
                    <button type="button" class="btn btn-rounded btn-teal btn-sm dropdown-toggle" data-toggle="dropdown">
                      <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item edit-data" href="javascript:void(0)" data-id="`+row.id_ruangan+`" data-nama="`+row.nama_ruangan+`" data-rombel="`+row.id_rombel_ruangan+`"><i class="bx bx-edit"></i> Edit</a>
                      <a class="dropdown-item hapus-data" href="javascript:void(0)" data-id="`+row.id_ruangan+`"><i class="bx bx-trash"></i> Hapus</a>
                    </div>
                  </div>`;
        }},
      ],
      order: [[2, 'desc']],
      pageLength: 25,
      responsive: true,
      processing: true,
      serverSide: true,
      searching: false,
      lengthChange: false,
      oLanguage: {
        sProcessing: 'Mohon Tunggu...',
        sInfo: '_START_ s/d _END_ dari _TOTAL_ data',
        sEmptyTable: '<span class="text-teal">Tidak Ada Data</span>',
        sInfoEmpty: '0 data'
      },
    });
  }


</script>
</body>
</html>
