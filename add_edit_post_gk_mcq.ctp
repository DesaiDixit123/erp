<body>
    <style>
        label {
            display: none1
        }

        .chosen-container-single .chosen-single {
            position: relative;
            display: block;
            overflow: hidden;
            padding: 4px 0 0 8px;
            height: 40px;
            border: 1px solid #eee;

        }

        .description1 {
            height: 80px !important;
            margin-bottom: 15px !important;
        }
    </style>
    <script>
        function PreviewImage(no) {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage" + no).files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("uploadPreview" + no).src = oFREvent.target.result;
            };
        }	
    </script>
    <div id="page-wrapper" class="uprofile">
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
                            <a href="javascript:void(0)"><strong>
                                    <?php if ($paction == 'copy') {
                                        echo 'Create Clone your Post';
                                    } else if (empty($viewData)) {
                                        echo 'Write your Post';
                                    } else {
                                        echo 'Update your Post';
                                    } ?>
                                </strong></a>
                        </li>
                        <!-- END Header Link -->
                    </ul>
                    <!-- END Left Header Navigation -->
                    <?php echo $this->element('member_header'); ?>
                </header>
                <div id="page-content">
                    <!-- Form Validation Content -->
                    <!-- Form Validation Form -->
                    <div class="">
                        <div class="lsdbllock">
                            <form id="form-validation" action="" method="post" class="form-horizontal form-bordered"
                                enctype="multipart/form-data">
                                <?= $this->Form->create($entity, ['type' => 'file', 'enctype' => 'multipart/form-data']) ?>

                                <div class="row">
                                    <div class="lwhitebg">
                                        <!-- Form Validation Title -->
                                        <div class="">
                                            <h2><?php //echo  $this->Flash->render('acc_alert'); ?></h2>
                                        </div>
                                        <div class="boxfrom">


                                            <!-- Post Type -->
                                            <div class="form-group" style="display:none">
                                                <label class="col-md-12">Post Type</label>
                                                <div class="col-md-12">
                                                    <?= $this->Form->control('post_type', [
                                                        'label' => false,
                                                        'class' => 'form-control'
                                                    ]) ?>
                                                </div>
                                            </div>

                                            <!-- Title -->
                                            <div class="form-group">
                                                <label class="col-md-12">Title</label>
                                                <div class="col-md-12">
                                                    <?= $this->Form->control('title', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'placeholder' => 'Enter Title',
                                                        'value' => $titleValue
                                                    ]) ?>
                                                </div>
                                            </div>
                                         <!-- Category -->
<div class="form-group col-md-4">
    <label class="col-md-12">Category</label>
    <div class="col-md-12">
        <select id="category_id_post" name="category" class="form-control" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $id => $name) { ?>
                <option value="<?= $id ?>"
                    <?= (!empty($postResult['category']) && $postResult['category'] == $id) ? 'selected' : '' ?>>
                    <?= $name ?>
                </option>
            <?php } ?>
        </select>
    </div>
</div>

<!-- Sub Category -->
<div class="form-group col-md-4">
    <label class="col-md-12">Sub Category</label>
    <div class="col-md-12">
        <select id="sub_category_id_post" name="subcategory" class="form-control" required>
            <option value="">-- Select Sub Category --</option>
            <?php if (!empty($subcategories)) {
                foreach ($subcategories as $id => $name) { ?>
                    <option value="<?= $id ?>"
                        <?= (!empty($postResult['subcategory']) && $postResult['subcategory'] == $id) ? 'selected' : '' ?>>
                        <?= $name ?>
                    </option>
                <?php }
            } ?>
        </select>
    </div>
</div>

<!-- Section -->
<div class="form-group col-md-4">
    <label class="col-md-12">Section</label>
    <div class="col-md-12">
        <select id="section_id_post" name="section" class="form-control" required>
            <option value="">-- Select Section --</option>
            <?php if (!empty($sections)) {
                foreach ($sections as $id => $name) { ?>
                    <option value="<?= $id ?>"
                        <?= (!empty($postResult['section']) && $postResult['section'] == $id) ? 'selected' : '' ?>>
                        <?= $name ?>
                    </option>
                <?php }
            } ?>
        </select>
    </div>
