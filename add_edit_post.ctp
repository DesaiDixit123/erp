<?php
$walexam = $this->Member->wall_parent_category_list('Exam');
if (!empty($viewData)) {
   $examdata = $this->Member->exam_wall_data($viewData['id']);
} else {
   $examdata = '';
}
?>
<?php echo $this->Html->script(webURL . 'ckeditor/ckeditor.js');
$adminLogType = $this->request->session()->read("Admincheck.admin-logtype"); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo webURL; ?>admin/css/rating.css">
<?php


if (!empty($viewData['college_id'])) {
   $postby = $this->Member->post_by_clg($viewData['college_id']);
} else if (!empty($viewData['company_id'])) {
   $postby = $this->Member->post_by_cmp($viewData['company_id']);
} else {
   $postby = 'LifeSet';
}
$seodata = '';
if (!empty($viewData)) {
   $seodata = $this->Member->wall_seo_data($viewData['id']);
}
if (isset($_GET['type'])) {
   $paction = $_GET['type'];
} else {
   $paction = '';
}
?>

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
                        enctype="multipart/form-data" onChange="show_html();">
                        <div class="row">
                           <div class="lwhitebg">
                              <!-- Form Validation Title -->
                              <div class="">
                                 <h2><?php //echo  $this->Flash->render('acc_alert'); ?></h2>
                              </div>
                              <div class="boxfrom">
                                 <h3 class="col-md-12">General Details </h3> <?php //print_r($_SESSION); ?>
                                 <div class="form-group  event_box" <?php if ($viewData['post_type'] == 'Job' || $viewData['post_type'] == 'Internship' || $viewData['post_type'] == 'Q&A' || $viewData['post_type'] == 'Review' || $viewData['post_type'] == 'Exam' || $viewData['post_type'] == 'GK' || $viewData['post_type'] == 'Personality' || $viewData['post_type'] == 'Survey') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <label class="col-md-12" for="val-username">Heading <span
                                          class="text-danger"></span></label>
                                    <div class="col-md-12">
                                       <input type="text" id="heading" name="title" value="<?php if (!empty($viewData)) {
                                          echo $viewData['title'];
                                       } ?>" onkeyup="show_html();" class="form-control" placeholder="">
                                    </div>
                                 </div>
                                 <div class="job_box" <?php if ($viewData['post_type'] != 'Job' && $viewData['post_type'] != 'Internship') {
                                    echo 'style="display:none;"';
                                 } ?>>

                                    <div class="col-md-12">
                                       <input type="text" id="title1" name="title1" value="<?php if (!empty($viewData)) {
                                          echo $viewData['title'];
                                       } ?>" onkeyup="show_html();" class="form-control" placeholder="Job Title">
                                    </div>
                                 </div>
                                 <div class="job_box">

                                    <div class="col-md-12">
                                       <input type="text" id="company_name" name="company_name" value="<?php if (!empty($viewData)) {
                                          echo $viewData['company_name'];
                                       } ?>" class="form-control" placeholder="Company Name">
                                    </div>
                                 </div>
                                 <div class=" col-md-6">


                                    <select name="post_type" type="" id="post_type" onChange="change_type();"
                                       class="form-control post_type">
                                       <?php $ckmembid = $this->request->session()->read("company_accs.id");
                                       if (!empty($ckmembid)) { ?>
                                          <optgroup label="Type of Post">

                                             <option value="Job" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Job') {
                                                   echo 'selected';
                                                }
                                             } ?>>Job</option>
                                             <option value="Internship" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Internship') {
                                                   echo 'selected';
                                                }
                                             } ?>>
                                                Internship</option>
                                          <?php } else { ?>
                                             <option value="Event" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Event') {
                                                   echo 'selected';
                                                }
                                             } ?>>Event</option>
                                             <option value="Announcement" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Announcement') {
                                                   echo 'selected';
                                                }
                                             } ?>>
                                                Announcement</option>
                                             <option value="Apprenticeship" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Apprenticeship') {
                                                   echo 'selected';
                                                }
                                             } ?>>
                                                Apprenticeship</option>
                                             <option value="Seminar" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Seminar') {
                                                   echo 'selected';
                                                }
                                             } ?>>Seminar
                                             </option>
                                             <option value="Conference" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Conference') {
                                                   echo 'selected';
                                                }
                                             } ?>>
                                                Conference</option>
                                             <option value="Job Fair" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Job Fair') {
                                                   echo 'selected';
                                                }
                                             } ?>>Job Fair
                                             </option>
                                             <option value="Alumina Meet" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Alumina Meet') {
                                                   echo 'selected';
                                                }
                                             } ?>>Alumina
                                                Meet</option>
                                          <?php }
                                       if (!empty($adminLogType)) { ?>
                                             <option value="Job" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Job') {
                                                   echo 'selected';
                                                }
                                             } ?>>Job</option>
                                             <option value="Internship" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Internship') {
                                                   echo 'selected';
                                                }
                                             } ?>>
                                                Internship</option>
                                             <option value="Review" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Review') {
                                                   echo 'selected';
                                                }
                                             } ?>>Review
                                             </option>
                                             <option value="Q&A" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Q&A') {
                                                   echo 'selected';
                                                }
                                             } ?>>Q&A </option>
                                             <option value="Exam" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Exam') {
                                                   echo 'selected';
                                                }
                                             } ?>>Exam </option>
                                             <option value="Personality" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Personality') {
                                                   echo 'selected';
                                                }
                                             } ?>>
                                                Personality </option>
                                             <option value="Survey" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Survey') {
                                                   echo 'selected';
                                                }
                                             } ?>>Survey
                                             </option>
                                             <option value="GK" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'GK') {
                                                   echo 'selected';
                                                }
                                             } ?>> GK & CA
                                             </option>
                                             <option value="Self Assessment" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Self Assessment') {
                                                   echo 'selected';
                                                }
                                             } ?>>Self
                                                Assessment </option>
                                             <option value="Apprenticeship" <?php if (!empty($viewData)) {
                                                if ($viewData['post_type'] == 'Apprenticeship') {
                                                   echo 'selected';
                                                }
                                             } ?>>
                                                Apprenticeship </option>
                                          </optgroup>
                                       <?php } ?>
                                    </select>

                                 </div>
                                 <div class="col-md-6 profilephoto" <?php if ($viewData['post_type'] == 'Job' || $viewData['post_type'] == 'Internship') {
                                    echo 'style="display:none;"';
                                 } ?>>

                                    <div class="col-md-12">
                                       <input type="file" id='uploadImage1' onchange="PreviewImage(1);" name="postimage"
                                          value="" class="form-control" placeholder="Upload Photo">
                                       <input type="hidden" id="val-name" name="saveimage" value="<?php if (!empty($viewData)) {
                                          echo $viewData['image'];
                                       } ?>" class="form-control" placeholder="Upload Photo">
                                    </div>
                                 </div>
                                 <input type="hidden" id="val-name" name="id" value="<?php if (!empty($viewData)) {
                                    if ($paction != 'copy') {
                                       echo $viewData['id'];
                                    }
                                 } ?>" class="form-control" placeholder="">
                                 <div class="event_box" <?php if ($viewData['post_type'] == 'Job' || $viewData['post_type'] == 'Internship' || $viewData['post_type'] == 'Q&A' || $viewData['post_type'] == 'Review' || $viewData['post_type'] == 'Exam' || $viewData['post_type'] == 'GK' || $viewData['post_type'] == 'Personality' || $viewData['post_type'] == 'Survey') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-username">Url link<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" id="post_url" name="post_url" value="<?php if (!empty($viewData)) {
                                             echo $viewData['post_url'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <!--<div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-skill">Region<span class="text-danger"></span></label>
                                       </div>-->
                                    <div class="form-group col-md-12">
                                       <div class="form-group col-md-4">
                                          <label class="col-md-12" for="val-username">Pincode<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <input type="number" id="pincode" onkeyup="show_html();"
                                                onkeypress="return isNumber(event)" onkeyup="check_pincode();"
                                                name="pincode" value="<?php if (!empty($viewData)) {
                                                   echo $viewData['pincode'];
                                                } ?>" class="form-control" placeholder="">
                                          </div>
                                       </div>
                                       <div class="form-group col-md-4">
                                          <label class="col-md-12" for="val-username">State<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <input type="text" id="state" onkeyup="show_html();" name="state" value="<?php if (!empty($viewData)) {
                                                echo $viewData['state'];
                                             } ?>" class="form-control" placeholder="">
                                          </div>
                                       </div>
                                       <div class="form-group col-md-4">
                                          <label class="col-md-12" for="val-username">District<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <input type="text" id="district" onkeyup="show_html();" name="district"
                                                value="<?php if (!empty($viewData)) {
                                                   echo $viewData['district'];
                                                } ?>" class="form-control" placeholder="">
                                          </div>
                                       </div>
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
                                 <div class="review_box" <?php if ($viewData['post_type'] != 'Review') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <div class="inc">
                                       <div class="controls">
                                          <div class="form-group col-md-6">
                                             <label class="col-md-12" for="val-skill">Category<span
                                                   class="text-danger"></span></label>
                                             <div class="col-md-12">
                                                <?php
                                                $wallcat_list = $this->Member->review_category_list();
                                                ?>
                                                <select id="function" data-placeholder="Choose a function.."
                                                   style="width: 250px;" name="review_category"
                                                   class="form-control function select-chosen">
                                                   <option value="">Select</option>
                                                   <?php foreach ($wallcat_list as $wallcat_lists) { ?>
                                                      <option value="<?php echo $wallcat_lists['id']; ?>" <?php if (!empty($wallcat_lists)) {
                                                            if ($viewData['category'] == $wallcat_lists['id']) {
                                                               echo 'selected';
                                                            }
                                                         } ?>><?php echo $wallcat_lists['name']; ?></option>
                                                   <?php } ?>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="form-group col-md-12">
                                             <label class="col-md-12" for="val-username">Question<span
                                                   class="text-danger"></span></label>
                                             <div class="col-md-12">
                                                <input type="text" id="question" onkeyup="show_html();" name="question"
                                                   value="<?php if (!empty($viewData)) {
                                                      echo $viewData['question'];
                                                   } ?>" class="form-control" placeholder="">
                                             </div>
                                          </div>
                                          <?php if (!empty($viewData['options'])) {
                                             $options = explode(';;', $viewData['options']);
                                             $rating = explode(';;', $viewData['rating']);
                                             for ($qi = 0; count($options) > $qi; $qi++) {
                                                ?>
                                                <div class="row form-group ">
                                                   <div class="col-md-10">
                                                      <label class="col-md-12" for="val-username">Option <span
                                                            class="text-danger">*</span></label>
                                                      <div class="col-md-12">
                                                         <input type="text" id="option" onkeyup="show_html();" required
                                                            name="options[]" value="<?php echo $options[$qi]; ?>"
                                                            class="form-control" placeholder="">
                                                      </div>
                                                   </div>
                                                   <a href="#" class="btn btn-danger remove_this btn-xs"> - remove</a>
                                                </div>
                                             <?php }
                                          } ?>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <a class="btn btn-success add-more  btn-xs  pull-right" id="append">+ Add
                                          More</a>
                                    </div>
                                 </div>
                                 <div class="quest_box" <?php if ($viewData['post_type'] != 'Q&A') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <div class="inc1">
                                       <div class="controls1">
                                          <div class="form-group col-md-6">
                                             <label class="col-md-12" for="val-skill">Category<span
                                                   class="text-danger"></span></label>
                                             <div class="col-md-12">
                                                <?php
                                                $walQA_list = $this->Member->QA_category_list();
                                                ?>
                                                <select id="function" data-placeholder="Choose a function.."
                                                   style="width: 250px;" name="qna_category"
                                                   class="form-control function select-chosen">
                                                   <option value="">Select</option>
                                                   <?php foreach ($walQA_list as $walQA_lists) { ?>
                                                      <option value="<?php echo $walQA_lists['id']; ?>" <?php if (!empty($walQA_lists)) {
                                                            if ($viewData['category'] == $walQA_lists['id']) {
                                                               echo 'selected';
                                                            }
                                                         } ?>><?php echo $walQA_lists['name']; ?></option>
                                                   <?php } ?>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="form-group col-md-12">
                                             <label class="col-md-12" for="val-username">Question<span
                                                   class="text-danger"></span></label>
                                             <div class="col-md-12">
                                                <input type="text" id="objquestion" name="objquestion" value="<?php if (!empty($viewData)) {
                                                   echo $viewData['objquestion'];
                                                } ?>" onkeyup="show_html();" class="form-control" placeholder="">
                                             </div>
                                          </div>
                                          <?php if (!empty($viewData['answer'])) {
                                             $answer = explode(';;', $viewData['answer']);
                                             $right_answer = explode(';;', $viewData['right_answer']);
                                             for ($qi1 = 0; count($answer) > $qi1; $qi1++) {
                                                ?>
                                                <div class="form-group row">
                                                   <div class="col-md-7">
                                                      <label class="col-md-12" for="val-username">Answer <span
                                                            class="text-danger"></span></label>
                                                      <div class="col-md-12">
                                                         <input type="text" id="answer" required name="answer[]" value="<?php if (isset($answer[$qi1])) {
                                                            echo $answer[$qi1];
                                                         } ?>" class="form-control answer" placeholder=""
                                                            onkeyup="show_html();">
                                                      </div>
                                                   </div>
                                                   <div class="col-md-4">
                                                      <label class="col-md-12" for="val-username">&nbsp; <span
                                                            class="text-danger"></span></label>
                                                      <div class="col-md-12">
                                                         <select id="right_answer" name="right_answer[]"
                                                            onkeyup="show_html();" class="form-control">
                                                            <option value="0" <?php if (!empty($right_answer[$qi1])) {
                                                               if ($right_answer[$qi1] == '0') {
                                                                  echo 'selected';
                                                               }
                                                            } ?>>Wrong
                                                            </option>
                                                            <option value="1" <?php if (!empty($right_answer[$qi1])) {
                                                               if ($right_answer[$qi1] == '1') {
                                                                  echo 'selected';
                                                               }
                                                            } ?>>Right
                                                            </option>
                                                         </select>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="btn btn-danger removethis btn-xs"> - remove</a>
                                                </div>
                                             <?php }
                                          } ?>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <a class="btn btn-success addmore  btn-xs  pull-right" id="append">+ Add More</a>
                                    </div>
                                 </div>
                                 <div class="event_box" <?php if ($viewData['post_type'] == 'Job' || $viewData['post_type'] == 'Internship' || $viewData['post_type'] == 'Q&A' || $viewData['post_type'] == 'Review' || $viewData['post_type'] == 'Exam' || $viewData['post_type'] == 'GK' || $viewData['post_type'] == 'Personality' || $viewData['post_type'] == 'Survey') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Interest/Hobbies<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="hobbies" name="hobbies" value="<?php if (!empty($viewData)) {
                                             echo $viewData['hobbies'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-username">Short Description<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <textarea rows="10" name="description" onkeyup="show_html();" id="editor-1"
                                             value="" class="form-control editor" placeholder=""><?php if (!empty($viewData)) {
                                                echo $viewData['description'];
                                             } ?></textarea>
                                          <script type="text/javascript">
                                             CKEDITOR.replace('editor1', {
                                                extraPlugins: 'imageuploader'
                                             });
                                          </script>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="job_box" <?php if ($viewData['post_type'] != 'Job' && $viewData['post_type'] != 'Internship' || $viewData['post_type'] == 'Q&A' || $viewData['post_type'] == 'Review') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <div class=" col-md-6">


                                       <select id="industry" data-placeholder="Choose a industry.."
                                          style="width: 250px;" name="industry"
                                          class="form-control industry select-chosen">
                                          <optgroup label="Select Industry">
                                             <?php
                                             $industry = $this->Member->industrylist($viewData['course_id']);
                                             ?>
                                             <?php foreach ($industry as $industrys) { ?>


                                                <option value="<?php echo $industrys['name']; ?>" <?php if (!empty($viewData)) {
                                                      if ($viewData['industry'] == $industrys['name']) {
                                                         echo 'selected';
                                                      }
                                                   } ?>><?php echo $industrys['name']; ?></option>
                                             </optgroup>
                                          <?php } ?>
                                       </select>

                                    </div>
                                    <div class="col-md-12">


                                       <select id="function" data-placeholder="Choose a function.."
                                          style="width: 250px;" name="function"
                                          class="form-control function select-chosen">
                                          <optgroup label="Function">
                                             <?php
                                             $function = $this->Member->functionlist($viewData['course_id']);
                                             ?>
                                             <?php foreach ($function as $functions) { ?>
                                                <option value="<?php echo $functions['name']; ?>" <?php if (!empty($viewData)) {
                                                      if ($viewData['function'] == $functions['name']) {
                                                         echo 'selected';
                                                      }
                                                   } ?>><?php echo $functions['name']; ?></option>
                                             </optgroup>
                                          <?php } ?>
                                       </select>

                                    </div>
                                    <div class="form-group col-md-6">

                                       <div class="col-md-12">
                                          <select name="role" id="role" class="form-control">
                                             <optgroup label="Select Role">
                                                <option value="Entry" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == 'Entry') {
                                                      echo 'selected';
                                                   }
                                                } ?>>Entry
                                                </option>
                                                <option value="Mid" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == 'Mid') {
                                                      echo 'selected';
                                                   }
                                                } ?>>Mid</option>
                                                <option value="Executive" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == 'Executive') {
                                                      echo 'selected';
                                                   }
                                                } ?>>Executive
                                                </option>
                                                <option value="Director" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == 'Director') {
                                                      echo 'selected';
                                                   }
                                                } ?>>Director
                                                </option>
                                                <option value="Top Level" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == 'Top Level') {
                                                      echo 'selected';
                                                   }
                                                } ?>>Top Level
                                                </option>
                                             </optgroup>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">

                                       <div class="col-md-12">
                                          <select name="past_experience" id="past_experience" class="form-control">
                                             <optgroup label="Experience">
                                                <option value="0-2 Years" <?php if (!empty($viewData)) {
                                                   if ($viewData['past_experience'] == '0-2 Years') {
                                                      echo 'selected';
                                                   }
                                                } ?>>0-2 Years</option>
                                                <option value="2-5 Years" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == '2-5 Years') {
                                                      echo 'selected';
                                                   }
                                                } ?>>2-5 Years
                                                </option>
                                                <option value="5-10 Years" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == '5-10 Years') {
                                                      echo 'selected';
                                                   }
                                                } ?>>5-10
                                                   Years</option>
                                                <option value="10-15 Years" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == '10-15 Years') {
                                                      echo 'selected';
                                                   }
                                                } ?>>10-15
                                                   Years</option>
                                                <option value="15+ Years" <?php if (!empty($viewData)) {
                                                   if ($viewData['role'] == '15+ Years') {
                                                      echo 'selected';
                                                   }
                                                } ?>>15+ Years
                                                </option>
                                             </optgroup>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group">

                                       <div class="col-md-6">
                                          <input type="text" onkeyup="show_html();" id="job_location"
                                             name="job_location" value="<?php if (!empty($viewData)) {
                                                echo $viewData['job_location'];
                                             } ?>" class="form-control" placeholder="Job Location">
                                       </div>

                                       <div class="col-md-6">
                                          <input type="text" onkeyup="show_html();" id="skill" name="skill" value="<?php if (!empty($viewData)) {
                                             echo $viewData['skill'];
                                          } ?>" class="form-control" placeholder="Skills">
                                       </div>

                                    </div>

                                    <div class="form-group">
                                       <div class="col-md-6">
                                          <label class="col-md-12" for="val-skill"><span class="text-danger"></span>Job
                                             Type</label>
                                          <div class="col-md-12">
                                             <label>
                                                <input type="radio" checked name="job_type" id="job_type"
                                                   value="It's a Desk Job" <?php if (!empty($viewData)) {
                                                      if ($viewData['job_type'] == "It's a Desk Job") {
                                                         echo 'checked';
                                                      }
                                                   } ?>
                                                   onClick="show_html();"> Desk Job
                                             </label>
                                             <label>
                                                <input type="radio" name="job_type" id="job_type"
                                                   value="It's a Field Job" <?php if (!empty($viewData)) {
                                                      if ($viewData['job_type'] == "It's a Field Job") {
                                                         echo 'checked';
                                                      }
                                                   } ?>
                                                   onClick="show_html();"> Field Job
                                             </label>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <label class="col-md-12" for="val-skill">Clients to Manage<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <label>
                                                <input type="radio" checked name="client_to_manage"
                                                   id="client_to_manage" value="Internal" <?php if (!empty($viewData)) {
                                                      if ($viewData['client_to_manage'] == 'Internal') {
                                                         echo 'checked';
                                                      }
                                                   } ?> onClick="show_html();"> Internal
                                             </label>
                                             <label>
                                                <input type="radio" name="client_to_manage" id="client_to_manage"
                                                   value="External" <?php if (!empty($viewData)) {
                                                      if ($viewData['client_to_manage'] == 'External') {
                                                         echo 'checked';
                                                      }
                                                   } ?>
                                                   onClick="show_html();"> External
                                             </label>
                                          </div>
                                       </div>
                                       <div class="col-md-12">
                                          <label class="col-md-12" for="val-skill">Capacity<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <label>
                                                <input type="radio" checked name="capacity" id="capacity"
                                                   value="Individual Role" <?php if (!empty($viewData)) {
                                                      if ($viewData['capacity'] == 'Individual Role') {
                                                         echo 'checked';
                                                      }
                                                   } ?>
                                                   onClick="show_html();"> Individual Role
                                             </label>
                                             <label>
                                                <input type="radio" name="capacity" id="capacity" value="Team Role"
                                                   <?php if (!empty($viewData)) {
                                                      if ($viewData['capacity'] == 'Team Role') {
                                                         echo 'checked';
                                                      }
                                                   } ?> onClick="show_html();"> Team Role
                                             </label>

                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-group">

                                       <div class="col-md-6">
                                          <input type="text" id="working_days" name="working_days" value="<?php if (!empty($viewData)) {
                                             echo $viewData['working_days'];
                                          } ?>" class="form-control" placeholder="Working Days">
                                       </div>
                                       <div class="col-md-6">
                                          <input type="text" id="work_time" name="work_time" value="<?php if (!empty($viewData)) {
                                             echo $viewData['work_time'];
                                          } ?>" class="form-control" placeholder="Work Time">
                                       </div>
                                    </div>
                                    <div class="row ">


                                       <div class="col-md-6">
                                          <input type="text" class="form-control" onkeyup="show_html();"
                                             name="fixed_salary" id="fixed_salary" value="<?php if (!empty($viewData)) {
                                                echo $viewData['fixed_salary'];
                                             } ?>" placeholder="Yearly Salary">

                                       </div>

                                       <div class="col-md-6">
                                          <input type="text" onkeyup="show_html();" id="variable_sallery"
                                             name="variable_sallery" value="<?php if (!empty($viewData)) {
                                                echo $viewData['variable_sallery'];
                                             } ?>" class="form-control" placeholder="Perks & Benefits">
                                       </div>
                                    </div>
                                    <div class="row">

                                       <div class="col-md-12">
                                          <textarea rows="10" name="description1" row="3" onkeyup="show_html();"
                                             id="editor-1" value="" style="height:80px!important"
                                             class="form-control editor description1" placeholder="Short Description"><?php if (!empty($viewData)) {
                                                echo $viewData['description'];
                                             } ?></textarea>
                                          <script type="text/javascript">
                                             CKEDITOR.replace('editor1', {
                                                extraPlugins: 'imageuploader'
                                             });
                                          </script>
                                       </div>
                                    </div>
                                 </div>
                                 <?php
                                 $walsurvey_list = $this->Member->wall_parent_category_list('Survey');
                                 if (!empty($viewData)) {
                                    $surveydata = $this->Member->survey_wall_data($viewData['id']);
                                 } else {
                                    $surveydata = '';
                                 }
                                 ?>
                                 <div class="survey_box" <?php if ($viewData['post_type'] != 'Survey') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <input type="hidden" id="val-name" name="surveyid" value="<?php if (!empty($surveydata)) {
                                       if ($paction != 'copy') {
                                          echo $surveydata['id'];
                                       }
                                    } ?>" class="form-control" placeholder="">
                                    <div class="survey_inc">
                                       <div class="survey_controls">
                                          <div class="form-group col-md-6">
                                             <label class="col-md-12" for="val-skill">Category<span
                                                   class="text-danger"></span></label>
                                             <div class="col-md-12">
                                                <select id="function" data-placeholder="Choose a function.."
                                                   style="width: 250px;" name="survey_cat_id"
                                                   class="form-control function select-chosen">
                                                   <option value="">Select</option>
                                                   <?php foreach ($walsurvey_list as $walsurvey_lists) { ?>
                                                      <option value="<?php echo $walsurvey_lists['id']; ?>" <?php if (!empty($surveydata)) {
                                                            if ($surveydata['cat_id'] == $walsurvey_lists['id']) {
                                                               echo 'selected';
                                                            }
                                                         } ?>><?php echo $walsurvey_lists['name']; ?>
                                                      </option>
                                                   <?php } ?>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="form-group col-md-12">
                                             <label class="col-md-12" for="val-username">Question<span
                                                   class="text-danger"></span></label>
                                             <div class="col-md-12">
                                                <input type="text" id="surveyquestion" name="survey_question" value="<?php if (!empty($surveydata)) {
                                                   echo $surveydata['question'];
                                                } ?>" onkeyup="show_survey_html();" class="form-control"
                                                   placeholder="">
                                             </div>
                                          </div>
                                          <?php if (!empty($surveydata['answer'])) {
                                             $suranswer = explode(';;', $surveydata['answer']);
                                             $surright_answer = explode(';;', $surveydata['right_answer']);
                                             for ($sqi1 = 0; count($suranswer) > $sqi1; $sqi1++) {
                                                ?>
                                                <div class="row form-group ">
                                                   <div class="col-md-7">
                                                      <label class="col-md-12" for="val-username">Answer <span
                                                            class="text-danger"></span></label>
                                                      <div class="col-md-12">
                                                         <input type="text" id="survey_answer" required name="survey_answer[]"
                                                            value="<?php if (isset($suranswer[$sqi1])) {
                                                               echo $suranswer[$sqi1];
                                                            } ?>" class="form-control survey_answer" placeholder=""
                                                            onkeyup="show_survey_html();">
                                                      </div>
                                                   </div>
                                                   <div class="col-md-4">
                                                      <label class="col-md-12" for="val-username">&nbsp; <span
                                                            class="text-danger"></span></label>
                                                      <div class="col-md-12">
                                                         <select id="survey_right_answer" name="survey_right_answer[]"
                                                            onkeyup="show_survey_html();" class="form-control">
                                                            <option value="0" <?php if (!empty($surright_answer[$sqi1])) {
                                                               if ($surright_answer[$sqi1] == '0') {
                                                                  echo 'selected';
                                                               }
                                                            } ?>>Wrong
                                                            </option>
                                                            <option value="1" <?php if (!empty($surright_answer[$sqi1])) {
                                                               if ($surright_answer[$sqi1] == '1') {
                                                                  echo 'selected';
                                                               }
                                                            } ?>>Right
                                                            </option>
                                                         </select>
                                                      </div>
                                                   </div>
                                                   <a href="#" class="btn btn-danger survey_removethis btn-xs"> - remove</a>
                                                </div>
                                             <?php }
                                          } ?>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <a class="btn btn-success survey_addmore  btn-xs  pull-right"
                                          id="survey_append">+ Add More</a>
                                    </div>
                                 </div>
                                 <div class="exam_box" <?php if ($viewData['post_type'] != 'Exam') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <input type="hidden" id="val-name" name="examid" value="<?php if (!empty($examdata)) {
                                       if ($paction != 'copy') {
                                          echo $examdata['id'];
                                       }
                                    } ?>">
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Category<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <select id="exam_cat_id" data-placeholder="Choose a industry.."
                                             style="width: 250px;" name="exam_cat_id" class="form-control select-chosen"
                                             onChange="other_exam_category(this.value)">
                                             <?php foreach ($walexam as $walexams) { ?>
                                                <option value="<?php echo $walexams['id']; ?>" <?php if (!empty($examdata)) {
                                                      if ($examdata['cat_id'] == $walexams['id']) {
                                                         echo 'selected';
                                                      }
                                                   } ?>>
                                                   <?php echo $walexams['name']; ?>
                                                </option>
                                             <?php } ?>
                                             <option value="Other" <?php if (!empty($examdata)) {
                                                if ($examdata['cat_id'] == 'Other') {
                                                   echo 'selected';
                                                }
                                             } ?>>Other</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6 other_category" style="<?php if (!empty($examdata)) {
                                       if ($examdata['cat_id'] != 'Other') {
                                          echo 'display:none;';
                                       }
                                    } else {
                                       echo 'display:none;';
                                    } ?>">
                                       <label class="col-md-12" for="val-skill">&nbsp; <span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" id="other_exam_category" name="other_exam_category" value="<?php if (!empty($examdata)) {
                                             echo $examdata['other_exam_category'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Exam Level<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <select name="exam_level" id="exam_level"
                                             onChange="other_exam_level(this.value)" class="form-control">
                                             <option value="10th Pass" <?php if (!empty($examdata)) {
                                                if ($examdata['exam_level'] == '10th Pass') {
                                                   echo 'selected';
                                                }
                                             } ?>>10th
                                                Pass</option>
                                             <option value="12th Pass" <?php if (!empty($examdata)) {
                                                if ($examdata['exam_level'] == '12th Pass') {
                                                   echo 'selected';
                                                }
                                             } ?>>12th
                                                Pass</option>
                                             <option value="UG" <?php if (!empty($examdata)) {
                                                if ($examdata['exam_level'] == 'UG') {
                                                   echo 'selected';
                                                }
                                             } ?>>UG</option>
                                             <option value="PG" <?php if (!empty($examdata)) {
                                                if ($examdata['exam_level'] == 'PG') {
                                                   echo 'selected';
                                                }
                                             } ?>>PG</option>
                                             <option value="Other" <?php if (!empty($examdata)) {
                                                if ($examdata['exam_level'] == 'Other') {
                                                   echo 'selected';
                                                }
                                             } ?>>Other
                                             </option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6 other_exam" style="<?php if (!empty($examdata)) {
                                       if ($examdata['exam_level'] != 'Other') {
                                          echo 'display:none;';
                                       }
                                    } else {
                                       echo 'display:none;';
                                    } ?>">
                                       <label class="col-md-12" for="val-skill">&nbsp; <span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" id="other_exam_level" name="other_exam_level" value="<?php if (!empty($examdata)) {
                                             echo $examdata['other_exam_level'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Exam Name<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="exam_name" name="exam_name"
                                             value="<?php if (!empty($examdata)) {
                                                echo $examdata['exam_name'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Name of Post<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="name_of_post"
                                             name="name_of_post" value="<?php if (!empty($examdata)) {
                                                echo $examdata['name_of_post'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">1st Announcement Date<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="date" onkeyup="show_html();" id="announcement_date"
                                             name="announcement_date" value="<?php if (!empty($examdata)) {
                                                echo $examdata['announcement_date'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Last Date of Form Filling<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="date" onkeyup="show_html();" id="last_date_form_filling"
                                             name="last_date_form_filling" value="<?php if (!empty($examdata)) {
                                                echo $examdata['last_date_form_filling'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Admit Card<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="admit_card" name="admit_card"
                                             value="<?php if (!empty($examdata)) {
                                                echo $examdata['admit_card'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Exam Date<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="date" onkeyup="show_html();" id="exam_date" name="exam_date"
                                             value="<?php if (!empty($examdata)) {
                                                echo $examdata['exam_date'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Exam Time<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="time" onkeyup="show_html();" id="exam_time" name="exam_time"
                                             value="<?php if (!empty($examdata)) {
                                                echo $examdata['exam_time'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Result<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="result" name="result" value="<?php if (!empty($examdata)) {
                                             echo $examdata['result'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Fees<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="fees" name="fees" value="<?php if (!empty($examdata)) {
                                             echo $examdata['fees'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Vacancy/Seats<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="vacancy" name="vacancy" value="<?php if (!empty($examdata)) {
                                             echo $examdata['vacancy'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Exam Pattern<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="exam_pattern"
                                             name="exam_pattern" value="<?php if (!empty($examdata)) {
                                                echo $examdata['exam_pattern'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Cutoff<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="cutoff" name="cutoff" value="<?php if (!empty($examdata)) {
                                             echo $examdata['cutoff'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Eligibility<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="eligibility" name="eligibility"
                                             value="<?php if (!empty($examdata)) {
                                                echo $examdata['eligibility'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Age Limit<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="age_limit" name="age_limit"
                                             value="<?php if (!empty($examdata)) {
                                                echo $examdata['age_limit'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-username">Description<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <textarea rows="10" name="exam_description" onkeyup="show_html();"
                                             id="editor2" value="" class="form-control editor exam_description"
                                             placeholder=""><?php if (!empty($examdata)) {
                                                echo $examdata['description'];
                                             } ?></textarea>
                                          <script type="text/javascript">
                                             CKEDITOR.replace('editor-2', {
                                                extraPlugins: 'imageuploader'
                                             });
                                          </script>
                                       </div>
                                    </div>
                                 </div>
                                 <?php
                                 $walpersonality = $this->Member->wall_parent_category_list('Personality');
                                 if (!empty($viewData)) {
                                    $personalitydata = $this->Member->personality_wall_data($viewData['id']);
                                 } else {
                                    $personalitydata = '';
                                 }
                                 ?>
                                 <div class="personality_box" <?php if ($viewData['post_type'] != 'Personality') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <input type="hidden" id="val-name" name="personalityid" value="<?php if (!empty($personalitydata)) {
                                       if ($paction != 'copy') {
                                          echo $personalitydata['id'];
                                       }
                                    } ?>">
                                    <div class="form-group col-md-6" style="display:none;">
                                       <label class="col-md-12" for="val-skill">Category<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <select id="personality_cat_id" data-placeholder="Choose a industry.."
                                             style="width: 250px;" name="personality_cat_id"
                                             class="form-control select-chosen">
                                             <?php foreach ($walpersonality as $walpersonalitys) { ?>
                                                <option value="<?php echo $walpersonalitys['id']; ?>" <?php if (!empty($personalitydata)) {
                                                      if ($personalitydata['cat_id'] == $walpersonalitys['id']) {
                                                         echo 'selected';
                                                      }
                                                   } ?>><?php echo $walpersonalitys['name']; ?></option>
                                             <?php } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="personality_yesis">Yes Is <span
                                             class="text-danger">*</span></label>
                                       <div class="col-md-12">
                                          <select id="personality_yesis" name="personality_yesis" data-placeholder="Choose a trait..."
                                             style="width: 250px;" class="form-control select-chosen">
                                             <option value="">-- Select Yes Is --</option>
                                             <option value="Extraversion (E)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Extraversion (E)') ? 'selected' : ''; ?>>
                                                Extraversion (E)</option>
                                             <option value="Introversion (I)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Introversion (I)') ? 'selected' : ''; ?>>
                                                Introversion (I)</option>
                                             <option value="Intuition (N)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Intuition (N)') ? 'selected' : ''; ?>>
                                                Intuition (N)</option>
                                             <option value="Sensing (S)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Sensing (S)') ? 'selected' : ''; ?>>Sensing
                                                (S)</option>
                                             <option value="Feeling (F)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Feeling (F)') ? 'selected' : ''; ?>>Feeling
                                                (F)</option>
                                             <option value="Thinking (T)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Thinking (T)') ? 'selected' : ''; ?>>
                                                Thinking (T)</option>
                                             <option value="Perception (P)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Perception (P)') ? 'selected' : ''; ?>>
                                                Perception (P)</option>
                                             <option value="Judgment (J)" <?php echo (!empty($personalitydata) && $personalitydata['yesis'] == 'Judgment (J)') ? 'selected' : ''; ?>>
                                                Judgment (J)</option>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="personality_nois">No Is <span
                                             class="text-danger">*</span></label>
                                       <div class="col-md-12">
                                          <select id="personality_nois" name="personality_nois" data-placeholder="Choose a trait..."
                                             style="width: 250px;" class="form-control select-chosen">
                                             <option value="">-- Select No Is --</option>
                                             <option value="Extraversion (E)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Extraversion (E)') ? 'selected' : ''; ?>>
                                                Extraversion (E)</option>
                                             <option value="Introversion (I)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Introversion (I)') ? 'selected' : ''; ?>>
                                                Introversion (I)</option>
                                             <option value="Intuition (N)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Intuition (N)') ? 'selected' : ''; ?>>
                                                Intuition (N)</option>
                                             <option value="Sensing (S)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Sensing (S)') ? 'selected' : ''; ?>>Sensing
                                                (S)</option>
                                             <option value="Feeling (F)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Feeling (F)') ? 'selected' : ''; ?>>Feeling
                                                (F)</option>
                                             <option value="Thinking (T)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Thinking (T)') ? 'selected' : ''; ?>>
                                                Thinking (T)</option>
                                             <option value="Perception (P)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Perception (P)') ? 'selected' : ''; ?>>
                                                Perception (P)</option>
                                             <option value="Judgment (J)" <?php echo (!empty($personalitydata) && $personalitydata['nois'] == 'Judgment (J)') ? 'selected' : ''; ?>>
                                                Judgment (J)</option>
                                          </select>
                                       </div>
                                    </div>

                          
    <!-- <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-skill">Yesis<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="personality_yesis"
                                             name="personality_yesis" value="<?php if (!empty($personalitydata)) {
                                                echo $personalitydata['yesis'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                     -->

                                    <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-skill">Question<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="personality_question"
                                             name="personality_question" value="<?php if (!empty($personalitydata)) {
                                                echo $personalitydata['name'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6" style="display:none;">
                                       <label class="col-md-12" for="val-skill">Value<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="number" onkeyup="show_html();" id="personality_answer"
                                             name="personality_answer" value="<?php if (!empty($personalitydata)) {
                                                echo $personalitydata['answer'];
                                             } else {
                                                echo '5';
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                 </div>
                                 <?php
                                 $walgk = $this->Member->wall_parent_category_list('GK');
                                 if (!empty($viewData)) {
                                    $gkdata = $this->Member->gk_wall_data($viewData['id']);
                                 } else {
                                    $gkdata = '';
                                 }
                                 ?>
                                 <div class="gk_box" <?php if ($viewData['post_type'] != 'GK') {
                                    echo 'style="display:none;"';
                                 } ?>>
                                    <input type="hidden" id="val-name" name="gkid" value="<?php if (!empty($gkdata)) {
                                       if ($paction != 'copy') {
                                          echo $gkdata['id'];
                                       }
                                    } ?>">
                                    <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-username">Heading <span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" id="heading" name="gk_title" value="<?php if (!empty($gkdata)) {
                                             echo $gkdata['title'];
                                          } ?>" onkeyup="show_html();" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-username">Url link<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" id="post_url" name="gk_post_url" value="<?php if (!empty($gkdata)) {
                                             echo $gkdata['post_url'];
                                          } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                       <div class="form-group col-md-4">
                                          <label class="col-md-12" for="val-username">Date<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <input type="date" id="gk_pincode" onkeyup="show_html();"
                                                onkeypress="return isNumber(event)" onkeyup="check_pincode();"
                                                name="gk_pincode" value="<?php if (!empty($gkdata)) {
                                                   echo $gkdata['pincode'];
                                                } ?>" class="form-control" placeholder="">
                                          </div>
                                       </div>
                                       <div class="form-group col-md-4">
                                          <label class="col-md-12" for="val-username">State<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <input type="text" id="gk_state" onkeyup="show_html();" name="gk_state"
                                                value="<?php if (!empty($gkdata)) {
                                                   echo $gkdata['state'];
                                                } ?>" class="form-control" placeholder="">
                                          </div>
                                       </div>
                                       <div class="form-group col-md-4">
                                          <label class="col-md-12" for="val-username">District<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <input type="text" id="gk_district" onkeyup="show_html();"
                                                name="gk_district" value="<?php if (!empty($gkdata)) {
                                                   echo $gkdata['district'];
                                                } ?>" class="form-control" placeholder="">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Interest/Hobbies<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <input type="text" onkeyup="show_html();" id="gk_hobbies" name="gk_hobbies"
                                             value="<?php if (!empty($gkdata)) {
                                                echo $gkdata['hobbies'];
                                             } ?>" class="form-control" placeholder="">
                                       </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                       <label class="col-md-12" for="val-username">Short Description<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <textarea rows="10" name="gk_description" onkeyup="show_html();"
                                             id="gk_editor-1" value="" class="form-control editor" placeholder=""><?php if (!empty($gkdata)) {
                                                echo $gkdata['description'];
                                             } ?></textarea>
                                          <script type="text/javascript">
                                             CKEDITOR.replace('gk_editor1', {
                                                extraPlugins: 'imageuploader'
                                             });
                                          </script>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row col-md-12">
                                    <a href="javascript:void(0)" data-toggle="collapse" data-target="#demo"
                                       class="btn btn-secondary collapsed"><i class="fa fa-hourglass-end fa-fw"></i>
                                       Advance Filter </a>
                                 </div>
                                 <div id="demo" class="collapse">
                                    <?php
                                    $coll_id = $this->request->session()->read("Tbl_faculty_members.collegeid");
                                    if (empty($coll_id)) {
                                       ?>
                                       <div class="form-group col-md-6">
                                          <label class="col-md-12" for="val-skill">college<span
                                                class="text-danger"></span></label>
                                          <div class="col-md-12">
                                             <?php $collegelist = $this->Member->collegelist(); ?>
                                             <select name="college_id" id="college_id" onChange="check_college();"
                                                class="form-control college_id">
                                                <option value="">Select</option>
                                                <?php foreach ($collegelist as $collegelists) { ?>
                                                   <option value="<?php echo $collegelists['id']; ?>" <?php if (!empty($viewData)) {
                                                         if ($viewData['college_id'] == $collegelists['id']) {
                                                            echo 'selected';
                                                         }
                                                      } ?>><?php echo $collegelists['name']; ?></option>
                                                <?php } ?>
                                             </select>
                                          </div>
                                       </div>
                                    <?php } else { ?>
                                       <input type="hidden" id="college_id" name="college_id"
                                          value="<?php echo $coll_id; ?>" class="form-control college_id" placeholder="">
                                    <?php } ?>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Stream<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <select id="stream" onChange="check_course();" name="stream"
                                             class=" form-control select_box select_stream">
                                             <option value="">Select</option>
                                             <?php if (!empty($viewData)) {
                                                $clgcourse = $this->Member->clgmscourselist($viewData['college_id']);
                                                $newclgcourse = array_unique($clgcourse);
                                             } else if (!empty($coll_id)) {
                                                $clgcourse = $this->Member->clgmscourselist($coll_id);
                                                $newclgcourse = array_unique($clgcourse);
                                             }
                                             if (!empty($newclgcourse)) {
                                                ?>
                                                <?php foreach ($newclgcourse as $clgcourses) { ?>
                                                   <option value="<?php echo $clgcourses; ?>" <?php if (!empty($viewData)) {
                                                         if ($viewData['stream'] == $clgcourses) {
                                                            echo 'selected';
                                                         }
                                                      } ?>>
                                                      <?php echo $clgcourses; ?>
                                                   </option>
                                                <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Course<span
                                             class="text-danger"></span></label>
                                       <div class="col-md-12">
                                          <select name="course_id" id="course_id" class=" form-control select_course"
                                             onChange="check_year();">
                                             <option value="">Select</option>
                                             <?php
                                             if (!empty($viewData)) {
                                                $clgcourse = $this->Member->clgcourselist($viewData['college_id']);
                                                ?>
                                                <?php foreach ($clgcourse as $clgcourses) { ?>
                                                   <option value="<?php echo $clgcourses['id']; ?>" <?php if (!empty($viewData)) {
                                                         if ($viewData['course_id'] == $clgcourses['id']) {
                                                            echo 'selected';
                                                         }
                                                      } ?>><?php echo $clgcourses['name']; ?></option>
                                                <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                       <label class="col-md-12" for="val-skill">Year<span
                                             class="text-danger">*</span></label>
                                       <div class="col-md-12">
                                          <select id="year" name="year" class="form-control select_year">
                                             <?php
                                             if (!empty($viewData)) {
                                                $duration = $this->Member->coursedetails($viewData['course_id']);
                                                $clyear = $duration['duration'];
                                                ?>
                                                <?php for ($dr = 1; $dr <= $clyear; $dr++) { ?>
                                                   <option value="<?php echo $dr; ?>" <?php if (!empty($viewData)) {
                                                         if ($viewData['year'] == $dr) {
                                                            echo 'selected';
                                                         }
                                                      } ?>>
                                                      <?php echo $dr . ' Year'; ?>
                                                   </option>
                                                <?php }
                                             } ?>
                                          </select>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="form-group col-md-12">

                                    <div class="col-md-12" style="display:none;">
                                       <input type="hidden" name="seo_id" value="<?php if (!empty($seodata)) {
                                          if ($paction != 'copy') {
                                             echo $seodata['id'];
                                          }
                                       } ?>" class="form-control" placeholder="">
                                       <input type="text" name="seo_title" value="<?php if (!empty($seodata)) {
                                          echo $seodata['seo_title'];
                                       } ?>" class="form-control" placeholder="Seo Title" style="margin-bottom:15px">
                                    </div>


                                    <div class="col-md-12" style="display:none;">
                                       <textarea rows="3" name="seo_keyword" id="editor1" value=""
                                          class="form-control description1" placeholder="Seo Keyword"><?php if (!empty($seodata)) {
                                             echo $seodata['seo_keyword'];
                                          } ?></textarea>
                                    </div>

                                    <div class="col-md-12" style="display:none;">
                                       <textarea rows="5" name="seo_description" id="editor1" value=""
                                          class="form-control description1" placeholder="Seo Description"><?php if (!empty($seodata)) {
                                             echo $seodata['seo_description'];
                                          } ?></textarea>
                                    </div>
                                    <div class="col-md-12">

                                       <button type="submit" name="Preview" class="btn btn-primary">Save</button>

                                    </div>
                                 </div>



                              </div>
                           </div>
                           <div class="lgreybg previewbox">
                              <?php if (!empty($viewData)) { ?>
                                 <?php if (!empty($viewData['post_by'])) {
                                    $postby_member = $this->Member->memberdetails($viewData['post_by']);
                                    $postbymember = $postby_member['name'];
                                 } else {
                                    $postbymember = '';
                                 } ?>
                                 <h3 style="margin-left:30px">Preview</h3>
                                 <?php if ($viewData['post_type'] == 'Exam') {
                                    $examdata = $this->Member->exam_wall_data($viewData['id']); ?>
                                    <div class="lswallbolck jobblock">
                                       <div class="col-md-3 pull-right">
                                          <a href="javascript:void(0);" class="btn btn-primary btn-block">Interested </a>
                                       </div>
                                       <span class="lstag"><?php echo $viewData['post_type']; ?></span>
                                       <p class="lsdate"><?php echo date('d M Y', strtotime($viewData['updated'])); ?> |
                                          <img src="<?php echo webURL . 'admin/'; ?>img/icons/share-icon.png"
                                             alt="Lifeset Share">
                                          <?php $bookmark = $this->Member->get_bookmarks($viewData['id']); ?>
                                          <?php if (empty($bookmark)) { ?>
                                             <a href="javascript:void(0);"><img
                                                   src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                   alt="Lifeset Bookmark"></a>
                                          <?php } else if (!empty($adminLogType)) { ?>
                                                <a href="javascript:void(0)"><img
                                                      src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                      alt="Lifeset Bookmark"> </a>
                                          <?php } else { ?>
                                                <a href="javascript:void(0)"><img
                                                      src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon.png"
                                                      alt="Lifeset Bookmark"> </a>
                                          <?php } ?>
                                       </p>
                                       <h3><?php echo $examdata['exam_name']; ?></h3>
                                       <?php if (!empty($examdata['image'])) { ?>
                                          <a href="javascript:void(0);" class="post">
                                             <div class="postbanner">
                                                <img src="<?php echo webURL . 'img/Post/' . $examdata['image']; ?>"
                                                   alt="Lifeset Post" class="<?php echo webURL . 'admin/'; ?>img-responsive">
                                             </div>
                                          </a>
                                       <?php } ?>
                                       <div class="walljobpst">
                                          <ul class="lsdetls">
                                             <?php if (!empty($examdata['exam_level'])) { ?>
                                                <li><span>Exam Level :</span>
                                                   <?php if ($examdata['exam_level'] != 'Other') {
                                                      echo $examdata['exam_level'];
                                                   } else {
                                                      echo $examdata['other_exam_level'];
                                                   } ?>
                                                </li>
                                             <?php }
                                             if (!empty($examdata['name_of_post'])) { ?>
                                                <li><span>Name of Post :</span> <?php echo $examdata['name_of_post']; ?></li>
                                             <?php }
                                             if (!empty($examdata['announcement_date'])) { ?>
                                                <li><span>1st Announcement Date :</span>
                                                   <?php echo date('d-m-Y', strtotime($examdata['announcement_date'])); ?></li>
                                             <?php }
                                             if (!empty($examdata['last_date_form_filling'])) { ?>
                                                <li><span>Last Date of Form Filling :</span>
                                                   <?php echo date('d-m-Y', strtotime($examdata['last_date_form_filling'])); ?>
                                                </li>
                                             <?php }
                                             if (!empty($examdata['admit_card'])) { ?>
                                                <li><span>Admit Card :</span> <?php echo $examdata['admit_card']; ?></li>
                                             <?php }
                                             if (!empty($examdata['exam_date'])) { ?>
                                                <li><span>Exam Date :</span>
                                                   <?php echo date('d-m-Y', strtotime($examdata['exam_date']));
                                                   if (!empty($examdata['exam_time'])) {
                                                      echo ' ' . $examdata['exam_time'];
                                                   } ?>
                                                </li>
                                             <?php }
                                             if (!empty($examdata['fees'])) { ?>
                                                <li><span>Fees :</span> <?php echo $examdata['fees']; ?></li>
                                             <?php }
                                             if (!empty($examdata['result'])) { ?>
                                                <li><span>Result :</span> <?php echo $examdata['result']; ?></li>
                                             <?php }
                                             if (!empty($examdata['vacancy'])) { ?>
                                                <li><span>Vacancy/Seats :</span> <?php echo $examdata['vacancy']; ?></li>
                                             <?php }
                                             if (!empty($examdata['exam_pattern'])) { ?>
                                                <li><span>Exam Pattern :</span> <?php echo $examdata['exam_pattern']; ?></li>
                                             <?php }
                                             if (!empty($examdata['cutoff'])) { ?>
                                                <li><span>Cutoff :</span> <?php echo $examdata['cutoff']; ?></li>
                                             <?php }
                                             if (!empty($examdata['eligibility'])) { ?>
                                                <li><span>Eligibility :</span> <?php echo $examdata['eligibility']; ?></li>
                                             <?php }
                                             if (!empty($examdata['age_limit'])) { ?>
                                                <li><span>Age Limit :</span> <?php echo $examdata['age_limit']; ?></li>
                                             <?php } ?>
                                          </ul>
                                          <?php /*if(!empty($blogs['company_id'])){ ?>
                                     <h5>Company Details: </h5>
                                     <?php $cmpdtl=$this->Member->companydetail($blogs['company_id']); ?>
                                     <ul class="lsdetls">
                                        <?php if(!empty($cmpdtl['name'])){ ?>	
                                        <li><span>Company Name :</span> <?php echo $cmpdtl['name']; ?></li>
                                        <?php } if(!empty($cmpdtl['name'])){ ?>	
                                        <li><span>Phone Number :</span> <?php echo $cmpdtl['mobile']; ?></li>
                                        <?php } if(!empty($cmpdtl['name'])){ ?>	
                                        <li><span>Email Id :</span> <?php echo $cmpdtl['emil']; ?></li>
                                        <?php } ?>	class="viewdec"><?
                                     </ul>
                                     <?php } */ ?>
                                          <p class="viewdec"><?php echo $examdata['description']; ?></p>
                                       </div>
                                    </div>
                                 <?php } else if ($viewData['post_type'] == 'GK') {
                                    $gkdata = $this->Member->gk_wall_data($viewData['id']);
                                    //if(!empty($gkdata['cat_id'])){ $catdta=$this->Member->cat_detail($gkdata['cat_id']); $catname=$catdta['name']; }else{ $catname=''; }
                                    ?>
                                       <div class="lswallbolck jobblock">
                                          <span class="lstag"><?php echo $viewData['post_type']; ?></span>
                                          <p class="lsdate"><?php echo date('d M Y', strtotime($viewData['updated'])); ?> |
                                             <img src="<?php echo webURL . 'admin/'; ?>img/icons/share-icon.png"
                                                alt="Lifeset Share">
                                          <?php $bookmark = $this->Member->get_bookmarks($viewData['id']); ?>
                                          <?php if (empty($bookmark)) { ?>
                                                <a href="javascript:void(0)"><img
                                                      src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                      alt="Lifeset Bookmark"></a>
                                          <?php } else if (!empty($adminLogType)) { ?>
                                                   <a href="javascript:void(0)"><img
                                                         src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                         alt="Lifeset Bookmark"> </a>
                                          <?php } else { ?>
                                                   <a href="javascript:void(0)"><img
                                                         src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon.png"
                                                         alt="Lifeset Bookmark"> </a>
                                          <?php } ?>
                                          </p>
                                          <h3><?php echo $gkdata['title']; ?></h3>
                                       <?php if (!empty($gkdata['image'])) { ?>
                                             <a href="javascript:void(0)" class="post">
                                                <div class="postbanner">
                                                   <img src="<?php echo webURL . 'img/Post/' . $gkdata['image']; ?>"
                                                      alt="Lifeset Post" class="<?php echo webURL . 'admin/'; ?>img-responsive">
                                                </div>
                                             </a>
                                       <?php } ?>
                                          <div class="walljobpst">
                                             <ul class="lsdetls">
                                             <?php if (!empty($gkdata['pincode'])) { ?>
                                                   <li><span>Pincode :</span> <?php echo $gkdata['pincode']; ?></li>
                                             <?php }
                                             if (!empty($gkdata['state'])) { ?>
                                                   <li><span>State :</span> <?php echo $gkdata['state']; ?></li>
                                             <?php }
                                             if (!empty($gkdata['district'])) { ?>
                                                   <li><span>District :</span> <?php echo $gkdata['district']; ?></li>
                                             <?php }
                                             if (!empty($gkdata['hobbies'])) { ?>
                                                   <li><span>Interest/Hobbies :</span> <?php echo $gkdata['hobbies']; ?></li>
                                             <?php } ?>
                                             </ul>
                                             <p><?php echo $gkdata['description']; ?></p>
                                          </div>
                                       </div>
                                 <?php } else if ($viewData['post_type'] == 'Personality') {
                                    $personalitydata = $this->Member->personality_wall_data($viewData['id']);
                                    $persanswer = $this->Member->personality_answer($viewData['id']);
                                    //if(!empty($personalitydata['cat_id'])){ $catdta=$this->Member->cat_detail($personalitydata['cat_id']); $catname=$catdta['name']; } else{ $catname=''; }
                                    ?>
                                          <div class="lswallbolck jobblock">
                                             <span class="lstag"><?php echo $viewData['post_type']; ?></span>
                                             <p class="lsdate"><?php echo date('d M Y', strtotime($viewData['updated'])); ?> |
                                                <img src="<?php echo webURL . 'admin/'; ?>img/icons/share-icon.png"
                                                   alt="Lifeset Share">
                                          <?php $bookmark = $this->Member->get_bookmarks($viewData['id']); ?>
                                          <?php if (empty($bookmark)) { ?>
                                                   <a href="javascript:void(0)"><img
                                                         src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                         alt="Lifeset Bookmark"></a>
                                          <?php } else if (!empty($adminLogType)) { ?>
                                                      <a href="javascript:void(0)"><img
                                                            src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                            alt="Lifeset Bookmark"> </a>
                                          <?php } else { ?>
                                                      <a href="javascript:void(0)"><img
                                                            src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon.png"
                                                            alt="Lifeset Bookmark"> </a>
                                          <?php } ?>
                                             </p>
                                             <h3><?php echo $personalitydata['name']; ?></h3>
                                       <?php if (!empty($personalitydata['image'])) { ?>
                                                <div class="postbanner">
                                                   <img src="<?php echo webURL . 'img/Post/' . $personalitydata['image']; ?>"
                                                      alt="Lifeset Post" class="<?php echo webURL . 'admin/'; ?>img-responsive">
                                                </div>
                                       <?php } ?>
                                       <style>.ansclm .lstitle {
  font-size: 12px;
  display: block;

  min-height: 60px;
}
.ansclm {
  width:20%;
}
</style>
                                             <form action="<?php echo webURL . 'personality_answer'; ?>" method="post">
                                                <div class="walljobpst">
                                                   <div class="lsanslst">
                                                      <div class="row">
                                                         
                                                   <?php $answer = $personalitydata['answer'];
                                                   for ($qi1 = 1; $answer >= $qi1; $qi1++) { ?>
                                                            <div class="ansclm">

                                                         <?php if ($qi1 == 1) {
                                                            echo '<span class="lstitle disagreed">  बिल्कुल गलत<br> Absolutly Incorrect</span>';
                                                         } else
                                                            if ($qi1 == 2) {
                                                               echo '<span class="lstitle nominal"> कुछ हद तक ग़लत <br>Partially Incorrect  </span>';
                                                            } else
                                                            if ($qi1 == 3) {
                                                               echo '<span class="lstitle nominal">  निष्पक्ष <br>Neutral   </span>';
                                                            } else
                                                            if ($qi1 == 4) {
                                                               echo '<span class="lstitle nominal">  कुछ हद तक सही Partially Correct </span>';
                                                                
                                                            } else
                                                               if ($qi1 == 5) {
                                                                  echo '<span class="lstitle agreed">  बिलकुल सही<br>Absolutely Correct</span>';
                                                               } ?>
                                                               <label><input type="radio" id="right_answer" name="right_answer"
                                                                     class="option-input radio right_answer" value="<?php if (isset($qi1)) {
                                                                        echo $qi1;
                                                                     } ?>" disabled placeholder="">
                                                               </label>
                                                            </div>
                                                   <?php } ?>
                                                      </div>
                                                   </div>
                                                </div>
                                             </form>
                                          </div>
                                 <?php } else if ($viewData['post_type'] == 'Survey') {
                                    $surveydata = $this->Member->survey_wall_data($viewData['id']);
                                    $quizanswer = $this->Member->survey_answer($viewData['id']);
                                    //if(!empty($surveydata['cat_id'])){ $catdta=$this->Member->cat_detail($surveydata['cat_id']); $catname=$catdta['name']; }else{ $catname=''; }
                                    ?>
                                             <div class="lswallbolck">
                                                <span class="lstag"><?php echo $viewData['post_type']; ?></span>
                                                <p class="lsdate"><?php echo date('d M Y', strtotime($viewData['updated'])); ?> |
                                                   <img src="<?php echo webURL . 'admin/'; ?>img/icons/share-icon.png"
                                                      alt="Lifeset Share">
                                          <?php $bookmark = $this->Member->get_bookmarks($viewData['id']); ?>
                                          <?php if (empty($bookmark)) { ?>
                                                      <a href="javascript:void(0)"><img
                                                            src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                            alt="Lifeset Bookmark"></a>
                                          <?php } else if (!empty($adminLogType)) { ?>
                                                         <a href="javascript:void(0)"><img
                                                               src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                               alt="Lifeset Bookmark"> </a>
                                          <?php } else { ?>
                                                         <a href="javascript:void(0)"><img
                                                               src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon.png"
                                                               alt="Lifeset Bookmark"> </a>
                                          <?php } ?>
                                                </p>
                                                <h3 class="col-md-12"><?php echo $surveydata['question']; ?></h3>
                                       <?php if (!empty($surveydata['image'])) { ?>
                                                   <div class="postbanner">
                                                      <img src="<?php echo webURL . 'img/Post/' . $surveydata['image']; ?>"
                                                         alt="Lifeset Post" class="<?php echo webURL . 'admin/'; ?>img-responsive">
                                                   </div>
                                       <?php } ?>
                                                <ul class="lsanslst">
                                          <?php if (!empty($surveydata['answer'])) {
                                             $answer = explode(';;', $surveydata['answer']);
                                             $right_answer = explode(';;', $surveydata['right_answer']);
                                             $totalr = array_sum($right_answer);
                                             for ($qi1 = 0; count($answer) > $qi1; $qi1++) { ?>
                                                         <li>
                                                            <label>
                                                               <input type="checkbox" id="right_answer" name="right_answer[]" value="<?php if (isset($qi1)) {
                                                                  echo $qi1;
                                                               } ?>" disabled class="option-input radio"
                                                                  placeholder="">
                                                            </label>
                                                   <?php if (isset($answer[$qi1])) {
                                                      echo $answer[$qi1];
                                                   } ?>
                                                         </li>
                                             <?php }
                                          } ?>
                                                </ul>
                                             </div>
                                 <?php } else if ($viewData['post_type'] == 'Q&A') {
                                    $quizanswer = $this->Member->quiz_answer($viewData['id']);
                                    //if(!empty($viewData['category'])){ $catname=$this->Member->cat_detail($viewData['category']); } else{ $catname=''; }
                                    ?>
                                                <div class="lswallbolck">
                                                   <span class="lstag"><?php echo $viewData['post_type']; ?></span>
                                                   <p class="lsdate"><?php echo date('d M Y', strtotime($viewData['updated'])); ?> |
                                                      <img src="<?php echo webURL . 'admin/'; ?>img/icons/share-icon.png"
                                                         alt="Lifeset Share">
                                          <?php $bookmark = $this->Member->get_bookmarks($viewData['id']); ?>
                                          <?php if (empty($bookmark)) { ?>
                                                         <a href="javascript:void(0)"><img
                                                               src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                               alt="Lifeset Bookmark"></a>
                                          <?php } else if (!empty($adminLogType)) { ?>
                                                            <a href="javascript:void(0)"><img
                                                                  src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                                  alt="Lifeset Bookmark"> </a>
                                          <?php } else { ?>
                                                            <a href="javascript:void(0)"><img
                                                                  src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon.png"
                                                                  alt="Lifeset Bookmark"> </a>
                                          <?php } ?>
                                                   </p>
                                                   <h3 class="col-md-12"><?php echo $viewData['objquestion']; ?></h3>
                                       <?php if (!empty($viewData['image'])) { ?>
                                                      <div class="postbanner">
                                                         <img src="<?php echo webURL . 'img/Post/' . $viewData['image']; ?>"
                                                            alt="Lifeset Post" class="<?php echo webURL . 'admin/'; ?>img-responsive">
                                                      </div>
                                       <?php } ?>
                                                   <ul class="lsanslst">
                                          <?php if (!empty($viewData['answer'])) {
                                             $answer = explode(';;', $viewData['answer']);
                                             $right_answer = explode(';;', $viewData['right_answer']);
                                             $totalr = array_sum($right_answer);
                                             for ($qi1 = 0; count($answer) > $qi1; $qi1++) { ?>
                                                            <li>
                                                               <label>
                                                                  <input type="checkbox" id="right_answer" name="right_answer[]" value="<?php if (isset($qi1)) {
                                                                     echo $qi1;
                                                                  } ?>" disabled class="option-input radio"
                                                                     placeholder="">
                                                               </label>
                                                   <?php if (isset($answer[$qi1])) {
                                                      echo $answer[$qi1];
                                                   } ?>
                                                            </li>
                                             <?php }
                                          } ?>
                                                   </ul>
                                                </div>
                                 <?php } else if ($viewData['post_type'] == 'Review') {
                                    $reviewanswer = $this->Member->review_answer($viewData['id']);
                                    //if(!empty($viewData['category'])){ $catname=$this->Member->cat_detail($viewData['category']); } else{ $catname=''; }
                                    ?>
                                                   <div class="lswallbolck">
                                                      <span class="lstag"><?php echo $viewData['post_type']; ?></span>
                                                      <p class="lsdate"><?php echo date('d M Y', strtotime($viewData['updated'])); ?> |
                                                         <img src="<?php echo webURL . 'admin/'; ?>img/icons/share-icon.png"
                                                            alt="Lifeset Share">
                                          <?php $bookmark = $this->Member->get_bookmarks($viewData['id']); ?>
                                          <?php if (empty($bookmark)) { ?>
                                                            <a href="javascript:void(0)"><img
                                                                  src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                                  alt="Lifeset Bookmark"></a>
                                          <?php } else if (!empty($adminLogType)) { ?>
                                                               <a href="javascript:void(0)"><img
                                                                     src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                                     alt="Lifeset Bookmark"> </a>
                                          <?php } else { ?>
                                                               <a href="javascript:void(0)"><img
                                                                     src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon.png"
                                                                     alt="Lifeset Bookmark"> </a>
                                          <?php } ?>
                                                      </p>
                                                      <h3><?php echo $viewData['question']; ?></h3>
                                       <?php if (!empty($viewData['image'])) { ?>
                                                         <div class="postbanner">
                                                            <img src="<?php echo webURL . 'img/Post/' . $viewData['image']; ?>"
                                                               alt="Lifeset Post" class="<?php echo webURL . 'admin/'; ?>img-responsive">
                                                         </div>
                                       <?php } ?>
                                                      <form action="<?php echo webURL . 'review_answer'; ?>" method="post">
                                                         <div class="walljobpst">
                                                            <ul class="lsanslst">
                                                <?php if (!empty($viewData['options'])) {
                                                   $options = explode(';;', $viewData['options']);
                                                   $rating = explode(';;', $viewData['rating']);
                                                   for ($qi1 = 0; count($options) > $qi1; $qi1++) {
                                                      ?>
                                                                     <li>
                                                                        <label>
                                                                           <fieldset class="rating">
                                                               <?php if (!empty($reviewanswer)) {
                                                                  $resrating = explode(';;', $reviewanswer['rating']);

                                                                  for ($qi2 = $rating[$qi1]; 0 < $qi2; $qi2--) {

                                                                     if ($resrating[$qi1] == $qi2) { ?>
                                                                                       <input type="radio" id="<?php if (isset($viewData)) {
                                                                                          echo $viewData['id'];
                                                                                       } ?>_<?php if (isset($qi1)) {
                                                                                           echo $qi1;
                                                                                        } ?>_<?php if (isset($qi2)) {
                                                                                            echo $qi2;
                                                                                         } ?>" disabled name="rating_<?php if (isset($qi1)) {
                                                                                             echo $qi1;
                                                                                          } ?>_<?php if (isset($qi2)) {
                                                                                              echo $qi2;
                                                                                           } ?>" checked
                                                                                          value="<?php echo $qi2; ?>" />
                                                                                       <label class="full" for="<?php if (isset($viewData)) {
                                                                                          echo $viewData['id'];
                                                                                       } ?>_<?php if (isset($qi1)) {
                                                                                           echo $qi1;
                                                                                        } ?>_<?php if (isset($qi2)) {
                                                                                            echo $qi2;
                                                                                         } ?>"
                                                                                          title="stars"></label>
                                                                     <?php } else { ?>
                                                                                       <input type="radio" id="<?php if (isset($viewData)) {
                                                                                          echo $viewData['id'];
                                                                                       } ?>_<?php if (isset($qi1)) {
                                                                                           echo $qi1;
                                                                                        } ?>_<?php if (isset($qi2)) {
                                                                                            echo $qi2;
                                                                                         } ?>" disabled name="rating_<?php if (isset($qi1)) {
                                                                                             echo $qi1;
                                                                                          } ?>_<?php if (isset($qi2)) {
                                                                                              echo $qi2;
                                                                                           } ?>"
                                                                                          value="<?php echo $qi2; ?>" /><label class="full" for="<?php if (isset($viewData)) {
                                                                                                echo $viewData['id'];
                                                                                             } ?>_<?php if (isset($qi1)) {
                                                                                                 echo $qi1;
                                                                                              } ?>_<?php if (isset($qi2)) {
                                                                                                  echo $qi2;
                                                                                               } ?>"
                                                                                          title="stars"></label>
                                                                     <?php }
                                                                  }
                                                               } else { ?>
                                                                  <?php for ($qi2 = $rating[$qi1]; 0 < $qi2; $qi2--) { ?>
                                                                                    <input type="radio" id="<?php if (isset($viewData)) {
                                                                                       echo $viewData['id'];
                                                                                    } ?>_<?php if (isset($qi1)) {
                                                                                        echo $qi1;
                                                                                     } ?>_<?php if (isset($qi2)) {
                                                                                         echo $qi2;
                                                                                      } ?>" name="rating_<?php if (isset($qi1)) {
                                                                                          echo $qi1;
                                                                                       } ?>"
                                                                                       value="<?php echo $qi2; ?>" /><label class="full" for="<?php if (isset($viewData)) {
                                                                                             echo $viewData['id'];
                                                                                          } ?>_<?php if (isset($qi1)) {
                                                                                              echo $qi1;
                                                                                           } ?>_<?php if (isset($qi2)) {
                                                                                               echo $qi2;
                                                                                            } ?>" title="stars"></label>
                                                                  <?php }
                                                               } ?>
                                                                           </fieldset>
                                                            <?php if (isset($options[$qi1])) {
                                                               echo $options[$qi1];
                                                            } ?>
                                                                        </label>
                                                                     </li>
                                                   <?php }
                                                } ?>
                                                            </ul>
                                             <?php if (empty($reviewanswer)) {
                                                if (empty($adminLogType)) { ?>
                                                                  <input type="hidden" id="poptions" name="poptions"
                                                                     value="<?php echo $viewData['options']; ?>">
                                                                  <input type="hidden" id="pid" name="pid" value="<?php if ($paction != 'copy') {
                                                                     echo $viewData['id'];
                                                                  } ?>">
                                                                  <input type="hidden" id="pgurl" name="pgurl"
                                                                     value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                                                  <div class="text-right">
                                                                     <input type="submit" class="btn btn-primary" value="Submit">
                                                                  </div>
                                                <?php }
                                             } ?>
                                                         </div>
                                                      </form>
                                                   </div>
                                 <?php } else {
                                    //if($viewData['post_type']!='Job' && $viewData['post_type']!='Internship'){ 
                                    //if(!empty($viewData['category'])){ $catname=$this->Member->cat_detail($viewData['category']); } else{ $catname=''; }
                                    ?>
                                                   <div class="lswallbolck jobblock">
                                                      <div class="col-md-3 pull-right">
                                          <?php if ($viewData['post_type'] == 'Job' || $viewData['post_type'] == 'Internship') {
                                             $apply = $this->Member->get_applied($viewData['id']); ?>
                                             <?php if (empty($apply)) { ?>
                                                               <a href="javascript:void(0)" class="btn btn-primary btn-block">Apply </a>
                                             <?php } else if (!empty($adminLogType)) { ?>
                                                                  <a href="javascript:void(0)" class="btn btn-primary btn-block">Apply </a>
                                             <?php } else { ?>
                                                                  <a href="javascript:void(0)" class="btn btn-primary btn-block">Applied </a>
                                             <?php }
                                          } ?>
                                                      </div>
                                                      <span class="lstag"><?php echo $viewData['post_type']; ?></span>
                                                      <p class="lsdate"><?php echo date('d M Y', strtotime($viewData['updated'])); ?> |
                                                         <img src="<?php echo webURL . 'admin/'; ?>img/icons/share-icon.png"
                                                            alt="Lifeset Share">
                                          <?php $bookmark = $this->Member->get_bookmarks($viewData['id']); ?>
                                          <?php if (empty($bookmark)) { ?>
                                                            <a href="javascript:void(0)"><img
                                                                  src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                                  alt="Lifeset Bookmark"></a>
                                          <?php } else if (!empty($adminLogType)) { ?>
                                                               <a href="javascript:void(0)"><img
                                                                     src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon-a.png"
                                                                     alt="Lifeset Bookmark"> </a>
                                          <?php } else { ?>
                                                               <a href="javascript:void(0)"><img
                                                                     src="<?php echo webURL . 'admin/'; ?>img/icons/bookmark-icon.png"
                                                                     alt="Lifeset Bookmark"> </a>
                                          <?php } ?>
                                                      </p>
                                                      <h3><?php echo $viewData['title']; ?></h3>
                                       <?php if (!empty($viewData['image'])) { ?>
                                                         <a href="javascript:void(0)" class="post">
                                                            <div class="postbanner">
                                                               <img src="<?php echo webURL . 'img/Post/' . $viewData['image']; ?>"
                                                                  alt="Lifeset Post" class="<?php echo webURL . 'admin/'; ?>img-responsive">
                                                            </div>
                                                         </a>
                                       <?php } ?>
                                       <?php if ($viewData['post_type'] == 'Job' || $viewData['post_type'] == 'Internship') { ?>
                                                         <div class="walljobpst">
                                                            <ul class="lsdetls">
                                                <?php if (!empty($viewData['company_name'])) { ?>
                                                                  <li><span>Company Name :</span> <?php echo $viewData['company_name']; ?></li>
                                                <?php }
                                                if (!empty($viewData['industry'])) { ?>
                                                                  <li><span>Industry :</span> <?php echo $viewData['industry']; ?></li>
                                                <?php }
                                                if (!empty($viewData['function'])) { ?>
                                                                  <li><span>Function :</span> <?php echo $viewData['function']; ?></li>
                                                <?php }
                                                if (!empty($viewData['role'])) { ?>
                                                                  <li><span>Role :</span> <?php echo $viewData['role']; ?></li>
                                                <?php }
                                                if (!empty($viewData['past_experience'])) { ?>
                                                                  <li><span>Past Experience :</span> <?php echo $viewData['past_experience']; ?>
                                                                  </li>
                                                <?php }
                                                if (!empty($viewData['job_location'])) { ?>
                                                                  <li><span>Job Location :</span> <?php echo $viewData['job_location']; ?></li>
                                                <?php }
                                                if (!empty($viewData['skill'])) { ?>
                                                                  <li><span>Skills :</span> <?php echo $viewData['skill']; ?></li>
                                                <?php }
                                                if (!empty($viewData['job_type'])) { ?>
                                                                  <li><span>Job Type :</span> <?php echo $viewData['job_type']; ?></li>
                                                <?php }
                                                if (!empty($viewData['client_to_manage'])) { ?>
                                                                  <li><span>Clients to Manage :</span> <?php echo $viewData['client_to_manage']; ?>
                                                                  </li>
                                                <?php }
                                                if (!empty($viewData['capacity'])) { ?>
                                                                  <li><span>Capacity :</span> <?php echo $viewData['capacity']; ?></li>
                                                <?php }
                                                if (!empty($viewData['fixed_salary'])) { ?>
                                                                  <li><span>Yearly Salary :</span> <?php echo $viewData['fixed_salary']; ?></li>
                                                <?php }
                                                if (!empty($viewData['variable_sallery'])) { ?>
                                                                  <li><span>Perks & Benefits :</span> <?php echo $viewData['variable_sallery']; ?>
                                                                  </li>
                                                <?php }
                                                if (!empty($viewData['working_days'])) { ?>
                                                                  <li><span>Working Days :</span> <?php echo $viewData['working_days']; ?></li>
                                                <?php }
                                                if (!empty($viewData['work_time'])) { ?>
                                                                  <li><span>Work Time :</span> <?php echo $viewData['work_time']; ?></li>
                                                <?php } ?>
                                                            </ul>
                                             <?php /*if(!empty($viewData['company_id'])){ ?>
                                                 <h5>Company Details: </h5>
                                                 <?php $cmpdtl=$this->Member->companydetail($viewData['company_id']); ?>
                                                 <ul class="lsdetls">
                                                    <?php if(!empty($cmpdtl['name'])){ ?>	
                                                    <li><span>Company Name :</span> <?php echo $cmpdtl['name']; ?></li>
                                                    <?php } if(!empty($cmpdtl['name'])){ ?>	
                                                    <li><span>Phone Number :</span> <?php echo $cmpdtl['mobile']; ?></li>
                                                    <?php } if(!empty($cmpdtl['name'])){ ?>	
                                                    <li><span>Email Id :</span> <?php echo $cmpdtl['email']; ?></li>
                                                    <?php } ?>	
                                                 </ul>
                                          <?php }*/ ?>
                                                            <p class="viewdec">
                                                <?php echo $viewData['description']; ?>
                                                            </p>
                                                         </div>
                                       <?php } else if ($viewData['post_type'] != 'Quick Post') { ?>
                                                            <div class="walljobpst">
                                                               <ul class="lsdetls">
                                                <?php if (!empty($viewData['pincode'])) { ?>
                                                                     <li><span>Pincode :</span> <?php echo $viewData['pincode']; ?></li>
                                                <?php }
                                                if (!empty($viewData['state'])) { ?>
                                                                     <li><span>State :</span> <?php echo $viewData['state']; ?></li>
                                                <?php }
                                                if (!empty($viewData['district'])) { ?>
                                                                     <li><span>District :</span> <?php echo $viewData['district']; ?></li>
                                                <?php }
                                                if (!empty($viewData['hobbies'])) { ?>
                                                                     <li><span>Interest/Hobbies :</span> <?php echo $viewData['hobbies']; ?></li>
                                                <?php } ?>
                                                               </ul>
                                             <?php /* if(!empty($viewData['company_id'])){ ?>
                                            <h5>Company Details: </h5>
                                            <?php $cmpdtl=$this->Member->companydetail($viewData['company_id']); ?>
                                            <ul class="lsdetls">
                                              <?php if(!empty($cmpdtl['name'])){ ?>	
                                              <li><span>Company Name :</span> <?php echo $cmpdtl['name']; ?></li>
                                              <?php } if(!empty($cmpdtl['name'])){ ?>	
                                              <li><span>Phone Number :</span> <?php echo $cmpdtl['mobile']; ?></li>
                                              <?php } if(!empty($cmpdtl['name'])){ ?>	
                                              <li><span>Email Id :</span> <?php echo $cmpdtl['email']; ?></li>
                                              <?php } ?>	
                                            </ul>
                                          <?php }*/ ?>
                                                               <p class="viewdec">
                                                <?php echo $viewData['description']; ?>
                                                               </p>
                                                            </div>
                                       <?php } else { ?>

                                                            <p class="viewdec">
                                             <?php echo $viewData['description']; ?>
                                                            </p>
                                       <?php } ?>
                                                   </div>
                                 <?php } ?>
                                 <div class="col-md-12">

                                    <div class="form-group">
                                       <button type="submit" name="Publish" class=" btn btn-primary">Publish</button>

                                       <?php if ($viewData['status'] == 1) { ?>
                                          <button type="submit" name="Deactive"
                                             class=" btn btn-primary deactive pull-right">Deactive</button>
                                       <?php } ?>
                                    </div>
                                 </div>
                              <?php } ?>
                           </div>
                        </div>
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


   <!-- END Page Wrapper -->
   <!-- Load and execute javascript code used only in this page -->
   <?php echo $this->Html->script(webURL . 'admin/js/pages/formsValidation.js'); ?>
   <script>$(function () { FormsValidation.init(); });</script>
   <script>
      jQuery(document).ready(function () {
         $('.add-more').on('click', function () {
            $('.inc').append('<div class="controls"><div class="row form-group"><div class="col-md-10"><label class="col-md-12" for="val-username">Option <span class="text-danger">*</span></label><div class="col-md-12"><input type="text" id="val-name" required name="options[]" value="" class="form-control" placeholder=""></div></div><a href="#" class="btn btn-danger remove_this btn-xs" > - remove</a></div></div>');
         });
         jQuery(document).on('click', '.remove_this', function () {
            jQuery(this).parent().remove();
            return false;

         });
         $("input[type=submit]").click(function (e) {
            e.preventDefault();
            $(this).next("[name=textbox]")
               .val(
                  $.map($(".inc :text"), function (el) {
                     return el.value
                  }).join(",\n")
               )
         })
      });
   </script>
   <script>
      jQuery(document).ready(function () {
         $('.addmore').on('click', function () {
            $('.inc1').append('<div class="controls1"><div class="row form-group"><div class="col-md-7"><label class="col-md-12" for="val-username">Answer <span class="text-danger"></span></label><div class="col-md-12"> 	<input type="text" id="answer" required name="answer[]" value="" class="form-control answer" placeholder=""></div></div><div class="col-md-4"><label class="col-md-12" for="val-username"> &nbsp;  <span class="text-danger"></span></label><div class="col-md-12"> <select id="right_answer" name="right_answer[]" class="form-control"><option value="0" >Wrong</option><option value="1">Right</option></select>  </div></div><a href="#" class="btn btn-danger remove_this btn-xs" > - remove</a></div></div>');
         });
         jQuery(document).on('click', '.removethis', function () {
            jQuery(this).parent().remove();
            return false;

         });
         $("input[type=submit]").click(function (e) {
            e.preventDefault();
            $(this).next("[name=textbox]")
               .val(
                  $.map($(".inc :text"), function (el) {
                     return el.value
                  }).join(",\n")
               )
         })
      });
   </script>
   <script>
      jQuery(document).ready(function () {
         $('.survey_addmore').on('click', function () {
            $('.survey_inc').append('<div class="survey_controls"><div class="row form-group"><div class="col-md-7"><label class="col-md-12" for="val-username">Answer <span class="text-danger"></span></label><div class="col-md-12"> 	<input type="text" id="survey_answer" required name="survey_answer[]" value="" class="form-control survey_answer" placeholder=""></div></div><div class="col-md-4"><label class="col-md-12" for="val-username"> &nbsp;  <span class="text-danger"></span></label><div class="col-md-12"> <select id="survey_right_answer" name="survey_right_answer[]" class="form-control"><option value="0" >Wrong</option><option value="1">Right</option></select>  </div></div><a href="#" class="btn btn-danger survey_removethis btn-xs" > - remove</a></div></div>');
         });
         jQuery(document).on('click', '.survey_removethis', function () {
            jQuery(this).parent().remove();
            return false;

         });
         $("input[type=submit]").click(function (e) {
            e.preventDefault();
            $(this).next("[name=textbox]")
               .val(
                  $.map($(".inc :text"), function (el) {
                     return el.value
                  }).join(",\n")
               )
         })
      });
   </script>
   <script>
      <?php if (!empty($ckmembid)) { ?>
         $('.job_box').show();
         $('.event_box').hide();
         $('.job_textbox').show();
         $('.event_textbox').hide();
         $('.profilephoto').hide();
         $('.review_box').hide();
         $('.quest_box').hide();
         $('.review_textbox').hide();
         $('.q_a_textbox').hide();
      <?php } ?>
      function change_type() {
         var posttype = $('.post_type').val();
         if (posttype == 'GK') {
            $('.profilephoto').show();
            $('.gk_box').show();
            $('.survey_box').hide();
            $('.personality_box').hide();
            $('.exam_box').hide();
            $('.job_box').hide();
            $('.event_box').hide();
            $('.job_textbox').hide();
            $('.event_textbox').hide();
            $('.review_box').hide();
            $('.quest_box').hide();
            $('.review_textbox').hide();
            $('.q_a_textbox').hide();
         } else if (posttype == 'Survey') {
            $('.profilephoto').show();
            $('.gk_box').hide();
            $('.survey_box').show();
            $('.personality_box').hide();
            $('.exam_box').hide();
            $('.personality_box').hide();
            $('.exam_box').hide();
            $('.job_box').hide();
            $('.event_box').hide();
            $('.job_textbox').hide();
            $('.event_textbox').hide();
            $('.review_box').hide();
            $('.quest_box').hide();
            $('.review_textbox').hide();
            $('.q_a_textbox').hide();
         } else if (posttype == 'Personality') {
            $('.profilephoto').show();
            $('.gk_box').hide();
            $('.survey_box').hide();
            $('.personality_box').show();
            $('.exam_box').hide();
            $('.exam_box').hide();
            $('.job_box').hide();
            $('.event_box').hide();
            $('.job_textbox').hide();
            $('.event_textbox').hide();
            $('.review_box').hide();
            $('.quest_box').hide();
            $('.review_textbox').hide();
            $('.q_a_textbox').hide();
         } else if (posttype == 'Exam') {
            $('.profilephoto').show();
            $('.gk_box').hide();
            $('.survey_box').hide();
            $('.personality_box').hide();
            $('.exam_box').show();
            $('.job_box').hide();
            $('.event_box').hide();
            $('.job_textbox').hide();
            $('.event_textbox').hide();
            $('.review_box').hide();
            $('.quest_box').hide();
            $('.review_textbox').hide();
            $('.q_a_textbox').hide();
         } else if (posttype == 'Review') {
            $('.job_box').hide();
            $('.gk_box').hide();
            $('.survey_box').hide();
            $('.personality_box').hide();
            $('.exam_box').hide();
            $('.event_box').hide();
            $('.job_textbox').hide();
            $('.event_textbox').hide();
            $('.profilephoto').show();
            $('.review_box').show();
            $('.quest_box').hide();
            $('.review_textbox').show();
            $('.q_a_textbox').hide();
         } else if (posttype == 'Q&A') {
            $('.job_box').hide();
            $('.gk_box').hide();
            $('.survey_box').hide();
            $('.personality_box').hide();
            $('.exam_box').hide();
            $('.event_box').hide();
            $('.job_textbox').hide();
            $('.event_textbox').hide();
            $('.review_box').hide();
            $('.quest_box').show();
            $('.review_textbox').hide();
            $('.q_a_textbox').show();
         } else if (posttype == 'Job' || posttype == 'Internship') {
            $('.job_box').show();
            $('.gk_box').hide();
            $('.survey_box').hide();
            $('.personality_box').hide();
            $('.exam_box').hide();
            $('.event_box').hide();
            $('.job_textbox').show();
            $('.event_textbox').hide();
            $('.profilephoto').hide();
            $('.review_box').hide();
            $('.quest_box').hide();
            $('.review_textbox').hide();
            $('.q_a_textbox').hide();
         } else {
            $('.job_box').hide();
            $('.gk_box').hide();
            $('.survey_box').hide();
            $('.personality_box').hide();
            $('.exam_box').hide();
            $('.event_box').show();
            $('.job_textbox').hide();
            $('.review_box').hide();
            $('.quest_box').hide();
            $('.event_textbox').show();
            $('.profilephoto').show();
            $('.review_textbox').hide();
            $('.q_a_textbox').hide();
         }
      }
   </script>
   <script>
      function check_pincode() {
         var pincode = $('#pincode').val();

         $.ajax
            ({
               url: '<?php echo webURL; ?>get_pincode',
               data: "pincode=" + pincode,
               type: 'POST',
               success: function (response) {
                  var getresponse = $.parseJSON(response);

                  $('#state').val(getresponse.state);
                  $('#district').val(getresponse.district);
               }
            });
      }
   </script>
   <script>
      function check_college() {
         var college = $('.college_id').val();

         $.ajax
            ({
               url: '<?php echo webURL; ?>getcollegestream1',
               data: "college=" + college,
               type: 'POST',
               success: function (response) {
                  var getresponse = $.parseJSON(response);

                  $('.select_stream').html(getresponse.cat);
                  $('.select_course').html(getresponse.course);
                  $('.select_year').html(getresponse.year);

               }
            });
      }
   </script>
   <script>
      jQuery(document).ready(function () {
         $('#exam_cat_id').on('change', function () {
            var cat = $('#exam_cat_id').val();
            if (cat != 'Other') {
               $('.other_category').hide();
            } else {
               $('.other_category').show();
            }
         });
      });
   </script>
   <script>
      jQuery(document).ready(function () {
         $('#exam_level').on('change', function () {
            var cat = $('#exam_level').val();
            if (cat != 'Other') {
               $('.other_exam').hide();
            } else {
               $('.other_exam').show();
            }
         });
      });

   </script>
   <script>
      function check_course() {
         var college = $('.college_id').val();
         var stream = $('.select_stream').val();

         $.ajax
            ({
               url: 'getcollegecourse1',
               data: "college=" + college + "&stream=" + stream,
               type: 'POST',
               success: function (response) {
                  var getresponse = $.parseJSON(response);
                  $('.select_course').html(getresponse.stream);
                  $('.select_year').html(getresponse.year);

               }
            });
      }
   </script>
   <script>
      function check_year() {
         var course = $('.select_course').val();
         $.ajax
            ({
               url: 'getcollegecourseyear',
               data: "course=" + course,
               type: 'POST',
               success: function (response) {
                  var getresponse = $.parseJSON(response);

                  $('.select_year').html(getresponse.year);
               }
            });
      }
   </script>