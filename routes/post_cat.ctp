<div id="page-wrapper">
    <div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
        <?php echo $this->element('member_sidepanel'); ?>
        <!-- Main Container -->
        <div id="main-container">
            <header class="navbar navbar-inverse navbar-fixed-top">
                <!-- Left Header Navigation -->
                <ul class="nav navbar-nav-custom">
                    <!-- Main Sidebar Toggle Button -->
                    <li>
                        <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                            <i class="fa fa-ellipsis-v fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                            <i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                        </a>
                    </li>
                    <!-- END Main Sidebar Toggle Button -->
                    <!-- Header Link -->
                    <li class="hidden-xs animation-fadeInQuick">
                        <a href="javascript:void(0)"><strong>Post Cat List</strong></a>
                    </li>
                    <!-- END Header Link -->
                </ul>
                <!-- END Left Header Navigation -->
                <?php echo $this->element('member_header'); ?>
            </header>
            <div id="page-content">

                <span style="text-align:center;color:red;"><?php echo $this->Flash->render('acc_alert'); ?></span>
                <!-- END Tables Row -->
                <div class="block full">
                    <div>
                        <a href="<?php echo webURL . 'add-edit-post-cat'; ?>" class="link-add btn-sm pull-right"><i
                                class="fa fa-plus" aria-hidden="true"></i> Add New Post</a>
                    </div>
                    <div class="lsdtable">
                        <table id="example-datatable" class="table table-striped table-bordered table-vcenter">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">ID</th>
                                    <th>Category For</th>
                                    <th>Section</th>
                                    <th>Sub-Section</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $ci = 1;
                                foreach ($result as $results) { ?>
                                    <tr>
                                        <td class="text-center"><?php echo $results['id']; ?></td>
                                        <td><?php echo $results['cat_id']; ?></td>
                                        <td><?php echo $this->Member->wall_parent_name($results['parent']); ?></td>
                                        <td><?php echo $results['name']; ?></td>
                                        <td><?php if ($results['status'] == '1') {
                                            echo '<span class="label label-success">Active..</span>';
                                        } else {
                                            echo '<span class="label label-danger">Deactive..</span>';
                                        } ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?php echo webURL . 'add-edit-wall-category?type=update&&id=' . $results['id']; ?>"
                                                data-toggle="tooltip" title="Edit category"
                                                class="btn btn-effect-ripple btn-xs btn-success"><i
                                                    class="fa fa-pencil"></i></a>
                                            <a href="<?php echo webURL . 'add-edit-wall-category?type=delete&&id=' . $results['id']; ?>"
                                                data-toggle="tooltip"
                                                onclick="return confirm('Are you sure? you want to delete this category!');"
                                                title="Delete category" class="btn btn-effect-ripple btn-xs btn-danger"><i
                                                    class="fa fa-times"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Datatables Block -->

                <!-- END Datatables Block -->
            </div>
            <!-- END Page Content -->
        </div>
        <!-- END Main Container -->
    </div>
    <!-- END Page Container -->
</div>
<!-- END Page Wrapper -->


<?php echo $this->Html->script(webURL . 'admin/js/vendor/jquery-2.2.4.min.js'); ?>
<?php echo $this->Html->script(webURL . 'admin/js/pages/uiTables.js'); ?>
<script>$(function () { UiTables.init(); });</script>