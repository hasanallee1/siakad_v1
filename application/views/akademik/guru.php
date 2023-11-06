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
                "url": "<?= base_url('akademik/Guru/loadData') ?>",
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
                    "data": "nip"
                },
                {
                    "data": "nama"
                }, {
                    "data": "jenis_kelamin",
                    "sortable": false
                },
                {
                    "data": "null",
                    "className": 'text-center',
                    "render": function(data, type, row, meta) {
                        return '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit" onclick=\'edit_guru("' + row.id + '");\'><i class="bi bi-pencil-fill"></i> Edit</a>' + '&nbsp;&nbsp;&nbsp;' + '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick=\'delete_guru("' + row.id + '");\'><i class="bi bi bi-trash"></i> Delete</a>';
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
        $('#kode_guru').prop('readonly', false);
        $('#roleModal').modal('show');
        $('#form')[0].reset();
    }


    function edit_guru(id) {
        save_method = 'update';

        $('.form-group').removeClass('has-error');

        $('#form')[0].reset();

        $.ajax({
            type: "GET",
            url: "<?= base_url('akademik/Guru/get') ?>/" + id,
            dataType: "json",
            success: function(data) {
                $('[name = "id"]').val(data.id);
                $('#nama').val(data.nama);
                $('#nip').val(data.nip);
                $('#tempat_lahir').val(data.tempat_lahir);
                $('#tanggal_lahir').val(data.tanggal_lahir);
                $('#jenis_kelamin').val(data.jenis_kelamin);
                $('#alamat').val(data.alamat);
                $('#gelar_depan').val(data.gelar_depan);
                $('#gelar_belakang').val(data.gelar_belakang);
                $('#telp').val(data.telp);
                $('#email').val(data.email);

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

    function delete_guru(id) {
        Swal.fire({
            // title: 'Are you sure?',
            text: "Data Guru akan dihapus !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('akademik/Guru/delete') ?>",
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
            url = "<?= base_url('akademik/Guru/add') ?>";
        } else {
            url = "<?= base_url('akademik/Guru/update') ?>";
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
                        <h5 class="card-title">Data Guru</h5>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-primary mb-3"><i class="bi bi-plus-square"></i> Add Data</button>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-success mb-3"><i class="bx bxs-file-import"></i> Import Excel</button>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-warning mb-3"><i class="ri-file-excel-line"></i> Export Excel</button>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-danger mb-3"><i class="bx bxs-file-pdf"></i> Export PDF</button>
                        <table id="myTable" width="100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Jenis Kelamin</th>
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
                                    <label for="nip" class="col-form-label">NIP</label>
                                    <input type="text" name="nip" id="nip" class="form-control" placeholder="">
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

                                <div class="col-lg-3">
                                    <label for="gelar_depan" class="col-form-label">Gelar Depan</label>
                                    <input type="text" name="gelar_depan" id="gelar_depan" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-3">
                                    <label for="gelar_belakang" class="col-form-label">Gelar Belakang</label>
                                    <input type="text" name="gelar_belakang" id="gelar_belakang" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-3">
                                    <label for="tempat_lahir" class="col-form-label">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-3">
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
                                    <label for="telp" class="col-form-label">Telpon</label>
                                    <input type="text" name="telp" id="telp" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-3">
                                    <label for="email" class="col-form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="">
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