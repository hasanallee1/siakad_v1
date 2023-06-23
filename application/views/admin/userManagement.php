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
                "url": "<?= base_url('admin/loadDataUser') ?>",
                "type": "POST"
            },
            "columns": [{
                    "data": 'id',
                    "className": 'text-center',
                    // "sortable": false,
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    "data": "name"
                },
                {
                    "data": "email"
                },
                {
                    "data": "role",
                    "sortable": false,
                },
                {
                    "data": "is_active",
                    "className": 'text-center',
                    "sortable": false,
                    "render": function(data) {
                        if (data == 1) {
                            return '<span class="badge bg-info"><i class="ri-checkbox-circle-line"></i> Active</span>';
                        } else {
                            return '<span class="badge bg-danger"><i class="ri-close-circle-line"></i> Not Active</span>';
                        }
                    }
                },
                {
                    "data": "null",
                    "className": 'text-center',
                    "sortable": false,
                    "render": function(data, type, row, meta) {
                        return '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit" onclick=\'edit_user("' + row.id + '");\'><i class="bi bi-pencil-fill"></i> Edit</a>' + '&nbsp;&nbsp;&nbsp;' + '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick=\'delete_user("' + row.id + '");\'><i class="bi bi bi-trash"></i> Delete</a>';
                        // return '<a href="show/' + data + '">Show</a>';
                    }
                },
            ],
        });



        $('#formInput').hide();

    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function add_user() {
        save_method = 'add';
        $('#roleModal').modal('show');
        $('.modal-title').text('Tambah User');
        $('.form-group').removeClass('has-error');
        $('#form')[0].reset();
    }

    function edit_user(id) {
        save_method = 'update';

        $('.form-group').removeClass('has-error');

        $('#form')[0].reset();

        $.ajax({
            type: "GET",
            url: "<?= base_url('admin/getUser') ?>/" + id,
            dataType: "json",
            success: function(data) {
                $('[name = "id"]').val(data.id);
                $('#nama').val(data.name);
                $('#email').val(data.email);
                $('#role').val(data.role_id);
                if (data.is_active == 1) {
                    $('#is_active').prop('checked', true);
                } else {
                    $('#is_active').prop('checked', false);
                }

                $('#roleModal').modal('show');
                $('#btnSave').text('Update');
                $('.modal-title').text('Edit User');
            }
        });


    }

    function delete_user(id) {
        Swal.fire({
            // title: 'Are you sure?',
            text: "Data user akan dihapus !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('admin/deleteUser') ?>",
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


        var url;

        if (save_method == 'add') {
            url = "<?= base_url('admin/addUser') ?>";
        } else {
            url = "<?= base_url('admin/updateUser') ?>";
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

                    if (data.email_error != '') {
                        $('#email_error').html(data.email_error);
                        $('#btnSave').attr('disabled', false);
                    } else {
                        $('#email_error').html('');
                    }

                    if (data.password_error != '') {
                        $('#password_error').html(data.password_error);
                        $('#btnSave').attr('disabled', false);
                    } else {
                        $('#password_error').html('');
                    }
                }

                if (data.status) {
                    Swal.fire(
                        'Berhasil!',
                        data.message,
                        'success'
                    )

                    $('#nama_error').html('');
                    $('#email_error').html('');
                    $('#password_error').html('');

                    $('#roleModal').modal('hide');
                    $('#form')[0].reset();
                    $('.form-group').removeClass('has-error');
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
                        <h5 class="card-title">Data User</h5>
                        <button type="button" id="add_data" onclick="add_user()" class="btn btn-sm btn-primary mb-3"><i class="bi bi-plus-square"></i> Add Data</button>
                        <table id="myTable" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Active</th>
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
                    <form action="#" id="form" class="row g-3">
                        <div class="form-group">
                            <div class="row mb-1">
                                <div class="col-lg-9">
                                    <label for="nama" class="col-form-label">Nama</label>
                                    <input type="hidden" value="" id="id" name="id" />
                                    <input type="text" name="nama" id="nama" class="form-control">
                                    <small class="text-danger" id="nama_error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row mb-1">
                                <div class="col-lg-9">
                                    <label for="email" class="col-form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                    <small class="text-danger" id="email_error"></small>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div id="pilRole" class="col-lg-9">
                                    <label class="col-form-label">Pilih Role</label>
                                    <select class="form-select" id="role" name="role" required aria-label="Default select example">
                                        <option value=""></option>
                                        <?php foreach ($userRole as $r) : ?>
                                            <option value="<?= $r->id ?>"><?= $r->role ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="password1" class="col-form-label">Password</label>
                                    <input type="password" name="password1" id="password1" class="form-control">
                                    <small class="text-danger" id="password_error"></small>
                                </div>
                                <div class="col-lg-6">
                                    <label for="password2" class="col-form-label">Ulangi Password</label>
                                    <input type="password" name="password2" id="password2" class="form-control">
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