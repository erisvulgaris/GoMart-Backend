<link rel="shortcut icon" href="<?= base_url($settings['logo']) ?>">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..90&display=swap" rel="stylesheet">
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="<?= base_url('/assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
<!-- iCheck -->
<link rel="stylesheet" href="<?= base_url('/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
<!-- JQVMap -->
<link rel="stylesheet" href="<?= base_url('/assets/plugins/jqvmap/jqvmap.min.css') ?>">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url('/assets/dist/css/adminlte.min.css') ?>">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= base_url('/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
<!-- summernote -->
<link rel="stylesheet" href="<?= base_url('/assets/plugins/summernote/summernote-bs4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/toastr/toastr.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/tag/tagsinput.css') ?>">
<link rel="stylesheet" href="<?= base_url('/assets/plugins/select2/css/select2.min.css') ?>">
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-rounded/css/uicons-thin-rounded.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-rounded/css/uicons-bold-rounded.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-straight/css/uicons-bold-straight.css'>
<link rel='stylesheet' href='https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-straight/css/uicons-thin-straight.css'>

<style>
  .dark-mode .select2-selection {
    background-color: #343a40 !important;
    border-color: #6c757d;
  }

  .content-wrapper {
    position: relative;
  }


  @media screen and (min-width: 1280px) {
    .content-wrapper:before {
      content: ' ';
      display: block;
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      opacity: 0.2;
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
    }
  }

  @media screen and (max-width: 480px) {
    .content-wrapper:before {
      content: ' ';
      display: flex;
      position: absolute;
      left: 0;
      top: 171px;
      width: 100%;
      height: 30%;
      opacity: 0.2;
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
    }
  }

  .text-sm .main-header .nav-link>.fi::before {
    font-size: 15px;
  }

  .text-underline {
    text-decoration: underline;
  }

  .content {
    position: relative;
  }



  /* Pagination container */
  .dataTables_wrapper .dataTables_paginate {
    padding-top: 10px;
  }

  /* Pagination button styles */
  .dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 8px 12px;
    margin: 0 4px;
    border-radius: 8px;
    border: 1px solid #00897B;
    /* Light border for each button */
    background-color: #ffffff;
    color: #374151;
    /* Dark gray text */
    font-size: 14px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background-color: #f3f4f6;
    /* Light hover background */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    /* Subtle shadow on hover */
  }

  /* Active/current pagination button */
  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background-color: #f0f5ff;
    /* Light blue background for active page */
    border-color: #2563eb;
    /* Blue border for active button */
    color: #2563eb;
    /* Blue text for active button */
    box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
    /* Subtle blue focus ring */
  }

  /* Disabled pagination buttons (prev/next on edge cases) */
  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    background-color: #f9fafb;
    border-color: #00897B;
    color: #9ca3af;
    cursor: not-allowed;
  }

  /* Pagination ellipsis */
  .dataTables_wrapper .dataTables_paginate .ellipsis {
    padding: 8px 16px;
    margin: 0 4px;
    color: #9ca3af;
  }

  /* Custom styling for prev/next arrows */


  .dataTables_wrapper .dataTables_paginate .paginate_button.previous:before {
    font-family: uicons-bold-rounded !important;
    font-style: normal;
    font-weight: normal !important;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    content: "\e0c6";
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.next:before {
    font-family: uicons-bold-rounded !important;
    font-style: normal;
    font-weight: normal !important;
    font-variant: normal;
    text-transform: none;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    content: "\e0cc";

  }

  .page-item.disabled .page-link,
  .page-item:first-child .page-link,
  .page-item:last-child .page-link {
    display: none;
  }

  .dataTables_wrapper .dataTables_filter {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-bottom: 10px;
  }

  .dataTables_wrapper .dt-buttons {
    margin-right: 10px;
  }

  .dt-button {
    padding: 8px 14px;
    margin-right: 8px;
    border-radius: 6px;
    background-color: #2563eb;
    color: white;
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
  }

  .dt-button:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
  }

  .dt-button:active {
    background-color: #1e40af;
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .dataTables_wrapper .dataTables_filter input {
    padding: 8px 12px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
    width: 200px;
    margin-left: 10px;
    transition: border-color 0.3s ease;
  }

  /* Search box input on focus */
  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: #2563eb;
    /* Blue border on focus */
    outline: none;
  }

  .dropdown-menu {
    min-width: 120px;
    /* Adjust as needed */
  }

  .btn-group .dropdown-menu:focus {
    display: block;
  }

  .primary-bprder,
  .primary-bprder:focus,
  .primary-bprder:hover {
    border-color: #00897B;
  }

  .main-footer {
    text-align: center;
  }

  .dataTables_filter {
    display: none !important;
  }

  .sidebar-search {
    position: fixed;
    z-index: 99;
    width: 235px
  }

  ::-webkit-scrollbar {
    width: 8px;
    height: 12px;
  }

  ::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, #00897B, #8bc34a);
    border-radius: 10px;
    border: 2px solid #f0f0f0;
    transition: background 0.3s ease;
  }


  ::-webkit-scrollbar-track {
    background: #e0e0e0;
    border-radius: 10px;
    margin: 2px;
  }

  html {
    scroll-behavior: smooth;
  }

  .nav-dropdowm-item {
    padding: .8rem 1.5rem !important;
  }

  .nav-link {
    display: flex;
    align-items: center;
  }

  .nav-link i {
    font-size: 1.2rem;
    line-height: 1;
    margin-right: 5px;
  }

  .nav-link span {
    line-height: 1;
    /* Ensures text aligns vertically with the icon */
  }

  .navbar-nav .nav-item {
    margin-inline: 5px !important;
  }

  .permission-not-allowed {
    width: 500px;
    height: 500px;
    margin-right: auto;
    margin-left: auto;
    display: block;
  }

  .go-back-btn {
    margin-right: auto;
    margin-left: auto;
    display: block;
  }

  #product-list li {
    margin: 10px 0;
    padding: 15px;
    background-color: #f4f4f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: grab;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s ease;
  }

  #product-list li:hover {
    background-color: #eaeaff;
  }

  #product-list li:active {
    cursor: grabbing;
    background-color: #cfd8ff;
  }

  /* Draggable icon */
  .drag-handle {
    font-size: 20px;
    color: #888;
    cursor: grab;
  }

  #map {
    height: 300px;
  }

  #description {
    font-family: Roboto;
    font-size: 15px;
    font-weight: 300;
  }

  #infowindow-content .title {
    font-weight: bold;
  }

  #infowindow-content {
    display: none;
  }

  #map #infowindow-content {
    display: inline;
  }

  .pac-card {
    background-color: #fff;
    border: 0;
    border-radius: 2px;
    box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
    margin: 10px;
    padding: 0 0.5em;
    font: 400 18px Roboto, Arial, sans-serif;
    overflow: hidden;
    font-family: Roboto;
    padding: 0;
  }

  #pac-container {
    padding-bottom: 12px;
    margin-right: 12px;
  }

  .pac-controls {
    display: inline-block;
    padding: 5px 11px;
  }

  .pac-controls label {
    font-family: Roboto;
    font-size: 13px;
    font-weight: 300;
  }





  #target {
    width: 345px;
  }

  .custom-dropzone {
    align-items: center;
    justify-content: center;
    background-color: #f0f0f0 !important;
    border: 2px dashed #cccccc !important;
    border-radius: 5px;
    color: #333;
    padding: 20px;
    font-size: 20px;
    position: relative;
    cursor: pointer;
    text-align: center;
  }

  .dropzone-clickable-area {
    text-align: center;
  }

  .dropzone-clickable-area .icon {
    font-size: 50px;
    color: #888;
  }

  /* Hide default Dropzone message */
  .dropzone .dz-message {
    display: none;
  }

  /* Custom preview for uploaded files */
  .custom-preview .dz-preview .dz-image img {
    width: 100px;
    height: auto;
  }

  .select2-container--default .select2-selection--multiple {
    border: 0;
    background-color: rgba(150, 150, 150, 0.1);
  }

  .select2-container--default.select2-container--focus .select2-selection--multiple {
    border: 0px;
  }

  .spin-icon {
    display: inline-block;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }

  .bb-1 {
    border-bottom: 1px solid #ccc;
    margin-bottom: 5px;
  }

  .btn-ai {
    font-size: 14px;
    font-weight: bold;
    color: white;
    padding: 5px 5px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    background: linear-gradient(45deg, #ff416c, #ff4b2b, #ff9a44, #2162ef);
    background-size: 400% 400%;
    animation: gradientAnimation 3s ease infinite;
  }

  @keyframes gradientAnimation {
    0% {
      background-position: 0% 50%;
    }

    50% {
      background-position: 100% 50%;
    }

    100% {
      background-position: 0% 50%;
    }
  }

  #avgOrderValueGauge {
    width: 100%;
  }

  .custom-form-control {
    border: 1px solid;
  }

  .nav-pills .nav-link {
    border-radius: 0;
    text-align: left;
  }

  .nav-pills .nav-link.active {
    background-color: #00897B;
    color: white;
  }

  .list-group-item {
    border: 1px solid #00897B;
    border-radius: 5px;
    background-color: #fff;
    color: #495057;
    padding: 11px 2px;
    text-align: center;
    font-size: 14px;
    margin-bottom: 13px;
  }

  .list-group-item:hover {
    background-color: #e9ecef;
    color: #000;
  }

  .list-group-item.active {
    background-color: #00897B;
    color: #fff;
    border-radius: 5px;
    border: 1px solid #00897B;
  }

  .tab-content {
    padding: 15px;
    background-color: white;
  }
