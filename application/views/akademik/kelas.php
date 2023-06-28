<script>
    var save_method; //for save method string
    var table;
    $(document).ready(function() {
        // datatables
        table = $('#myTable').DataTable({
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "order": [
                [0, 'asc']
            ],
            "ordering": true,
            "aLengthMenu": [
                [5, 25, 75, -1],
                [5, 25, 75, "All"]
            ],
            "iDisplayLength": 5,
            "ajax": {
                "url": "<?= base_url('akademik/Kelas/loadData') ?>",
                "type": "POST"
            },
            // "columnDefs": [{
            //     "targets": [-1],
            //     "orderable": false
            // }],
            "columns": [{
                    "data": 'id',
                    "className": 'text-center',
                    // "sortable": false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "kode_tingkat"
                },
                {
                    "data": "kode_kelas"
                }, {
                    "data": "nama_kelas"
                },
                {
                    "data": "null",
                    "className": 'text-center',
                    "render": function(data, type, row, meta) {
                        return '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit" onclick=\'edit_kelas("' + row.id + '");\'><i class="bi bi-pencil-fill"></i> Edit</a>' + '&nbsp;&nbsp;&nbsp;' + '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick=\'delete_kelas("' + row.id + '");\'><i class="bi bi bi-trash"></i> Delete</a>';
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
        $('.modal-title').text('Tambah Kelas');
        $('.form-group').removeClass('has-error');
        $('#kode_kelas').prop('readonly', false);
        $('#roleModal').modal('show');
        $('#form')[0].reset();
    }


    function edit_kelas(id) {
        save_method = 'update';

        $('.form-group').removeClass('has-error');

        $('#form')[0].reset();

        $.ajax({
            type: "GET",
            url: "<?= base_url('akademik/Kelas/get') ?>/" + id,
            dataType: "json",
            success: function(data) {
                $('[name = "id"]').val(data.id);
                $('[name = "kode_kelas"]').val(data.kode_kelas);
                $('[name = "nama_kelas"]').val(data.nama_kelas);
                $('#tingkat').val(data.tingkat_id);
                $('#kode_kelas').prop('readonly', true);
                $('#roleModal').modal('show');
                $('.modal-title').text('Edit Kelas');
            }
        });


    }

    function delete_kelas(id) {
        Swal.fire({
            // title: 'Are you sure?',
            text: "Data kelas akan dihapus !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('akademik/Kelas/delete') ?>",
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
            url = "<?= base_url('akademik/Kelas/add') ?>";
        } else {
            url = "<?= base_url('akademik/Kelas/update') ?>";
        }

        var tingkat = $('#tingkat').find(':selected').attr('value');

        if (!tingkat) {
            Swal.fire({
                icon: 'error',
                // title: 'Oops...',
                text: 'PIlih tingkat kelas terlebih dahulu !',
            })
            return false;
        }

        $.ajax({
            type: "POST",
            url: url,
            data: $('#form').serialize(),
            dataType: "json",
            success: function(data) {

                if (data.error) {
                    if (data.kode_error != '') {
                        $('#kode_error').html(data.kode_error);
                        $('#btnSave').attr('disabled', false);
                    } else {
                        $('#kode_error').html('');
                    }

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
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active"><?= $title ?></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section" id="front">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Kelas</h5>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-primary mb-3"><i class="bi bi-plus-square"></i> Add Data</button>
                        <table id="myTable" width="100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tingkat</th>
                                    <th scope="col">Kode</th>
                                    <th scope="col">Kelas</th>
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


    <div class="modal fade" id="roleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Basic Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="form">
                        <div class="form-group">
                            <div class="row">
                                <div id="pilTingkat" class="col-lg-12">
                                    <label class="col-form-label">Pilih Tingkatan</label>
                                    <select class="form-select" id="tingkat" name="tingkat" aria-label="Default select example" required>
                                        <option value=""></option>
                                        <?php foreach ($tingkat as $tk) : ?>
                                            <option value="<?= $tk['id'] ?>"><?= $tk['kode_tingkat'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row mb-1">
                                <div class="col-lg-12">
                                    <label for="kode_kelas" class="col-form-label">Kode Kelas</label>
                                    <input type="hidden" value="" id="id" name="id" />
                                    <input type="text" name="kode_kelas" id="kode_kelas" class="form-control" placeholder="">
                                    <small class="text-danger" id="kode_error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row mb-1">
                                <div class="col-lg-12">
                                    <label for="nama_kelas" class="col-form-label">Nama Kelas</label>
                                    <input type="hidden" value="" id="id" name="id" />
                                    <input type="text" name="nama_kelas" id="nama_kelas" class="form-control" placeholder="">
                                    <small class="text-danger" id="nama_error"></small>
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