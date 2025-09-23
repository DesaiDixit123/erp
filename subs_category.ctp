<div id="page-wrapper">
    <div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
        <?php echo $this->element('member_sidepanel'); ?>

        <div id="main-container">
            <header class="navbar navbar-inverse navbar-fixed-top">
                <ul class="nav navbar-nav-custom">
                    <li>
                        <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                            <i class="fa fa-bars fa-fw"></i>
                        </a>
                    </li>
                    <li class="hidden-xs">
                        <a href="javascript:void(0)"><strong>Sub Category</strong></a>
                    </li>
                </ul>
                <?php echo $this->element('member_header'); ?>
            </header>

            <div id="page-content">
                <span style="text-align:center;color:red;">
                    <?php echo $this->Flash->render('acc_alert'); ?>
                </span>

                <div class="block full">
                    <div>
                        <a href="<?php echo webURL . 'add-edit-sub-category'; ?>" class="btn btn-sm btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add New
                        </a>
                    </div>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($result)) { ?>
                                <?php foreach ($result as $row) { ?>
                                    <tr>
                                        <td><?= h($row['id']); ?></td>
                                        <td><?= h($row['PostGkCat']['category_name']); ?></td>
                                        <td><?= h($row['sub_category_name']); ?></td>
                                        <td>
                                            <?php if ($row['status'] == 1) { ?>
                                                <span class="label label-success">Active</span>
                                            <?php } else { ?>
                                                <span class="label label-danger">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <a href="<?= webURL . 'add-edit-sub-category?type=update&id=' . $row['id']; ?>" class="btn btn-xs btn-success">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="<?= webURL . 'add-edit-sub-category?type=delete&id=' . $row['id']; ?>"
                                               onclick="return confirm('Are you sure you want to delete this?');"
                                               class="btn btn-xs btn-danger">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr><td colspan="5" class="text-center">No records found</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