</style>
<style>

.sb-brand-link {
    display: flex !important;
    align-items: center !important;
    gap: 10px;
    padding: 14px 16px !important;
    border-bottom: 2px solid rgba(255,255,255,.08) !important;
    position: relative;
    overflow: hidden;
}
.sb-brand-link::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 100%; height: 2px;
    background: linear-gradient(90deg, #00897B, #8bc34a, #00bcd4);
}
.sb-brand-img {
    width: 34px !important;
    height: 34px !important;
    border-radius: 8px !important;
    object-fit: cover;
    box-shadow: 0 2px 6px rgba(0,0,0,.25);
    flex-shrink: 0;
}
.sb-brand-text {
    font-size: 15px !important;
    font-weight: 700 !important;
    letter-spacing: .01em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.sb-sidebar {
    padding-top: 0 !important;
    overflow-x: hidden;
}

.sb-profile {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px 10px;
    margin: 10px 10px 4px;
    border-radius: 10px;
    background: rgba(255,255,255,.06);
}
.sidebar-light-primary .sb-profile,
.sidebar-light-success .sb-profile,
.sidebar-light-info .sb-profile,
.sidebar-light-warning .sb-profile,
.sidebar-light-danger .sb-profile,
.sidebar-light-teal .sb-profile {
    background: rgba(0,0,0,.04);
}
.sb-profile-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #00897B, #8bc34a);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
    flex-shrink: 0;
}
.sb-profile-name {
    font-size: 13px;
    font-weight: 700;
    line-height: 1.2;
}
.sb-profile-role {
    font-size: 11px;
    opacity: .65;
    display: flex;
    align-items: center;
    gap: 5px;
}
.sb-online-dot {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #2ecc71;
}

