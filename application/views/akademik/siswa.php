<script>
    var save_method; //for save method string
    var table;
    $(document).ready(function() {

        $(".calendar").flatpickr({
            dropdownParent: $('#roleModal')
        });

        // datatables
        table = $('#myTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [
                [2, 'asc']
            ],
            "ordering": true,
            "aLengthMenu": [
                [5, 25, 75, -1],
                [5, 25, 75, "All"]
            ],
            "iDisplayLength": 5,
            "ajax": {
                "url": "<?= base_url('akademik/Siswa/loadData') ?>",
                "type": "POST"
            },
            // "columnDefs": [{
            //     "targets": [-1],
            //     "orderable": false
            // }],
            "columns": [{
                    "data": 'id',
                    "className": 'text-center',
                    "sortable": false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "nis"
                },
                {
                    "data": "nama"
                }, {
                    "data": "tempat_lahir",
                    "sortable": false
                },
                {
                    "data": "tanggal_lahir",
                    "sortable": false
                },
                {
                    "data": "null",
                    "className": 'text-center',
                    "render": function(data, type, row, meta) {
                        return '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit" onclick=\'edit_siswa("' + row.id + '");\'><i class="bi bi-pencil-fill"></i> Edit</a>' + '&nbsp;&nbsp;&nbsp;' + '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick=\'delete_siswa("' + row.id + '");\'><i class="bi bi bi-trash"></i> Delete</a>';
                        // return '<a href="show/' + data + '">Show</a>';
                    }
                },
            ],
        });

    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function add_role() {
        save_method = 'add';
        $('.modal-title').text('Tambah Data Siswa');
        $('.form-group').removeClass('has-error');
        $('#roleModal').modal('show');
        $('#form')[0].reset();
    }


    function edit_siswa(id) {
        save_method = 'update';

        $('.form-group').removeClass('has-error');

        $('#form')[0].reset();

        $.ajax({
            type: "GET",
            url: "<?= base_url('akademik/Siswa/get') ?>/" + id,
            dataType: "json",
            success: function(data) {
                $('[name = "id"]').val(data.id);
                $('#nama').val(data.nama);
                $('#nis').val(data.nis);
                $('#agama').val(data.agama);
                $('#tempat_lahir').val(data.tempat_lahir);
                $('#tanggal_lahir').val(data.tanggal_lahir);
                $('#jenis_kelamin').val(data.jenis_kelamin);
                $('#alamat').val(data.alamat);
                $('#kel_des').val(data.kelurahan);
                $('#kecamatan').val(data.kecamatan);
                $('#kab_kota').val(data.kabupaten);
                $('#provinsi').val(data.provinsi);
                $('#kode_pos').val(data.kode_pos);
                $('#ayah').val(data.ayah);
                $('#pekerjaan_ayah').val(data.pekerjaan_ayah);
                $('#no_telp_ayah').val(data.no_telp_ayah);
                $('#ibu').val(data.ibu);
                $('#pekerjaan_ibu').val(data.pekerjaan_ibu);
                $('#no_telp_ibu').val(data.no_telp_ibu);
                $('#wali').val(data.wali);
                $('#pekerjaan_wali').val(data.pekerjaan_wali);
                $('#no_telp_wali').val(data.no_telp_wali);
                if (data.is_active == 1) {
                    $('#is_active').prop('checked', true);
                } else {
                    $('#is_active').prop('checked', false);
                }

                $('#roleModal').modal('show');
                $('.modal-title').text('Edit Siswa');
            }
        });


    }

    function delete_siswa(id) {
        Swal.fire({
            // title: 'Are you sure?',
            text: "Data Siswa akan dihapus !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('akademik/Siswa/delete') ?>",
                    data: ({
                        id
                    }),
                    dataType: "JSON",
                    success: function(data) {
                        //if success reload ajax table
                        Swal.fire(
                            'Deleted!',
                            data.message,
                            'success'
                        )
                        $('#roleModal').modal('hide');
                        reload_table();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                        })
                    }
                });

            }
        })




    }



    function save() {
        $('#btnSave').text('saving...');
        $('#btnSave').attr('disabled', true);

        var url;

        if (save_method == 'add') {
            url = "<?= base_url('akademik/Siswa/add') ?>";
        } else {
            url = "<?= base_url('akademik/Siswa/update') ?>";
        }

        $.ajax({
            type: "POST",
            url: url,
            data: $('#form').serialize(),
            dataType: "json",
            success: function(data) {

                if (data.error) {
                    if (data.nama_error != '') {
                        $('#nama_error').html(data.nama_error);
                        $('#btnSave').attr('disabled', false);
                    } else {
                        $('#nama_error').html('');
                    }
                    if (data.nis_error != '') {
                        $('#nis_error').html(data.nis_error);
                        $('#btnSave').attr('disabled', false);
                    } else {
                        $('#nis_error').html('');
                    }
                }

                if (data.status) {
                    $('#kode_error').html('');
                    $('#nama_error').html('');
                    $('#form')[0].reset();
                    $('#roleModal').modal('hide');
                    Swal.fire(
                        'Berhasil!',
                        data.message,
                        'success'
                    )
                    reload_table();
                }

                $('#btnSave').text('save');
                $('#btnSave').attr('disabled', false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong!',
                })
                $('#btnSave').text('save');
                $('#btnSave').attr('disabled', false);
            }
        });

    }
