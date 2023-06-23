<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?= $title ?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?= base_url('assets/NiceAdmin') ?>/img/favicon.png" rel="icon">
    <link href="<?= base_url('assets/NiceAdmin') ?>/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="<?= base_url('assets/NiceAdmin') ?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/NiceAdmin') ?>/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?= base_url('assets/NiceAdmin') ?>/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/NiceAdmin') ?>/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?= base_url('assets/NiceAdmin') ?>/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?= base_url('assets/NiceAdmin') ?>/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?= base_url('assets/NiceAdmin') ?>/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- jquery -->
    <script type="text/javascript" src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>



    <!-- datatables -->

    <link href="<?= base_url('assets/datatables') ?>/datatables.min.css" rel="stylesheet" />
    <script src="<?= base_url('assets/datatables') ?>/datatables.min.js"></script>

    <!-- select 2 -->
    <link href="<?= base_url('assets') ?>/css/select2.min.css" rel="stylesheet" />
    <script src="<?= base_url('assets') ?>/js/select2.min.js"></script>


    <!-- sweetalert -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="<?= base_url('assets/sweetalert') ?>/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('assets/sweetalert') ?>/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('assets/sweetalert') ?>/sweetalert2.min.css">

    <!-- Template Main CSS File -->
    <link href="<?= base_url('assets/NiceAdmin') ?>/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: Mar 09 2023 with Bootstrap v5.2.3
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="<?= base_url('assets/NiceAdmin') ?>/img/logo.png" alt="">
                <span class="d-none d-lg-block">SiAkad</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <!-- <div class="d-flex align-items-center mt-2" style="padding-left: 20px;">
            <h5><span class="badge bg-info"><i class="bi bi-calendar-check"></i> <?= tahun_akademik('tahun_akademik') ?></span></h5>
        </div> -->

        <!-- 
        <div class="search-bar">
            <form class="search-form d-flex align-items-center" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div> -->
        <!-- End Search Bar -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">


                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="<?= base_url('assets/img/') . $user['image'] ?> " alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?= $user['name'] ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= $user['name'] ?></h6>
                            <span><?= $role['role'] ?></span>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('auth/logout') ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header><!-- End Header -->