.sb-search-wrap {
    padding: 8px 10px 4px !important;
    position: fixed;
    z-index: 99;
    left: 0;
    width: 250px;
    box-sizing: border-box;
    background: inherit;
}
.sb-search-group {
    position: relative;
    display: flex !important;
    align-items: center;
    background: rgba(255,255,255,.12);
    border-radius: 8px;
    overflow: hidden;
    border: 1.5px solid rgba(255,255,255,.35);
    transition: border-color .2s, box-shadow .2s;
    width: 100%;
    min-width: 0;
}
.sidebar-light-primary .sb-search-group,
.sidebar-light-success .sb-search-group,
.sidebar-light-info .sb-search-group,
.sidebar-light-warning .sb-search-group,
.sidebar-light-danger .sb-search-group,
.sidebar-light-teal .sb-search-group {
    background: rgba(0,0,0,.06);
    border-color: rgba(0,0,0,.22);
}
.sb-search-group:focus-within {
    border-color: #00e5c3;
    box-shadow: 0 0 0 2.5px rgba(0,229,195,.25);
}
.sidebar-light-primary .sb-search-group:focus-within,
.sidebar-light-success .sb-search-group:focus-within,
.sidebar-light-info .sb-search-group:focus-within,
.sidebar-light-warning .sb-search-group:focus-within,
.sidebar-light-danger .sb-search-group:focus-within,
.sidebar-light-teal .sb-search-group:focus-within {
    border-color: #00897B;
    box-shadow: 0 0 0 2.5px rgba(0,137,123,.2);
}
.sb-search-icon {
    padding: 0 10px;
    font-size: 14px;
    opacity: .85;
    pointer-events: none;
    display: flex;
    align-items: center;
    color: #a0d4f5;
}
.sidebar-light-primary .sb-search-icon,
.sidebar-light-success .sb-search-icon,
.sidebar-light-info .sb-search-icon,
.sidebar-light-warning .sb-search-icon,
.sidebar-light-danger .sb-search-icon,
.sidebar-light-teal .sb-search-icon {
    color: #555;
    opacity: 1;
}
.sb-search-input {
    border: none !important;
    background: transparent !important;
    box-shadow: none !important;
    font-size: 12px !important;
    padding: 7px 8px 7px 0 !important;
    flex: 1;
    color: inherit;
}
.sb-search-input::placeholder {
    opacity: 1 !important;
    color: rgba(220,235,255,.65) !important;
}
.sidebar-light-primary .sb-search-input::placeholder,
.sidebar-light-success .sb-search-input::placeholder,
.sidebar-light-info .sb-search-input::placeholder,
.sidebar-light-warning .sb-search-input::placeholder,
.sidebar-light-danger .sb-search-input::placeholder,
.sidebar-light-teal .sb-search-input::placeholder {
    color: rgba(0,0,0,.45) !important;
}
.sb-search-input:focus { outline: none; box-shadow: none !important; }

