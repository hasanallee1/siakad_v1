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
                [10, 25, 75, -1],
                [10, 25, 75, "All"]
            ],
            "iDisplayLength": 10,
            "ajax": {
                "url": "<?= base_url('menu/loadDataSubMenu') ?>",
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
                    "data": "title"
                },
                {
                    "data": "menu"
                },
                {
                    "data": "url",
                    "sortable": false
                },
                {
                    "data": "icon",
                    "sortable": false
                },
                {
                    "data": "is_active",
                    "sortable": false,
                    "className": 'text-center',
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
                        return '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit" onclick=\'edit_subMenu("' + row.id + '");\'><i class="bi bi-pencil-fill"></i> Edit</a>' + '&nbsp;&nbsp;&nbsp;' + '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick=\'delete_menu("' + row.id + '");\'><i class="bi bi bi-trash"></i> Delete</a>';
                        // return '<a href="show/' + data + '">Show</a>';
                    }
                },
            ],
        });


    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    function add_subMenu() {
        save_method = 'add';
        $('.modal-title').text('Tambah Sub Menu');
        $('.form-group').removeClass('has-error');
        $('#menuModal').modal('show');
        $('#form')[0].reset();
    }

    function edit_subMenu(id) {
        save_method = 'update';

        $('.form-group').removeClass('has-error');

        $('#form')[0].reset();

        $.ajax({
            type: "GET",
            url: "<?= base_url('menu/getSubMenu') ?>/" + id,
            dataType: "json",
            success: function(data) {
                $('[name = "id"]').val(data.id);
                $('#title').val(data.title);
                $('#menu').val(data.menu_id);
                $('#sub_menu').val(data.sub_menu_id);
                $('#url').val(data.url);
                $('#ikon').val(data.icon);
                if (data.is_active == 1) {
                    $('#is_active').prop('checked', true);
                } else {
                    $('#is_active').prop('checked', false);
                }
                $('#menuModal').modal('show');
                $('.modal-title').text('Edit Menu');
            }
        });


    }

    function delete_subMenu(id) {
        if (confirm('Are you sure delete this data?')) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('menu/deleteSubMenu') ?>",
                data: ({
                    id
                }),
                dataType: "JSON",
                success: function(data) {
                    //if success reload ajax table
                    alert('Data Berhasil dihapus !');
                    $('#menuModal').modal('hide');
                    reload_table();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Error deleting data');
                }
            });
        }
    }



    function save() {


        var url;

        if (save_method == 'add') {
            url = "<?= base_url('menu/addSubMenu') ?>";
        } else {
            url = "<?= base_url('menu/updateSubMenu') ?>";
        }

        var id = document.getElementById('id').value;
        var title = document.getElementById('title').value;
        var menu = $('#menu').find(":selected").attr('value');
        var sub_menu = $('#sub_menu').find(":selected").attr('value');
        var url1 = document.getElementById('url').value;
        var ikon = document.getElementById('ikon').value;

        if (!sub_menu) {
            sub_menu = 0;
        }

        if ($('#is_active').prop('checked')) {
            var is_active = 1;
        } else {
            var is_active = 0;
        }

        if (!url) {
            Swal.fire({
                icon: 'error',
                // title: 'Oops...',
                text: 'Url tidak boleh kosong !',
            })
            return false;
        }

        if (!title) {
            Swal.fire({
                icon: 'error',
                // title: 'Oops...',
                text: 'Nama Sub Menu tidak boleh kosong !',
            })
            return false;
        }

        if (!menu) {
            Swal.fire({
                icon: 'error',
                // title: 'Oops...',
                text: 'Pilih menu terlebih dahulu !',
            })
            return false;
        }

        if (!ikon) {
            Swal.fire({
                icon: 'error',
                // title: 'Oops...',
                text: 'ikon tidak boleh kosong !',
            })
            return false;
        }



        // $('#btnSave').text('saving...');
        // $('#btnSave').attr('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: ({
                id,
                title,
                menu,
                sub_menu,
                url1,
                ikon,
                is_active
            }),
            dataType: "json",
            success: function(data) {

                if (data.status) {
                    Swal.fire(
                        'Berhasil!',
                        data.message,
                        'success'
                    )
                    $('#menuModal').modal('hide');
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

    <section class="section">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Data Sub Menu</h5>
                        <button type="button" id="add_data" onclick="add_subMenu()" class="btn btn-sm btn-primary mb-3"><i class="bi bi-plus-square"></i> Add Data</button>
                        <table id="myTable" class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Menu</th>
                                    <th scope="col">Url</th>
                                    <th scope="col">Icon</th>
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

    <div class="modal fade" id="menuModal" tabindex="-1">
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
                                <div class="col-lg-12">
                                    <label for="title" class="col-form-label">Judul</label>
                                    <input type="hidden" value="" id="id" name="id" />
                                    <input type="text" name="title" id="title" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div id="pilMenu" class="col-lg-12">
                                    <label class="col-form-label">Pilih Menu</label>
                                    <select class="form-select" id="menu" name="menu" aria-label="Default select example">
                                        <option value=""></option>
                                        <?php foreach ($menu as $m) : ?>
                                            <option value="<?= $m['id'] ?>"><?= $m['menu'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div id="pilMenu" class="col-lg-12">
                                    <label class="col-form-label">Pilih Sub Menu</label>
                                    <select class="form-select" id="sub_menu" name="sub_menu" aria-label="Default select example">
                                        <option value=""></option>
                                        <?php foreach ($sub_menu as $sm) : ?>
                                            <option value="<?= $sm['id'] ?>"><?= $sm['title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="url" class="col-sm-2 col-form-label">Url</label>
                                    <input type="text" name="url" id="url" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="url" class="col-sm-2 col-form-label">Ikon</label>
                                    <input type="text" name="ikon" id="ikon" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row mb-3 mt-3">
                                <div class="col-lg-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active">
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