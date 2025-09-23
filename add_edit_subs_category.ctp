<div id="page-wrapper">
    <div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
        <?php echo $this->element('member_sidepanel'); ?>
        <div id="main-container">
            <header class="navbar navbar-inverse navbar-fixed-top">
                <ul class="nav navbar-nav-custom">
                    <li><a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');"><i class="fa fa-bars"></i></a></li>
                    <li class="hidden-xs"><a href="#"><strong><?= $editMode ? 'Edit Sub Category' : 'Add Sub Category' ?></strong></a></li>
                </ul>
                <?php echo $this->element('member_header'); ?>
            </header>

            <div id="page-content">
                <span style="color:red;"><?php echo $this->Flash->render('acc_alert'); ?></span>

                <form method="post" class="form-horizontal form-bordered">
                    <div class="block full">
                        <div class="form-group col-md-6">
                            <label class="col-md-12">Category</label>
                            <div class="col-md-12">
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $id => $name): ?>
                                        <option value="<?= $id ?>" <?= (!empty($subResult['category_id']) && $subResult['category_id'] == $id) ? 'selected' : '' ?>>
                                            <?= $name ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label class="col-md-12">Sub Category Name</label>
                            <div class="col-md-12">
                                <input type="text" name="sub_category_name" class="form-control"
                                       value="<?= !empty($subResult['sub_category_name']) ? $subResult['sub_category_name'] : ''; ?>" required>
                                <input type="hidden" name="id" value="<?= !empty($subResult['id']) ? $subResult['id'] : ''; ?>">
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <?= $editMode ? 'Update Sub Category' : 'Save Sub Category' ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
