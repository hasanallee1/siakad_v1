<script>
    var save_method; //for save method string
    var table;

    function save() {


        $('#form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url('user/updateUser') ?>",
                data: new FormData(this),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: "json",
                success: function(data) {

                    if (data.error) {
                        if (data.name_error != '') {
                            $('#name_error').html(data.name_error);
                            $('#btnSave').attr('disabled', false);
                        } else {
                            $('#name_error').html('');
                        }

                        if (data.email_error != '') {
                            $('#email_error').html(data.email_error);
                            $('#btnSave').attr('disabled', false);
                        } else {
                            $('#email_error').html('');
                        }
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

    function upload_img() {
        $('.modal-title').text('Ubah Foto');
        $('.form-group').removeClass('has-error');
        $('#roleModal').modal('show');
        $('#form')[0].reset();
    }

    function delete_img() {
        var id = $('#id').val();
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
                    type: "post",
                    url: "<?= base_url('user/delete_image') ?>",
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

    function upload() {
        $('#formUpload').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "<?= base_url('user/update_image') ?>",
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


    function changePassword() {
        var user_id = $('#user_id').val();
        var currentPassword = $('#currentPassword').val();
        var newpassword1 = $('#newpassword1').val();
        var newpassword2 = $('#newpassword2').val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('user/changePassword') ?>",
            data: ({
                user_id,
                currentPassword,
                newpassword1,
                newpassword2
            }),
            dataType: "json",
            success: function(data) {

                if (data.cekPassword) {
                    Swal.fire({
                        icon: 'error',
                        // title: 'Oops...',
                        text: data.message,
                    })
                }
                if (data.error) {
                    if (data.current_error != '') {
                        $('#current_error').html(data.current_error);

                    } else {
                        $('#current_error').html('');
                    }

                    if (data.password1_error != '') {
                        $('#password1_error').html(data.password1_error);
                    } else {
                        $('#password1_error').html('');
                    }

                    if (data.password2_error != '') {
                        $('#password2_error').html(data.password2_error);
                    } else {
                        $('#password2_error').html('');
                    }
                }

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

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">

                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                        <img src="<?= base_url('assets/img/') . $user['image'] ?>" alt="Profile" class="rounded-circle">
                        <h2><?= $user['name'] ?></h2>
                        <h3><?= $role['role'] ?></h3>
                    </div>
                </div>

            </div>

            <div class="col-xl-8">

                <div class="card">
                    <div class="card-body pt-3">
                        <!-- Bordered Tabs -->
                        <ul class="nav nav-tabs nav-tabs-bordered">

                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                            </li>


                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                            </li>

                        </ul>
                        <div class="tab-content pt-2">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">


                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                    <div class="col-lg-9 col-md-8"><?= $user['name'] ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8"><?= $user['email'] ?></div>
                                </div>

                            </div>

                            <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                <!-- Profile Edit Form -->
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                    <div class="col-md-8 col-lg-9">
                                        <img src="<?= base_url('assets/img/') . $user['image'] ?>" alt="Profile">
                                        <div class="pt-2">
                                            <button href="#" onclick="upload_img()" class="btn btn-primary btn-sm" title="Upload new profile image"><i class="bi bi-upload"></i></button>
                                            <button href="#" onclick="delete_img()" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="bi bi-trash"></i></button>
                                            <!-- <input class="form-control" name="image" type="file" id="image">
                                            <small class="text-danger" id="image_error"></small> -->
                                        </div>
                                    </div>
                                </div>
                                <form id="form" method="POST">

                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input type="hidden" id="id" name="id" value="<?= $user['id'] ?>">
                                            <input name="name" type="text" class="form-control" id="name" value="<?= $user['name'] ?>">
                                            <small class="text-danger" id="name_error"></small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" readonly type="email" class="form-control" id="email" value="<?= $user['email'] ?>">
                                            <small class="text-danger" id="email_error"></small>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" id="btnSave" onclick="save()" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form><!-- End Profile Edit Form -->

                            </div>

                            <div class="tab-pane fade pt-3" id="profile-change-password">
                                <!-- Change Password Form -->
                                <form id="formPassword" method="post">

                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="currentPassword" type="password" class="form-control" id="currentPassword">
                                            <input type="hidden" id="user_id" name="user_id" value="<?= $user['id'] ?>">
                                            <small class="text-danger" id="current_error"></small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword1" type="password" class="form-control" id="newpassword1">
                                            <small class="text-danger" id="password1_error"></small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newpassword2" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword2" type="password" class="form-control" id="newpassword2">
                                            <small class="text-danger" id="password2_error"></small>
                                        </div>
                                    </div>

                                </form>
                                <div class="text-center">
                                    <button type="button" onclick="changePassword()" class="btn btn-primary">Change Password</button>
                                </div>
                                <!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- modal upload image -->

    <div class="modal fade" id="roleModal" tabindex="-1">
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
                                    <label for="kode_kelas" class="col-form-label">Pilih Photo</label>
                                    <input type="hidden" value="<?= $user['id'] ?>" id="id_user" name="id_user" />
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