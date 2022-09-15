<?php
$file_name = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
if (strpos($file_name, '.php') == false) {
    $file_name .= '.php';
}
?>
<div id="sidebar" class="sidebar responsive sidebar-fixed sidebar-scroll">
    <ul class="nav nav-list">
        <li <?php if ($file_name == 'users.php') { ?> class="active open" <?php } ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-tachometer"></i>
                <span class="menu-text">
                    Masters
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li <?php if ($file_name == 'users.php') { ?> class="active hover" <?php } ?>>
                    <a href="users.php">
                        <i class="menu-icon fa fa-users"></i>
                        <span class="menu-text"> Users </span>
                    </a>
                </li>
                <?php if (($_SESSION['user'] == 'Anup Patil')) { ?>
                    <li <?php if ($file_name == 'users.php') { ?> class="active hover" <?php } ?>>
                        <a href="db-backup.php">
                            <i class="menu-icon fa fa-cog"></i>
                            <span class="menu-text"> DB Backup </span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </li>
        <li <?php if (($file_name == 'course.php') || ($file_name == 'courses.php')) { ?> class="active open" <?php } ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-pencil-square-o"></i>
                <span class="menu-text">
                    Courses
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li <?php if ($file_name == 'course.php') { ?> class="active hover" <?php } ?>>

                    <a href="course.php">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text"> Add Course </span>
                    </a>
                </li>
                <li <?php if ($file_name == 'courses.php') { ?> class="active hover" <?php } ?>>

                    <a href="courses.php">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text"> Courses </span>
                    </a>
                </li>
            </ul>
        </li>
        <li <?php if (($file_name == 'fees-collection-report.php') || ($file_name == 'dcr-report-detailed.php') || ($file_name == 'dcr-report-short.php') || ($file_name == 'paid-outstanding-report-short.php') || ($file_name == 'paid-outstanding-report-detailed.php') || ($file_name == 'dcr-report.php') || ($file_name == 'mis-report.php') || ($file_name == 'my-reports.php')) { ?> class="active open" <?php } ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-calendar"></i>
                <span class="menu-text">
                    Reports  
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li <?php if ($file_name == 'paid-outstanding-report-short.php') { ?> class="active hover" <?php } ?>>
                    <a href="paid-outstanding-report-short.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Paid Fees Report (short)
                    </a>
                </li>
                <li <?php if ($file_name == 'paid-outstanding-report-detailed.php') { ?> class="active hover" <?php } ?>>
                    <a href="paid-outstanding-report-detailed.php">
                        <i class="menu-icon fa fa-caret-right"></i>
                        Paid Fees Report (detailed)
                    </a>
                </li>
                <li <?php if ($file_name == 'dcr-report.php') { ?> class="active hover" <?php } ?>>
                    <a href="dcr-report.php">
                        <i class="menu-icon fa fa-list"></i>
                        <span class="menu-text"> DCR Reports (Summary) </span>
                    </a>
                </li>
                <li <?php if ($file_name == 'dcr-report-short.php') { ?> class="active hover" <?php } ?>>
                    <a href="dcr-report-short.php">
                        <i class="menu-icon fa fa-list"></i>
                        <span class="menu-text"> DCR Reports (short) </span>
                    </a>
                </li>
                <li <?php if ($file_name == 'dcr-report-detailed.php') { ?> class="active hover" <?php } ?>>
                    <a href="dcr-report-detailed.php">
                        <i class="menu-icon fa fa-list"></i>
                        <span class="menu-text"> DCR Reports (detailed) </span>
                    </a>
                </li>
                <li <?php if ($file_name == 'fees-collection-report.php') { ?> class="active hover" <?php } ?>>
                    <a href="fees-collection-report.php">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text"> Fees Collection Report </span>
                    </a>
                </li>
                <li <?php if ($file_name == 'mis-report.php') { ?> class="active hover" <?php } ?>>
                    <a href="mis-report.php">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text"> MIS Report </span>
                    </a>
                </li>
                <li <?php if ($file_name == 'my-reports.php') { ?> class="active hover" <?php } ?>>
                    <a href="my-reports.php">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text"> My Report </span>
                    </a>
                </li>

            </ul>
        </li>

        <li <?php if (($file_name == 'admissions.php') || ($file_name == 'students.php')) { ?> class="active open" <?php } ?>>
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-file-o"></i>
                <span class="menu-text">
                    Admissions  
                </span>

                <b class="arrow fa fa-angle-down"></b>
            </a>

            <b class="arrow"></b>

            <ul class="submenu">
                <li <?php if ($file_name == 'admissions.php') { ?> class="active hover" <?php } ?>>
                    <a href="admissions.php">
                        <i class="menu-icon fa fa-calendar"></i>
                        <span class="menu-text"> Admissions </span>
                    </a>
                </li>
                <li <?php if ($file_name == 'students.php') { ?> class="active hover" <?php } ?>>

                    <a href="students.php">
                        <i class="menu-icon fa fa-pencil-square-o"></i>
                        <span class="menu-text"> Student List</span>
                    </a>
                </li>
            </ul>
        </li>
        <li <?php if ($file_name == 'admission-form.php') { ?> class="active hover" <?php } ?>>

            <a href="admission-form.php">
                <i class="menu-icon fa fa-pencil-square-o"></i>
                <span class="menu-text"> Admission Forms </span>
            </a>
        </li>
        <li <?php if ($file_name == 'other-receipt.php') { ?> class="active hover" <?php } ?>>

            <a href="other-receipt.php">
                <i class="menu-icon fa fa-pencil-square-o"></i>
                <span class="menu-text"> Other Receipt </span>
            </a>
        </li>
        <!--        <li>
        <?php $url = 'http://' . $_SERVER['SERVER_NAME'] . '/acsc/library'; ?>
                    <a target="_blank" href="<?php echo $url; ?>">
                        <i class="menu-icon fa fa-book"></i>
                        <span class="menu-text"> Library </span>
                    </a>
                </li>-->
    </ul><!-- /.nav-list -->
    <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left " data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div>
</div>
