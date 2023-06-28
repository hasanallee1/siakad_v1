<script>
    var save_method; //for save method string
    var table;
    $(document).ready(function() {

        cek();
    });

    function cek() {
        var cek = <?= $cek ?>;
        if (cek == 0) {
            $('#cek_profil').show();
            $('#profil').hide();
        } else {
            $('#cek_profil').hide();
            $('#profil').show();
        }

    };


    function add_profile() {
        save_method = 'add';
        $('.modal-title').text('Tambah Profil Sekolah');
        $('.form-group').removeClass('has-error');
        $('#roleModal').modal('show');
        $('#form')[0].reset();
    }


    function edit_kelas() {
        save_method = 'update';

        var id = <?= $profil['id'] ?>;

        $('.form-group').removeClass('has-error');

        $('#form')[0].reset();

        $.ajax({
            type: "GET",
            url: "<?= base_url('akademik/ProfilSekolah/get') ?>/" + id,
            dataType: "json",
            success: function(data) {
                $('#id').val(data.id);
                $('#nama_sekolah').val(data.nama_sekolah);
                $('#npsn').val(data.npsn);
                $('#bentuk_sekolah').val(data.bentuk_sekolah);
                $('#alamat').val(data.alamat);
                $('#kel_des').val(data.desa_kelurahan);
                $('#kecamatan').val(data.kecamatan);
                $('#kab_kota').val(data.kabupaten_kota);
                $('#provinsi').val(data.provinsi);
                $('#kode_pos').val(data.kode_pos);
                $('#telp').val(data.telp);
                $('#email').val(data.email);
                $('#website').val(data.website);
                $('#roleModal').modal('show');
                $('.modal-title').text('Edit Profil Sekolah');
            }
        });


    }

    function upload_img() {
        $('.modal-title').text('Ubah Logo');
        $('.form-group').removeClass('has-error');
        $('#uploadModal').modal('show');
        $('#form')[0].reset();
    }

    function upload() {
        $('#formUpload').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url('akademik/ProfilSekolah/update_image') ?>",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: "json",
                success: function(data) {

                    if (data.error) {
                        $('#image_error').html(data.image_error);
                        $('#btnSaveUpload').attr('disabled', false);
                    } else {
                        $('#image_error').html('');
                    }

                    if (data.status) {
                        Swal.fire(
                            'Berhasil',
                            data.message,
                            'success'
                        )

                        location.reload();
                    }
                }
            });
        });
    }

    function delete_img() {
        var id = <?= $profil['id'] ?>;
        Swal.fire({
            // title: 'Are you sure?',
            text: "Logo akan dihapus !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "post",
                    url: "<?= base_url('akademik/ProfilSekolah/delete_image') ?>",
                    data: ({
                        id
                    }),
                    dataType: "json",
                    success: function(data) {
                        if (data.status) {
                            Swal.fire(
                                'Berhasil',
                                data.message,
                                'success'
                            )

                            location.reload();
                        }
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
            url = "<?= base_url('akademik/ProfilSekolah/add') ?>";
        } else {
            url = "<?= base_url('akademik/ProfilSekolah/update') ?>";
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
                    location.reload();
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



    <section class="section profil2" id="front">

        <div class="row" id="cek_profil">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-danger alert-dismissible fade show mt-4 col-lg-12" role="alert">
                            <i class="bi bi-exclamation-octagon me-1"></i>
                            Data profil sekolah belum dimasukkan <br>
                            <button type="button" class="btn btn-sm btn-primary mt-3" onclick="add_profile()"><i class="bi bi-plus-square"></i> Klik di sini untuk tambah data</button>
                            <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="profil">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="<?= base_url('assets/img/') . $profil['logo'] ?>" alt="Profile" class="rounded-circle">
                        <h2>Logo Sekolah</h2>
                        <div class="pt-2">
                            <button href="#" onclick="upload_img()" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></button>
                            <button href="#" onclick="delete_img()" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></button>
                            <!-- <input class="form-control" name="image" type="file" id="image">
                                            <small class="text-danger" id="image_error"></small> -->
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Detail</button>
                            </li>



                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">


                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Nama Sekolah</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['nama_sekolah'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">NPSN</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['npsn'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Alamat</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['alamat'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Desa/Kelurahan</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['desa_kelurahan'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Kecamatan</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['kecamatan'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Kabupaten/kota</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['kabupaten_kota'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Provinsi</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['provinsi'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Kode Pos</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['kode_pos'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Telpon</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['telp'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['email'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Website</div>
                                    <div class="col-lg-9 col-md-8"><?= $profil['website'] ?></div>
                                </div>

                                <div class="text-left">
                                    <button type="button" onclick="edit_kelas()" class="btn btn-sm btn-success"><i class='bi bi-pencil-fill'></i> Edit</button>
                                </div>

                            </div>


                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>

        </div>
    </section>


    <div class="modal fade" id="roleModal" tabindex="-1">
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
                                <div class="col-lg-4">
                                    <label for="nama_sekolah" class="col-form-label">Nama Sekolah</label>
                                    <input type="hidden" value="" id="id" name="id" />
                                    <input type="text" name="nama_sekolah" id="nama_sekolah" class="form-control" placeholder="">
                                    <small class="text-danger" id="nama_error"></small>
                                </div>
                                <div class="col-lg-4">
                                    <label for="npsn" class="col-form-label">NPSN</label>
                                    <input type="text" name="npsn" id="npsn" class="form-control" placeholder="">

                                </div>
                                <div class="col-lg-4">
                                    <label for="bentuk_sekolah" class="col-form-label">Bentuk Sekolah</label>
                                    <select name="bentuk_sekolah" class="form-select" id="bentuk_sekolah">
                                        <option value="swasta">Swasta</option>
                                        <option value="negeri">Negeri</option>
                                    </select>
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
                                    <label for="telp" class="col-form-label">Telpon</label>
                                    <input type="text" name="telp" id="telp" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="email" class="col-form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="">
                                </div>
                                <div class="col-lg-4">
                                    <label for="website" class="col-form-label">Website</label>
                                    <input type="text" name="website" id="website" class="form-control" placeholder="">
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

    <!-- modal upload image -->

    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Basic Modal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="formUpload" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <div class="row mb-1">
                                <div class="col-lg-12">
                                    <label for="upload" class="col-form-label">Pilih Photo</label>
                                    <input type="hidden" value="<?= $profil['id'] ?>" id="id_profil" name="id_profil" />
                                    <input class="form-control" name="image" type="file" id="image">
                                    <small class="text-danger" id="image_error"></small>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnSaveUpload" onclick="upload()" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div><!-- End Basic Modal-->

</main><!-- End #main -->