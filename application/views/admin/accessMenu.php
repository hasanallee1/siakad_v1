<script>
    var save_method; //for save method string
    var table;

    $(document).ready(function() {

        var role_id = <?= $roleId['id'] ?>;
        // datatables
        table = $('#myTable').DataTable({
            "processing": true,

            "contentType": "application/json; charset=utf-8",
            "dataType": "json",
            "cache": false,
            "destroy": true,
            "responsive": true,
            "aLengthMenu": [
                [5, 25, 75, -1],
                [5, 25, 75, "All"]
            ],
            "ajax": {
                "url": "<?= base_url('admin/loadMenu/') ?>" + role_id,
            },
            // "columnDefs": [{
            //     "targets": [-1],
            //     "orderable": false
            // }],
            "columns": [{
                    "data": 'urut',
                },
                {
                    "data": "menu"
                },
                {
                    "data": "cek",
                    "render": function(o) {
                        var cekx = "";

                        if (o == '1') {
                            cekx = '<input class="form-check-input" type="checkbox" checked > ';
                        } else {
                            cekx = '<input class="form-check-input" type="checkbox"  > ';
                        }

                        return cekx;
                    }
                },
                {
                    "data": "subMenu",
                    "render": function(submenu) {
                        var smcek = "";

                        var str = '';
                        var xidm = '';
                        $.each(submenu, function(i, n) {
                            xidm = n['idMenu']
                            if (n['cek'] == "1") {
                                smcek = '<input class="form-check-input" type="checkbox" checked  onclick="cekSubMenu(' + n['idMenu'] + ',' + n['idSubMenu'] + ')"> ';
                            } else {
                                smcek = '<input class="form-check-input" type="checkbox"  onclick="cekSubMenu(' + n['idMenu'] + ',' + n['idSubMenu'] + ')"> ';
                            }

                            str += smcek + n['title'] + "<br>";
                        });

                        return str;
                    }
                },
            ],

        });




    });

    function reload_table() {
        table.ajax.reload(null, false); //reload datatable ajax 
    }


    function cekSubMenu(val1, val2) {

        var role = <?= $roleId['id'] ?>;
        var menuId = val1;
        var subMenuId = val2;

        $.ajax({
            type: "post",
            url: "<?= base_url('admin/saveAccess') ?>",
            data: ({
                role,
                menuId,
                subMenuId
            }),
            dataType: "json",
            success: function(data) {
                //if success reload ajax table
                Swal.fire(
                    'Berhasil !',
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
                        <h5 class="card-title">Role : <?= $roleId['role'] ?></h5>
                        <a type="button" href="<?= base_url('Admin/roleAccess') ?>" class="btn btn-sm btn-warning mb-3"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
                        <table id="myTable" width="100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Menu</th>
                                    <th scope="col">Access Menu</th>
                                    <th scope="col">Sub Menu</th>
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


</main><!-- End #main -->