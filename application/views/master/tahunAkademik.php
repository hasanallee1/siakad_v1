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
                "url": "<?= base_url('akademik/tahunAkademik/loadData') ?>",
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
                    "data": "tahun_akademik"
                },
                {
                    "data": "is_active",
                    "render": function(data) {
                        if (data == 1) {
                            return '<span class="badge bg-info"><i class="ri-checkbox-circle-line"></i> Aktif</span>';
                        } else {
                            return '<span class="badge bg-danger"><i class="ri-close-circle-line"></i> Tidak Aktif</span>';
                        }
                    }
                },
                {
                    "data": "null",
                    "className": 'text-center',
                    "render": function(data, type, row, meta) {
                        return '<a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Delete" onclick=\'aktif("' + row.id + '");\' title="Aktifkan" ><i class="bi bi-check-circle"></i> Active</a>' + '&nbsp;&nbsp;&nbsp;' + '<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit" onclick=\'edit_TaAkademik("' + row.id + '");\'><i class="bi bi-pencil-fill"></i> Edit</a>' + '&nbsp;&nbsp;&nbsp;' + '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick=\'delete_TaAkademik("' + row.id + '");\'><i class="bi bi bi-trash"></i> Delete</a>';
                        // return '<a href="show/' + data + '">Show</a>';
                    }
                },
            ],
        });

    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }

    // function access_menu(id) {
    //     $('#front').hide();
    //     $('#roleMenu').show();
    // }

    function add_role() {
        save_method = 'add';
        $('.modal-title').text('Add Role');
        $('.form-group').removeClass('has-error');
        $('#roleModal').modal('show');
        $('#form')[0].reset();
    }

    function aktif(id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('akademik/tahunAkademik/aktif/') ?>" + id,
            // data: "data",
            dataType: "json",
            success: function(data) {
                Swal.fire(
                    'Aktif!',
                    data.message,
                    'success'
                )
                reload_table();
            }
        });
    }

    function edit_TaAkademik(id) {
        save_method = 'update';

        $('.form-group').removeClass('has-error');

        $('#form')[0].reset();

        $.ajax({
            type: "GET",
            url: "<?= base_url('akademik/tahunAkademik/getTaAkademik') ?>/" + id,
            dataType: "json",
            success: function(data) {
                $('[name = "id"]').val(data.id);
                $('[name = "tahun"]').val(data.tahun_akademik);
                $('#roleModal').modal('show');
                $('.modal-title').text('Edit Role');
            }
        });


    }

    function delete_TaAkademik(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('akademik/TahunAkademik/deleteTaAkademik') ?>",
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
            url = "<?= base_url('akademik/TahunAkademik/addTaAkademik') ?>";
        } else {
            url = "<?= base_url('akademik/TahunAkademik/updateTaAkademik') ?>";
        }

        var id = document.getElementById('id').value;
        var tahun = document.getElementById('tahun').value;

        $.ajax({
            type: "POST",
            url: url,
            data: ({
                id,
                tahun
            }),
            dataType: "json",
            success: function(data) {

                if (data.status) {
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
                        <h5 class="card-title">Data Tahun Akademik</h5>
                        <button type="button" id="add_data" onclick="add_role()" class="btn btn-sm btn-primary mb-3"><i class="bi bi-plus-square"></i> Add Data</button>
                        <table id="myTable" width="100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tahun Akademik</th>
                                    <th scope="col">Status</th>
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

    <section class="section" id="roleMenu">

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
                            <div class="row mb-3">
                                <label for="tahun" class="col-sm-2 col-form-label">Tahun</label>
                                <div class="col-sm-10">
                                    <input type="hidden" value="" id="id" name="id" />
                                    <input type="text" name="tahun" id="tahun" class="form-control" placeholder="Masukkan tahun akademik contoh : 2023/2024">
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