/* Section header labels */
.sb-nav-header {
    font-size: 10px !important;
    font-weight: 800 !important;
    letter-spacing: .1em !important;
    text-transform: uppercase !important;
    padding: 14px 16px 5px !important;
    opacity: .5;
}

/* Nav links */
.sb-nav {
    padding: 0 8px 16px !important;
    margin-top: 52px !important; /* space for fixed search */
}

.sb-nav .nav-item > .nav-link,
.sb-nav .nav-treeview .nav-item > .nav-link {
    border-radius: 8px !important;
    margin: 0 0 2px !important;
    padding: 8px 10px !important;
    width: 100% !important;
    box-sizing: border-box !important;
}

.sidebar-dark-primary .sb-nav .nav-link.active,
.sidebar-dark-success .sb-nav .nav-link.active,
.sidebar-dark-info    .sb-nav .nav-link.active,
.sidebar-dark-warning .sb-nav .nav-link.active,
.sidebar-dark-danger  .sb-nav .nav-link.active,
.sidebar-dark-teal    .sb-nav .nav-link.active {
    border-radius: 8px !important;
    margin-right: 0 !important;
}
.sidebar-light-primary .sb-nav .nav-link.active,
.sidebar-light-success .sb-nav .nav-link.active,
.sidebar-light-info    .sb-nav .nav-link.active,
.sidebar-light-warning .sb-nav .nav-link.active,
.sidebar-light-danger  .sb-nav .nav-link.active,
.sidebar-light-teal    .sb-nav .nav-link.active {
    border-radius: 8px !important;
    margin-right: 0 !important;
}
.sb-nav-link {
    border-radius: 8px !important;
    padding: 8px 10px !important;
    margin-bottom: 2px;
    transition: background .15s, color .15s, transform .1s !important;
    display: flex !important;
    align-items: center !important;
    gap: 0;
}
.sb-nav-link:hover {
    transform: translateX(2px);
}
.sb-child-link {
    padding: 6px 10px 6px 20px !important;
    border-radius: 6px !important;
    width: 100% !important;
    box-sizing: border-box !important;
    margin-right: 0 !important;
}
.sb-child-link::before {
    content: '';
    display: inline-block;
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: .4;
    margin-right: 8px;
    flex-shrink: 0;
}
.sb-child-link:hover::before { opacity: .9; }

/* Icon */
.sb-nav-icon {
    font-size: 15px !important;
    width: 22px !important;
    text-align: center;
    flex-shrink: 0;
    margin-right: 8px !important;
    opacity: .8;
}
.sb-nav-link:hover .sb-nav-icon,
.sb-nav-link.active .sb-nav-icon { opacity: 1; }

