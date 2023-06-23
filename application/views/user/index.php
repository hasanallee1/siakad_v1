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

                        if (data.image_error != '') {
                            $('#image_error').html(data.image_error);
                            $('#btnSave').attr('disabled', false);
                        } else {
                            $('#image_error').html('');
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
                                <form id="form" enctype="multipart/form-data" method="POST">
                                    <div class="row mb-3">
                                        <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img src="<?= base_url('assets/img/') . $user['image'] ?>" alt="Profile">
                                            <div class="pt-2">
                                                <input class="form-control" name="image" type="file" id="image">
                                                <small class="text-danger" id="image_error"></small>
                                            </div>
                                        </div>
                                    </div>

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
                                <form>

                                    <div class="row mb-3">
                                        <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="currentPassword" type="password" class="form-control" id="currentPassword">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword1" type="password" class="form-control" id="newpassword1">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="newpassword2" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="newpassword2" type="password" class="form-control" id="newpassword2">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" onclick="cekPassword()" class="btn btn-primary">Change Password</button>
                                    </div>
                                </form><!-- End Change Password Form -->

                            </div>

                        </div><!-- End Bordered Tabs -->

                    </div>
                </div>

            </div>
        </div>
    </section>

</main><!-- End #main -->