</script>

<main id="main" class="main">

    <div class="pagetitle">
        <h1><?= $title ?></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active"><?= $title ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section" id="front">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Siswa</h5>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-primary mb-3"><i class="bi bi-plus-square"></i> Add Data</button>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-success mb-3"><i class="bx bxs-file-import"></i> Import Excel</button>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-warning mb-3"><i class="ri-file-excel-line"></i> Export Excel</button>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-danger mb-3"><i class="bx bxs-file-pdf"></i> Export PDF</button>
                        <table id="myTable" width="100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">NIS</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Tempat Lahir</th>
                                    <th scope="col">Tanggal Lahir</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" data-bs-focus="false" id="roleModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Basic Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="form">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-5">
                                    <label for="nama" class="col-form-label"><span class="text-danger">*</span>Nama Lengkap</label>
                                    <input type="hidden" value="" id="id" name="id" />
                                    <input type="text" name="nama" id="nama" class="form-control" placeholder="" required>
                                    <small class="text-danger" id="nama_error"></small>
                                </div>
                                <div class="col-lg-3">
                                    <label for="nis" class="col-form-label">NIS (Nomor Induk Siswa)</label>
                                    <input type="text" name="nis" id="nis" class="form-control" placeholder="">
                                    <small class="text-danger" id="nis_error"></small>
                                </div>
                                <div class="col-lg-4">
                                    <label for="jenis_kelamin" class="col-form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" class="form-select" id="jenis_kelamin">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="agama" class="col-form-label">Agama</label>
                                    <select name="agama" class="form-select" id="agama">
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                        <option value="Konghucu">Konghucu</option>
                                        <option value="Lain-lain">Lain-lain</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label for="tempat_lahir" class="col-form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <!-- <label for="tanggal_lahir" class="col-form-label">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" placeholder=""> -->
                                    <label for="tanggal_lahir" class="col-form-label">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control calendar" name="tanggal_lahir" id="tanggal_lahir" />
                                        <span class="input-group-append">
                                            <span class="input-group-text bg-light d-block">
                                                <i class="bi bi-calendar3"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="alamat" class="col-form-label">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-3">
                                    <label for="kel_des" class="col-form-label">Desa/Kelurahan</label>
                                    <input type="text" name="kel_des" id="kel_des" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-3">
                                    <label for="kecamatan" class="col-form-label">Kecamatan</label>
                                    <input type="text" name="kecamatan" id="kecamatan" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="kab_kota" class="col-form-label">Kabupaten/kota</label>
                                    <input type="text" name="kab_kota" id="kab_kota" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="provinsi" class="col-form-label">Provinsi</label>
                                    <input type="text" name="provinsi" id="provinsi" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="kode_pos" class="col-form-label">Kode Pos</label>
                                    <input type="text" name="kode_pos" id="kode_pos" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="ayah" class="col-form-label">Ayah</label>
                                    <input type="text" name="ayah" id="ayah" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="pekerjaan_ayah" class="col-form-label">Pekerjaan Ayah</label>
                                    <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="no_telp_ayah" class="col-form-label">No. Telpon Ayah</label>
                                    <input type="text" name="no_telp_ayah" id="no_telp_ayah" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="ibu" class="col-form-label">Ibu</label>
                                    <input type="text" name="ibu" id="ibu" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="pekerjaan_ibu" class="col-form-label">Pekerjaan Ibu</label>
                                    <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="no_telp_ibu" class="col-form-label">No. Telpon Ibu</label>
                                    <input type="text" name="no_telp_ibu" id="no_telp_ibu" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="wali" class="col-form-label">Wali</label>
                                    <input type="text" name="wali" id="wali" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="pekerjaan_wali" class="col-form-label">Pekerjaan Wali</label>
                                    <input type="text" name="pekerjaan_wali" id="pekerjaan_wali" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="no_telp_wali" class="col-form-label">No. Telpon Wali</label>
                                    <input type="text" name="no_telp_wali" id="no_telp_wali" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row mb-3 mt-3">
                                <div class="col-lg-12">
                                    <div class="form-check">
                                        <input class="form-check-input" checked value="1" type="checkbox" id="is_active" name="is_active">
                                        <label class="form-check-label" for="gridCheck1">
                                            Aktif ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div><!-- End Basic Modal-->

</main><!-- End #main -->