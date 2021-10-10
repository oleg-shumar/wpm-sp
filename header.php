<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280, initial-scale=0.1, shrink-to-fit=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4"/>
    <!-- Title -->
    <title><?= get_the_title(); ?></title>
    <!-- Favicon -->
    <link rel="icon" href="<?= plugin_dir_url( __FILE__ ); ?>assets/img/brand/favicon.png" type="image/x-icon"/>
    <!-- Icons css -->
    <link href="<?= plugin_dir_url( __FILE__ ); ?>assets/css/icons.css" rel="stylesheet">
    <!--  Right-sidemenu css -->
    <link href="<?= plugin_dir_url( __FILE__ ); ?>assets/plugins/sidebar/sidebar.css" rel="stylesheet">
    <!--  Custom Scroll bar-->
    <link href="<?= plugin_dir_url( __FILE__ ); ?>assets/plugins/mscrollbar/jquery.mCustomScrollbar.css" rel="stylesheet"/>
    <!--- Style css-->
    <link href="<?= plugin_dir_url( __FILE__ ); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?= plugin_dir_url( __FILE__ ); ?>assets/css/style-dark.css" rel="stylesheet">
    <!---Skinmodes css-->
    <link href="<?= plugin_dir_url( __FILE__ ); ?>assets/css/skin-modes.css" rel="stylesheet"/>
    <!--- Animations css-->
    <link href="<?= plugin_dir_url( __FILE__ ); ?>assets/css/animate.css" rel="stylesheet">
    <!--  Left-Sidebar css -->
    <link rel="stylesheet" href="<?= plugin_dir_url( __FILE__ ); ?>assets/css/closed-sidemenu.css">
    <style>
        div.sp-admin-overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 100%;
            z-index: 999999;
            background-color: #fff;
            padding-top: 2em;
            overflow: scroll;
            padding-bottom: 6em;
            min-width: 1200px;
        }

        div.sp-admin-container {
            max-width: calc(100%);
            margin: auto;
            margin-left: 260px;

        }

        .card {
            max-width: 100%;
        }

        label {
            margin-top: .5rem;
            font-weight: bold;
        }

        .tabulator-header-filter input {
            max-height: 20px;
            padding: 2px;
        }

        .tabulator .tabulator-footer .tabulator-page-size {
            padding-left: 15px !important;
            padding-right: 25px !important;
        }

        .side-menu__label, .side-menu__item {
            color: #486A81 !important;
        }

        .side-menu__item.active .side-menu__label {
            color: #3093BA !important;
        }

        .side-menu__item.active, .side-menu__label:hover, .side-menu__item:hover, .side-menu__item:focus {
            color: #3093BA !important;
        }

        .app-sidebar .slide .side-menu__item.active::before {
            background: #3093BA !important;
        }

        .slide:hover .side-menu__label, .slide:hover .angle, .slide:hover .side-menu__icon {
            color: #3093BA !important;
            fill: #3093BA !important;
        }

        .badge-primary {
            background-color: #90566A;
        }

        .wp-core-ui .button-primary {
            background-color: #90566A;
            border-color: #90566A;
        }

        .wp-core-ui .button-primary.focus, .wp-core-ui .button-primary.hover, .wp-core-ui .button-primary:focus, .wp-core-ui .button-primary:hover {
            background-color: #90566A;
            border-color: #90566A;
        }

        .btn-success {
            background-color: #F27AAA !important;
            border-color: #F27AAA !important;
        }

        .btn-success.focus, .btn-success:hover, .btn-success:focus {
            background-color: #F27AAA !important;
            border-color: #F27AAA !important;
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #90566A !important;
        }

        .btn-info, .btn-info:focus, .btn-info:hover {
            background-color: #90566A !important;
            border-color: #90566A !important;
            color: #fff !important;
        }

        .tabulator-cell a {
            color: #90566A !important;
        }

        .ckbox span:after {
            background-color: #90566A !important;
        }

        .bg-primary {
            background-color: #F27AAA !important;
        }

        .bg-info {
            background-color: #90566A !important;
        }
    </style>
</head>
<body class="main-body">
<!-- Loader -->
<div id="global-loader">
    <img src="<?= plugin_dir_url( __FILE__ ); ?>assets/img/loader.svg" class="loader-img" alt="Loader">
</div>
<!-- /Loader -->