<div class="header">
    <div class="header-left active">
        <a href="<?= site_url() ?>" class="logo logo-normal">
            <img src="<?= site_url('assets/images/pos-logo.png') ?>" alt>
        </a>
        <a href="<?= site_url() ?>" class="logo logo-white">
            <img src="<?= site_url('assets/images/pos-logo.png') ?>" alt>
        </a>
        <a href="<?= site_url() ?>" class="logo-small">
            <img src="<?= site_url('assets/images/logo.png') ?>" alt>
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <ul class="nav user-menu">

        <li class="nav-item nav-searchinputs">
            <div class="top-nav-search">
                <a href="javascript:void(0);" class="responsive-search">
                    <i class="fa fa-search"></i>
                </a>
                <form action="#">
                    <div class="searchinputs">
                        <input type="text" placeholder="Search">
                        <div class="search-addon">
                            <span><i data-feather="search" class="feather-14"></i></span>
                        </div>
                    </div>

                </form>
            </div>
        </li>


        <li class="nav-item dropdown has-arrow flag-nav nav-item-box">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="javascript:void(0);" role="button">
                <i data-feather="globe"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="javascript:void(0);" class="dropdown-item active">
                    <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/flags/us.png" alt height="16"> English
                </a>
            </div>
        </li>

        <li class="nav-item nav-item-box">
            <a href="javascript:void(0);" id="btnFullscreen">
                <i data-feather="maximize"></i>
            </a>
        </li>
        <li class="nav-item dropdown nav-item-box">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <i data-feather="bell"></i><span class="badge rounded-pill">2</span>
            </a>
            <div class="dropdown-menu notifications">
                <div class="topnav-dropdown-header">
                    <span class="notification-title">Notifications</span>
                    <a href="javascript:void(0)" class="clear-noti"> Clear All </a>
                </div>
                <div class="noti-content">
                    <ul class="notification-list">
                        <!--
                        <li class="notification-message">
                            <a href="https://dreamspos.dreamguystech.com/html/template/activities.html">
                                <div class="media d-flex">
                                    <span class="avatar flex-shrink-0">
                                        <img alt src="https://dreamspos.dreamguystech.com/html/template/assets/img/profiles/avatar-02.jpg">
                                    </span>
                                    <div class="media-body flex-grow-1">
                                        <p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
                                        <p class="noti-time"><span class="notification-time">4 mins ago</span></p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    -->
                    </ul>
                </div>
                <div class="topnav-dropdown-footer">
                    <a href="#">View all Notifications</a>
                </div>
            </div>
        </li>

        <li class="nav-item nav-item-box">
            <a href="<?=site_url('settings/general-settings') ?>"><i data-feather="settings"></i></a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="https://dreamspos.dreamguystech.com/html/template/assets/img/profiles/avator1.jpg" alt class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name">John Smilga</span>
                        <span class="user-role">Super Admin</span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/profiles/avator1.jpg" alt>
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6>John Smilga</h6>
                            <h5>Super Admin</h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="<?=site_url('account/profile') ?>"> <i class="me-2" data-feather="user"></i> My Profile</a>
                    <a class="dropdown-item" href="<?=site_url('account/settings') ?>"><i class="me-2" data-feather="settings"></i>Settings</a>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="<?=site_url('logout') ?>"><img src="https://dreamspos.dreamguystech.com/html/template/assets/img/icons/log-out.svg" class="me-2" alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?=site_url('account/profile') ?>">My Profile</a>
            <a class="dropdown-item" href="<?=site_url('account/settings') ?>">Settings</a>
            <a class="dropdown-item" href="<?=site_url('logout') ?>">Logout</a>
        </div>
    </div>

</div>