/* Label text */
.sb-nav-text {
    font-size: 12.5px !important;
    font-weight: 500 !important;
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 0 !important;
}

/* Caret */
.sb-caret {
    font-size: 11px !important;
    margin-left: auto;
    opacity: .5;
    transition: transform .2s;
}
.menu-open > .sb-nav-link .sb-caret {
    transform: rotate(90deg);
    opacity: .9;
}

/* Treeview child list */
.sb-treeview {
    padding-left: 6px !important;
    margin-left: 0 !important;
}
.sb-treeview::before {
    content: '';
    position: absolute;
    left: 20px; top: 0; bottom: 0;
    width: 1px;
    background: rgba(255,255,255,.1);
}
.sidebar-light-primary .sb-treeview::before,
.sidebar-light-success .sb-treeview::before,
.sidebar-light-info .sb-treeview::before,
.sidebar-light-warning .sb-treeview::before,
.sidebar-light-danger .sb-treeview::before,
.sidebar-light-teal .sb-treeview::before {
    background: rgba(0,0,0,.1);
}

.sb-sidebar::-webkit-scrollbar { width: 3px; }
.sb-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.2); border-radius: 4px; }

.sidebar-search-results .list-group {
    border-radius: 10px !important;
    overflow: hidden !important;
    box-shadow: 0 10px 28px rgba(0,0,0,.5) !important;
    border: 1.5px solid rgba(255,255,255,.22) !important;
    padding: 5px !important;
    margin-top: 4px !important;
    background: #2a3447 !important;
}
.sidebar-light-primary .sidebar-search-results .list-group,
.sidebar-light-success .sidebar-search-results .list-group,
.sidebar-light-info    .sidebar-search-results .list-group,
.sidebar-light-warning .sidebar-search-results .list-group,
.sidebar-light-danger  .sidebar-search-results .list-group,
.sidebar-light-teal    .sidebar-search-results .list-group {
    background: #fff !important;
    border-color: rgba(0,0,0,.1) !important;
    box-shadow: 0 8px 24px rgba(0,0,0,.12) !important;
}
/* individual result rows */
.sidebar-search-results .list-group > .list-group-item {
    background: transparent !important;
    border: none !important;
    border-radius: 7px !important;
    padding: 8px 10px !important;
    margin: 1px 0 !important;
    text-decoration: none !important;
    transition: background .15s !important;
}
.sidebar-search-results .list-group > .list-group-item:hover {
    background: rgba(0,137,123,.2) !important;
}
.sidebar-search-results .list-group > .list-group-item:first-child {
    border-radius: 7px !important;
}
/* title text */
.sidebar-search-results .search-title {
    font-size: 12.5px !important;
    font-weight: 600 !important;
    color: #e2e8f0 !important;
    line-height: 1.3;
    margin-bottom: 1px !important;
}
.sidebar-search-results .search-path {
    font-size: 10.5px !important;
    color: rgba(255,255,255,.4) !important;
    line-height: 1.2;
}
.sidebar-light-primary .sidebar-search-results .search-title,
.sidebar-light-success .sidebar-search-results .search-title,
.sidebar-light-info    .sidebar-search-results .search-title,
.sidebar-light-warning .sidebar-search-results .search-title,
.sidebar-light-danger  .sidebar-search-results .search-title,
.sidebar-light-teal    .sidebar-search-results .search-title {
    color: #2c3e50 !important;
}
.sidebar-light-primary .sidebar-search-results .search-path,
.sidebar-light-success .sidebar-search-results .search-path,
.sidebar-light-info    .sidebar-search-results .search-path,
.sidebar-light-warning .sidebar-search-results .search-path,
.sidebar-light-danger  .sidebar-search-results .search-path,
.sidebar-light-teal    .sidebar-search-results .search-path {
    color: #7f8c8d !important;
}
.sidebar-search-results .text-light {
    color: #00e5ff !important;
    font-weight: 700 !important;
}
.sidebar-light-primary .sidebar-search-results .text-light,
.sidebar-light-success .sidebar-search-results .text-light,
.sidebar-light-info    .sidebar-search-results .text-light,
.sidebar-light-warning .sidebar-search-results .text-light,
.sidebar-light-danger  .sidebar-search-results .text-light,
.sidebar-light-teal    .sidebar-search-results .text-light {
    color: #00897B !important;
}
</style>
    <style>
        .top-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 500;
            position: relative;
            z-index: 1050;
            box-shadow: 0 2px 6px rgba(0,0,0,.15);
        }
        .top-banner .banner-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .top-banner .banner-button {
            background: #ff9800;
            color: #fff;
            border: none;
            padding: 6px 20px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            font-size: 12px;
            transition: background .2s, transform .2s;
        }
        .top-banner .banner-button:hover {
            background: #fb8c00;
            transform: translateY(-1px);
            color: #fff;
        }
        .top-banner .close-banner {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,.2);
            border: none;
            color: #fff;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .top-banner .close-banner:hover { background: rgba(255,255,255,.35); }
        .top-banner.hidden { display: none; }

        .dash-page-header {
            padding: 20px 0 4px;
            border-bottom: 1px solid rgba(0,0,0,.06);
            margin-bottom: 20px;
        }
        .dash-page-header h4 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
        }
        .dark-mode .dash-page-header h4 { color: #ecf0f1; }
        .dash-page-header .dash-date {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 2px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #95a5a6;
            margin-bottom: 10px;
            padding-left: 2px;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            display: flex;
            align-items: center;
            padding: 16px 18px;
            gap: 14px;
            transition: box-shadow .2s, transform .2s;
            border-left: 4px solid transparent;
            margin-bottom: 14px;
            text-decoration: none !important;
            color: inherit !important;
        }
        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,.12);
            transform: translateY(-2px);
        }
        .dark-mode .stat-card {
            background: #2d3748;
            box-shadow: 0 1px 6px rgba(0,0,0,.3);
        }
        .stat-card .sc-icon {
            width: 46px;
            height: 46px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
            color: #fff;
        }
        .stat-card .sc-body { flex: 1; min-width: 0; }
        .stat-card .sc-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #7f8c8d;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .dark-mode .stat-card .sc-label { color: #a0aec0; }
        .stat-card .sc-value {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.2;
            color: #2c3e50;
        }
        .dark-mode .stat-card .sc-value { color: #e2e8f0; }

        /* accent colours */
        .sc-blue   { border-left-color: #3498db; } .sc-blue   .sc-icon { background: linear-gradient(135deg,#3498db,#2980b9); }
        .sc-yellow { border-left-color: #f39c12; } .sc-yellow .sc-icon { background: linear-gradient(135deg,#f39c12,#e67e22); }
        .sc-maroon { border-left-color: #c0392b; } .sc-maroon .sc-icon { background: linear-gradient(135deg,#e74c3c,#c0392b); }
        .sc-orange { border-left-color: #e67e22; } .sc-orange .sc-icon { background: linear-gradient(135deg,#e67e22,#d35400); }
        .sc-navy   { border-left-color: #34495e; } .sc-navy   .sc-icon { background: linear-gradient(135deg,#34495e,#2c3e50); }
        .sc-green  { border-left-color: #27ae60; } .sc-green  .sc-icon { background: linear-gradient(135deg,#27ae60,#1e8449); }
        .sc-purple { border-left-color: #8e44ad; } .sc-purple .sc-icon { background: linear-gradient(135deg,#8e44ad,#6c3483); }
        .sc-red    { border-left-color: #e74c3c; } .sc-red    .sc-icon { background: linear-gradient(135deg,#e74c3c,#c0392b); }
        .sc-teal   { border-left-color: #16a085; } .sc-teal   .sc-icon { background: linear-gradient(135deg,#1abc9c,#16a085); }
        .sc-indigo { border-left-color: #5c6bc0; } .sc-indigo .sc-icon { background: linear-gradient(135deg,#5c6bc0,#3949ab); }
        .sc-cyan   { border-left-color: #00bcd4; } .sc-cyan   .sc-icon { background: linear-gradient(135deg,#00bcd4,#0097a7); }

        .action-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            display: flex;
            align-items: center;
            padding: 14px 16px;
            gap: 12px;
            text-decoration: none !important;
            color: inherit !important;
            margin-bottom: 14px;
            transition: box-shadow .2s, transform .2s;
            border: 1px solid #f0f0f0;
            position: relative;
            overflow: hidden;
        }
        .action-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 3px;
        }
        .action-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,.12);
            transform: translateY(-2px);
        }
        .dark-mode .action-card {
            background: #2d3748;
            border-color: #3d4a5c;
        }
        .action-card .ac-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
            color: #fff;
        }
        .action-card .ac-body { flex: 1; min-width: 0; }
        .action-card .ac-label {
            font-size: 12px;
            font-weight: 600;
            color: #7f8c8d;
            margin-bottom: 0;
        }
        .dark-mode .action-card .ac-label { color: #a0aec0; }
        .action-card .ac-value {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.1;
        }
        .dark-mode .action-card .ac-value { color: #e2e8f0; }
        .action-card .ac-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            white-space: nowrap;
        }
        .action-card .ac-arrow {
            font-size: 16px;
            color: #bdc3c7;
            flex-shrink: 0;
        }
        /* action card colour variants */
        .ac-teal::before   { background: linear-gradient(90deg,#1abc9c,#16a085); }
        .ac-teal   .ac-icon { background: linear-gradient(135deg,#1abc9c,#16a085); }
        .ac-teal   .ac-badge { background: #e8fdf5; color: #16a085; }
        .ac-indigo::before { background: linear-gradient(90deg,#5c6bc0,#3949ab); }
        .ac-indigo .ac-icon { background: linear-gradient(135deg,#5c6bc0,#3949ab); }
        .ac-indigo .ac-badge { background: #eef0fb; color: #3949ab; }
        .ac-cyan::before   { background: linear-gradient(90deg,#00bcd4,#0097a7); }
        .ac-cyan   .ac-icon { background: linear-gradient(135deg,#00bcd4,#0097a7); }
        .ac-cyan   .ac-badge { background: #e0f7fa; color: #0097a7; }
        .ac-amber::before  { background: linear-gradient(90deg,#ffa726,#fb8c00); }
        .ac-amber  .ac-icon { background: linear-gradient(135deg,#ffa726,#fb8c00); }
        .ac-amber  .ac-badge { background: #fff8e1; color: #e65100; }

        .sales-summary-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            margin-bottom: 14px;
            overflow: hidden;
        }
        .dark-mode .sales-summary-card { background: #2d3748; }
        .sales-summary-card .ssc-header {
            padding: 14px 18px 8px;
            border-bottom: 1px solid #f5f5f5;
        }
        .dark-mode .sales-summary-card .ssc-header { border-bottom-color: #3d4a5c; }
        .sales-summary-card .ssc-header h6 {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #7f8c8d;
            margin: 0;
        }
        .sales-summary-card .ssc-body { padding: 12px 18px 14px; }
        .sales-summary-card .ssc-amount {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
        }
        .dark-mode .sales-summary-card .ssc-amount { color: #e2e8f0; }
        .sales-summary-card .ssc-diff {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 2px;
        }
        .sales-summary-card .ssc-diff .up   { color: #27ae60; font-weight: 700; }
        .sales-summary-card .ssc-diff .down { color: #e74c3c; font-weight: 700; }

        .dash-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 6px rgba(0,0,0,.07);
            margin-bottom: 14px;
            overflow: hidden;
        }
        .dark-mode .dash-card { background: #2d3748; }
        .dash-card .dc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 18px;
            border-bottom: 1px solid #f5f5f5;
        }
        .dark-mode .dash-card .dc-header { border-bottom-color: #3d4a5c; }
        .dash-card .dc-header h6 {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
            color: #2c3e50;
        }
        .dark-mode .dash-card .dc-header h6 { color: #e2e8f0; }
        .dash-card .dc-body { padding: 14px 18px; }

        /* location table */
        .loc-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .loc-table tr td { padding: 6px 4px; border-bottom: 1px solid #f5f5f5; }
        .dark-mode .loc-table tr td { border-bottom-color: #3d4a5c; color: #e2e8f0; }
        .loc-table tr:last-child td { border-bottom: none; }
        .loc-table .loc-amount { text-align: right; font-weight: 600; color: #2c3e50; }
        .dark-mode .loc-table .loc-amount { color: #e2e8f0; }

        /* avg order value */
        .avg-val { text-align: center; font-size: 22px; font-weight: 700; color: #2c3e50; margin-top: 6px; }
        .dark-mode .avg-val { color: #e2e8f0; }
    </style>