</div>

                                            <div class="col-md-12">
                                                <input type="file" id='uploadImage1' onchange="PreviewImage(1);"
                                                    name="image" value="" class="form-control"
                                                    placeholder="Upload Photo">
                                                <input type="hidden" id="val-name" name="saveimage" value="<?php if (!empty($viewData)) {
                                                    echo $viewData['image'];
                                                } ?>" class="form-control" placeholder="Upload Photo">
                                            </div>

                                            <!-- Image Link -->
                                            <div class="form-group">
                                                <label class="col-md-12">Image Link</label>
                                                <div class="col-md-12">
                                                    <?= $this->Form->control('image_link', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'placeholder' => 'https://example.com/image.jpg'
                                                    ]) ?>
                                                </div>
                                            </div>

                                            <!-- Document Upload -->
                                            <div class="form-group">
                                                <label class="col-md-12">Upload Document (PDF)</label>
                                                <div class="col-md-12">
                                                    <input type="file" name="doc_file" id="doc_file"
                                                        accept="application/pdf" class="form-control">

                                                    <!-- old document hidden rakhiye -->
                                                    <input type="hidden" name="savedoc" value="<?php if (!empty($viewData['documnet_link'])) {
                                                        echo $viewData['documnet_link'];
                                                    } ?>">
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div class="form-group">
                                                <label class="col-md-12">Description</label>
                                                <div class="col-md-12">
                                                    <?= $this->Form->control('description', [
                                                        'type' => 'textarea',
                                                        'label' => false,
                                                        'class' => 'form-control description1',
                                                        'placeholder' => 'Write description here...'
                                                    ]) ?>
                                                </div>
                                            </div>

                                            <!-- Submit -->
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <?= $this->Form->button(__('Save'), ['class' => 'btn btn-primary']) ?>
                                                </div>
                                            </div>



                                            <?php if (!empty($viewData)) { ?>
                                                <div class="form-group col-md-6" style="display:none;">
                                                    <label class="col-md-12" for="val-skill">Status<span
                                                            class="text-danger"></span></label>
                                                    <div class="col-md-12">
                                                        <select name="status" id="statusstatus" class="form-control">
                                                            <option value="1" <?php if (!empty($viewData)) {
                                                                if ($viewData['status'] == '1') {
                                                                    echo 'selected';
                                                                }
                                                            } ?>>Active</option>
                                                            <option value="0" <?php if (!empty($viewData)) {
                                                                if ($viewData['status'] == '0') {
                                                                    echo 'selected';
                                                                }
                                                            } ?>>Deactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="lgreybg previewbox">
                                        <?php if (!empty($viewData)) { ?>
                                            <h3 style="margin-left:30px">Preview</h3>

                                            <div class="lswallbolck jobblock">
                                                <span
                                                    class="lstag"><?php echo !empty($viewData['post_type']) ? $viewData['post_type'] : 'GK Post'; ?></span>
                                                <p class="lsdate">
                                                    <?php echo !empty($viewData['updated']) ? date('d M Y', strtotime($viewData['updated'])) : date('d M Y'); ?>
                                                </p>

                                                <h3><?php echo !empty($viewData['title']) ? $viewData['title'] : ''; ?></h3>

                                                <?php if (!empty($viewData['image'])) { ?>
                                                    <a href="<?php echo webURL . 'img/Post/' . $viewData['image']; ?>"
                                                        target="_blank">
                                                        <div class="postbanner">
                                                            <img src="<?php echo webURL . 'img/Post/' . $viewData['image']; ?>"
                                                                alt="Lifeset Post" class="img-responsive">
                                                        </div>
                                                    </a>
                                                <?php } ?>

                                                <div class="walljobpst">
                                                    <ul class="lsdetls">
                                                        <?php if (!empty($viewData->post_gk_cat->category_name)) { ?>
                                                            <li><span>Category :</span>
                                                                <?= h($viewData->post_gk_cat->category_name); ?></li>
                                                        <?php } ?>

                                                        <?php if (!empty($viewData->post_gk_subcat->sub_category_name)) { ?>
                                                            <li><span>Subcategory :</span>
                                                                <?= h($viewData->post_gk_subcat->sub_category_name); ?></li>
                                                        <?php } ?>

                                                        <?php if (!empty($viewData->post_gk_section->section_name)) { ?>
                                                            <li><span>Section :</span>
                                                                <?= h($viewData->post_gk_section->section_name); ?></li>
                                                        <?php } ?>

                                                        <?php if (!empty($viewData['image_link'])) { ?>
                                                            <li><span>Image Link :</span> <a
                                                                    href="<?php echo $viewData['image_link']; ?>"
                                                                    target="_blank"><?php echo $viewData['image_link']; ?></a>
                                                            </li>
                                                        <?php } ?>

                                                    </ul>

                                                    <p class="viewdec">
                                                        <?php echo !empty($viewData['description']) ? nl2br($viewData['description']) : ''; ?>
                                                    </p>

                                                    <?php if (!empty($viewData['documnet_link'])) { ?>
                                                        <p>
                                                            <a href="<?php echo webURL . 'files/documents/' . $viewData['documnet_link']; ?>"
                                                                target="_blank" class="btn btn-sm btn-primary">
                                                                Read More
                                                            </a>
                                                        </p>
                                                    <?php } ?>

                                                </div>
                                            </div>

                                            <!-- Buttons -->
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button type="submit" name="Publish"
                                                        class="btn btn-primary">Publish</button>
                                                    <?php if (!empty($viewData['status']) && $viewData['status'] == 1) { ?>
                                                        <button type="submit" name="Deactive"
                                                            class="btn btn-primary deactive pull-right">Deactive</button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>


                                <?= $this->Form->end() ?>
                            </form>
                            <!-- END Page Content -->
                        </div>
                        <!-- END Main Container -->
                    </div>
                    <!-- END Page Container -->
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript">
$(document).ready(function () {

    function loadSubcategories(categoryId, selectedSubcategory = null, callback = null) {
        if (categoryId) {
            $.ajax({
                url: "<?= $this->Url->build(['action' => 'getSubCategories']) ?>",
                type: "GET",
                data: { category_id: categoryId },
                success: function (data) {
                    $("#sub_category_id_post").html('<option value="">-- Select Sub Category --</option>' + data);
                    $("#section_id_post").html('<option value="">-- Select Section --</option>');

                    if (selectedSubcategory) {
                        $("#sub_category_id_post").val(selectedSubcategory);
                    }

                    if (callback) callback(selectedSubcategory); // next step
                }
            });
        } else {
            $("#sub_category_id_post").html('<option value="">-- Select Sub Category --</option>');
            $("#section_id_post").html('<option value="">-- Select Section --</option>');
        }
    }

    function loadSections(subCategoryId, selectedSection = null) {
        if (subCategoryId) {
            $.ajax({
                url: "<?= $this->Url->build(['action' => 'getSections1']) ?>",
                type: "GET",
                data: { sub_category_id: subCategoryId },
                success: function (data) {
                    $("#section_id_post").html('<option value="">-- Select Section --</option>' + data);
                    if (selectedSection) {
                        $("#section_id_post").val(selectedSection);
                    }
                }
            });
        } else {
            $("#section_id_post").html('<option value="">-- Select Section --</option>');
        }
    }

    // On category change
    $("#category_id_post").change(function () {
        var categoryId = $(this).val();
        loadSubcategories(categoryId);
    });

    // On subcategory change
    $("#sub_category_id_post").change(function () {
        var subCategoryId = $(this).val();
        loadSections(subCategoryId);
    });

    // ---- Auto-load values on edit ----
    <?php if (!empty($postResult['category'])) { ?>
        var editCategory = "<?= $postResult['category'] ?>";
        var editSubcategory = "<?= $postResult['subcategory'] ?? '' ?>";
        var editSection = "<?= $postResult['section'] ?? '' ?>";

        $("#category_id_post").val(editCategory);

        // Proper chain: first subcategory load, then section load
        loadSubcategories(editCategory, editSubcategory, function(subCatId){
            if(subCatId){
                loadSections(subCatId, editSection);
            }
        });
    <?php } ?>

});

    </script>