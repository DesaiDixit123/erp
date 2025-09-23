<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<div id="page-wrapper">
    <div id="page-container" class="header-fixed-top sidebar-visible-lg-full">
        <?php echo $this->element('member_sidepanel'); ?>

        <!-- Main Container -->
        <div id="main-container">
            <header class="navbar navbar-inverse navbar-fixed-top">
                <ul class="nav navbar-nav-custom">
                    <li>
                        <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');this.blur();">
                            <i class="fa fa-ellipsis-v fa-fw animation-fadeInRight" id="sidebar-toggle-mini"></i>
                            <i class="fa fa-bars fa-fw animation-fadeInRight" id="sidebar-toggle-full"></i>
                        </a>
                    </li>
                    <li class="hidden-xs animation-fadeInQuick">
                        <a href="javascript:void(0)">
                            <strong><?php echo !empty($result) ? 'Update Post Category' : 'Add Post Category'; ?></strong>
                        </a>
                    </li>
                </ul>
                <?php echo $this->element('member_header'); ?>
            </header>

            <div id="page-content">
                <div class="text-center">
                    <center style="color:red;"><?php echo $this->Flash->render('acc_alert'); ?></center>
                </div>

                <form id="form-validation" action="" method="post" class="form-horizontal form-bordered"
                    enctype="multipart/form-data">
                    <div class="block full">
                        <div class="lsiblock">
                            <div class="row">

                                <!-- Parent Category -->
                                <div class="form-group col-md-4">
                                    <label class="col-md-12">Parent Category</label>
                                    <div class="col-md-12">
                                        <select id="parentCategory" name="parentCategory" class="form-control">
                                            <option value="">-- Select Category --</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Parent -->
                                <div class="form-group col-md-4">
                                    <label class="col-md-12">Parent</label>
                                    <div class="col-md-12">
                                        <select id="parent" name="parent" class="form-control"
                                            onchange="toggleFields(this.value)">
                                            <option value="0">Self</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Sub Category -->
                                <div class="form-group col-md-4" id="subCatField" style="display:none;">
                                    <label class="col-md-12">Sub Category</label>
                                    <div class="col-md-12">
                                        <input type="text" name="sub_category" class="form-control"
                                            value="<?php echo !empty($result['sub_category']) ? $result['sub_category'] : ''; ?>">
                                    </div>
                                </div>

                                <!-- Section -->
                                <div class="form-group col-md-4" id="sectionField" style="display:none;">
                                    <label class="col-md-12">Section</label>
                                    <div class="col-md-12">
                                        <input type="text" name="section" class="form-control"
                                            value="<?php echo !empty($result['section']) ? $result['section'] : ''; ?>">
                                        <input type="hidden" name="id"
                                            value="<?php echo !empty($result['id']) ? $result['id'] : ''; ?>">
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="form-group col-md-4">
                                    <label class="col-md-12">Status</label>
                                    <div class="col-md-12">
                                        <select name="status" class="form-control">
                                            <option value="1" <?php if (!empty($result) && $result['status'] == '1')
                                                echo 'selected'; ?>>Active</option>
                                            <option value="0" <?php if (!empty($result) && $result['status'] == '0')
                                                echo 'selected'; ?>>Deactive</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-effect-ripple btn-primary">Submit</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script type="text/javascript">
    $(document).ready(function () {
        $.ajax({
            type: 'get',
            url: 'get_post_category_list',
            success: function (data) {
                $("#parentCategory").html(data);
            }
        });

        toggleFields($("#parent").val());
    });

    function toggleFields(parentVal) {
        if (parentVal == 0) {
            $("#subCatField").show();
            $("#sectionField").show();
        } else {
            $("#subCatField").hide();
            $("#sectionField").show();
        }
    }
</script>

<?php echo $this->Html->script(webURL . 'admin/js/pages/formsValidation.js'); ?>
<script>$(function () { FormsValidation.init(); });</script>