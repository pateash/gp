<script type="text/javascript">
    $(document).ready(function() {
        $('#assignment_table').DataTable( {
            "scrollY":        "400px",
            "scrollCollapse": true,
            "paging":         false,
            "dom": '<"toolbar_assignment toolbar">frtip',
        });
        $("div.toolbar_assignment").html('<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignment_modal">Add new Assignment</button>');
        $('#syllabus_table').DataTable( {
            "scrollY":        "400px",
            "scrollCollapse": true,
            "paging":         false,
            "dom": '<"toolbar_syllabus toolbar">frtip',
        });
        $("div.toolbar_syllabus").html('<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#syllabus_modal">Add new syllabus</button>');

        $('#study_table').DataTable( {
            "scrollY":        "400px",
            "scrollCollapse": true,
            "paging":         false,
            "dom": '<"toolbar_study toolbar">frtip',
        });

        // $("div.toolbar_study").html('<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#study_modal">Add new syllabus</button>');
        $("div.toolbar_study").html(`<?php echo anchor_popup($upload_url, 'Upload new material', array('class'=>"btn btn-danger"));?>`);


    });
</script>


<style media="screen">
    #file{
        padding: 10px;
    }
</style>
<main class="site-main">
    <div id="wrapper">
        <?php //$this->view('site/sidebar'); ?>
        <!-- Page Content -->
        <div id="page-content-wrapper">

            <?php
            //     var_dump(get_defined_vars());
            if($logged_in){
                $course_id = $course_data['course_id'];
                $user_id = $this->session->userdata("USER_ID");
                $is_enrolled = $this->coursemodel->isEnrolled($user_id, $course_id);
            }?>
<div class="col-md-8">
            <div class="col-md-12 profile-info" style="margin-top: 2em">
                <div class="sbold uppercase"><span style='font-size:3em'><?php echo $course_data['course_title'];?></span>

                    <?php
                    if($logged_in) {
                        if ($is_enrolled) {
                            echo "&nbsp&nbsp";

                            echo "<span class=\"label label-success \" style='font-size:1em'>enrolled</span>";
                            echo "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";

                            echo "<a class='btn btn-danger ' href='/courses/removeenrollment/$course_id' style='float:right'>Remove Enrollment</a>";
                        } else {
                            echo "&nbsp&nbsp";

                            echo "<span class=\"label label-warning\" style='font-size:1em'>not enrolled</span>";

                            echo "<a class='btn btn-success' href='/courses/addenrollment/$course_id' style='float:right' >Add Enrollment</a>";
                        }
                    }
                    else{
                        echo "<a class='btn btn-info ' href='/user/login' style='float:right'>Login to Enroll</a>";

                    }
                    ?>
                </div>




                <p>
                    <?php echo $course_data['course_description'];?>
                </p>
                <!-- <ul class="list-inline">
                    <li>
                        <i class="fa fa-map-marker"></i> India </li>
                    <li>
                        <i class="fa fa-calendar"></i> 22 Aug 1995 </li>
                    <li>
                        <i class="fa fa-briefcase"></i> Web Development </li>
                </ul> -->
                <hr>
            </div>

            <div class="profile-content col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title tabbable-line">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">Assignments</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_2" data-toggle="tab">Syllabus</a>
                                    </li>
                                    <li>
                                        <a href="#tab_1_3" data-toggle="tab">Study Material</a>
                                    </li>
                                    <!-- <li>
                                        <a href="#tab_1_4" data-toggle="tab">P</a>
                                    </li> -->
                                </ul>
                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <!-- Assignment TAB -->
                                    <div class="tab-pane active" id="tab_1_1">

                                        <div class="table-scrollable">
                                            <?php if(!empty($assignments)){ ?>
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="assignment_table" data-height="400px" role="grid" aria-describedby="sample_1_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 10px;">S.No</th>
                                                        <th class="sorting_asc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="ascending" aria-label=" Username : activate to sort column descending"> Title </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Email : activate to sort column ascending"> Description </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Status : activate to sort column ascending"> Status </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Joined : activate to sort column ascending"> Creation Date </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending"> Actions </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($assignments as $key => $value) {
                                                        ?>
                                                        <tr class="gradeX odd" role="row">
                                                            <td>
                                                                1.
                                                            </td>
                                                            <td class="sorting_1"> <?php echo $value['material_title']; ?> </td>
                                                            <td>
                                                                <?php echo $value['material_description']; ?>
                                                            </td>
                                                            <td>
                                                                <span class="label label-sm label-info"><?php echo $this->config->item('material_status')[$value['material_status']]; ?> </span>
                                                            </td>
                                                            <td class="center">  <?php echo date('d-M-Y',strtotime($value['material_created_at']));?> </td>
                                                            <td>
                                                                <!-- <div class="btn-group">
                                                                     <button class="btn btn-xs btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                                                         <i class="fa fa-angle-down"></i>
                                                                     </button>
                                                                     <ul class="dropdown-menu" role="menu">
                                                                         <li>
                                                                             <a href="javascript:;">
                                                                                 <i class="icon-docs"></i> View Detail </a>
                                                                         </li>
                                                                         <li>
                                                                             <a href="javascript:;">
                                                                                 <i class="icon-user"></i> Submit Now </a>
                                                                         </li>
                                                                         <li class="divider"> </li>
                                                                     </ul>
                                                                 </div> -->
                                                                <div class="action_btns">
                                                                    <a href="#" class="btn btn-outline btn-primary btn-sm"><i class="fa fa-link"></i> View </a>
                                                                    <a href="#" id=""  class="btn btn-outline btn-danger btn-sm"><i class="fa fa-edit"></i> Submit Now </a>
                                                                    <a href="#" id=""  class="btn btn-outline btn-success btn-sm"><i class="fa fa-download"></i> Download </a>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <?php
                                                    }
                                                    ?>

                                                    </tbody>
                                                </table>
                                            <?php  } else{
                                                echo 'Assignments not available.';
                                            }?>
                                        </div>
                                    </div>
                                    <!-- END Assignment TAB -->
                                    <!-- Syllabus TAB -->
                                    <div class="tab-pane" id="tab_1_2">
                                        <div class="table-scrollable">
                                            <?php  if(!empty($syllabus)){?>
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="syllabus_table" data-height="400px" role="grid" aria-describedby="sample_1_info">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 10px;">
                                                            S.No.
                                                        </th>
                                                        <th class="sorting_asc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="activate to sort column descending"> Title </th>
                                                        <th class="sorting_asc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="ascending" aria-label=" activate to sort column descending"> Decription </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Created : activate to sort column ascending"> Creation Date </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending"> Actions </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($syllabus as $key => $value) {
                                                        ?>
                                                        <tr class="gradeX odd" role="row">
                                                            <td>
                                                                1.
                                                            </td>
                                                            <td class="sorting_1"> <?php echo $value['material_title']; ?> </td>
                                                            <td>
                                                                <?php echo $value['material_description']; ?>
                                                            </td>

                                                            <td class="center">  <?php echo date('d-M-Y',strtotime($value['material_created_at']));?> </td>
                                                            <td>
                                                                <!-- <div class="btn-group">
                                                                     <button class="btn btn-xs btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                                                         <i class="fa fa-angle-down"></i>
                                                                     </button>
                                                                     <ul class="dropdown-menu" role="menu">
                                                                         <li>
                                                                             <a href="javascript:;">
                                                                                 <i class="icon-docs"></i> View Detail </a>
                                                                         </li>
                                                                         <li>
                                                                             <a href="javascript:;">
                                                                                 <i class="icon-user"></i> Submit Now </a>
                                                                         </li>
                                                                         <li class="divider"> </li>
                                                                     </ul>
                                                                 </div> -->
                                                                <div class="action_btns">
                                                                    <a href="#" class="btn btn-outline btn-primary btn-sm"><i class="fa fa-link"></i> View </a>
                                                                    <a href="#" id=""  class="btn btn-outline btn-success btn-sm"><i class="fa fa-download"></i> Download </a>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <?php
                                                    }
                                                    ?>

                                                    </tbody>




                                                </table>
                                            <?php  } else{
                                                echo 'Syllabus not available.';;
                                            }?>
                                        </div>
                                    </div>
                                    <!-- END Syllabus  TAB -->
                                    <!-- Study Material TAB -->
                                    <div class="tab-pane" id="tab_1_3">
                                        <div class="table-scrollable">
                                            <?php  if(!empty($study_material)){?>
                                                <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer" id="study_table" data-height="400px" role="grid" aria-describedby="study Material">
                                                    <thead>
                                                    <tr role="row">
                                                        <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width:10px;">
                                                            S.No.
                                                        </th>
                                                        <th class="sorting_asc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="ascending" aria-label=" activate to sort column descending"> Title </th>
                                                        <th class="sorting_asc" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-sort="ascending" aria-label=" activate to sort column descending"> Description </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Joined : activate to sort column ascending"> Creation Date </th>
                                                        <th class="sorting" tabindex="0" aria-controls="sample_1" rowspan="1" colspan="1" aria-label=" Actions : activate to sort column ascending"> Actions </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    foreach ($study_material as $key => $value) {
                                                        ?>
                                                        <tr class="gradeX odd" role="row">
                                                            <td>
                                                                <?php echo $key+1; ?>.
                                                            </td>
                                                            <td class="sorting_1"> <?php echo $value['material_title']; ?> </td>
                                                            <td>
                                                                <?php echo $value['material_description']; ?>
                                                            </td>

                                                            <td class="center"><?php echo date('d-M-Y',strtotime($value['material_created_at']));?> </td>
                                                            <td>
                                                                <!-- <div class="btn-group">
                                                                     <button class="btn btn-xs btn-danger dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
                                                                         <i class="fa fa-angle-down"></i>
                                                                     </button>
                                                                     <ul class="dropdown-menu" role="menu">
                                                                         <li>
                                                                             <a href="javascript:;">
                                                                                 <i class="icon-docs"></i> View Detail </a>
                                                                         </li>
                                                                         <li>
                                                                             <a href="javascript:;">
                                                                                 <i class="icon-user"></i> Submit Now </a>
                                                                         </li>
                                                                         <li class="divider"> </li>
                                                                     </ul>
                                                                 </div> -->
                                                                <div class="action_btns">
                                                                    <a href="/reader_html/<?php echo $value['material_id'].'/'.$value['material_alias'] ?>" class="btn btn-outline btn-primary btn-sm"><i class="fa fa-link"></i> View </a>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <?php
                                                    }
                                                    ?>

                                                    </tbody>





                                                </table>
                                            <?php  } else{
                                                echo 'Study material not available.';
                                            }?>
                                        </div>
                                    </div>

                                    <!-- END Study Material TAB -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
         <div class="col-md-4" > 
       <div class="row">
                            <div class="col-md-12 profile-info">
                                <h2 >Suggested Course</h2>

                                <ul><li>
                                        <p>course 1</p>
                                    </li>
                                    <hr>
                                    <li>
                                        <p>course 2</p>
                                    </li>
                                    <hr>
                                    <li>
                                        <p>course 3</p>
                                    </li>

                                    <hr>
<li>
                                        <p>course 1</p>
                                    </li>
                                    <hr>
                                    <li>
                                        <p>course 2</p>
                                    </li>
                                    <hr>
                                    <li>
                                        <p>course 3</p>
                                    </li>
                                </ul>

                            </div>
                            <!--end col-md-8-->

                        </div>
    </div>
    </div>
</div>


<div id="assignment_modal" class="modal fade" role="dialog" aria-labelledby="assignmentModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">New Assignment</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="assignment_title" class="form-control-label">Assignment Title:</label>
                        <input type="text" name="assignment_title" class="form-control" id="assignment_title">
                    </div>
                    <div class="form-group">
                        <label for="assignment_last_date" class="form-control-label">Last Date Of Submission:</label>
                        <input type="datetime-local" name="assignment_last_date" class="form-control" id="assignment_last_date">
                    </div>

                    <div class="form-group">
                        <label for="assigment_description_text" class="form-control-label">Description:</label>
                        <textarea class="form-control" name="assigment_desc" id="assigment_description_text"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file" class="form-control-label">File Upload: (Max-size:20MB)</label>
                        <input type="file" name="assignment_file" id="file">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>


</main>


<div id="syllabus_modal" class="modal fade" role="dialog" aria-labelledby="syllabusModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">New Syllabus</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="syllabus_title:" class="form-control-label">Syllabus Title:</label>
                        <input type="text" name="syllabus_title" placeholder="ie. Syllabus for the mid term exams " class="form-control" id="syllabus_title">
                    </div>

                    <div class="form-group">
                        <label for="syllabus_desc_text" class="form-control-label">Description:</label>
                        <textarea class="form-control" name="syllabus_desc" id="syllabus_desc_text"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file" class="form-control-label">File Upload: (Max-size:20MB)</label>
                        <input type="file" name="syllabus_file" id="file">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>





<div id="study_modal" class="modal fade" role="dialog" aria-labelledby="studyModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add new Material</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="study_materia_title:" class="form-control-label">Title:</label>
                        <input type="text" name="study_materia_title" placeholder="ie. Syllabus for the mid term exams " class="form-control" id="syllabus_title">
                    </div>

                    <div class="form-group">
                        <label for="study_materia_desc_text" class="form-control-label">Description:</label>
                        <textarea class="form-control" name="study_materia_desc" id="study_materia_desc_text"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="file" class="form-control-label">File Upload: (Max-size:20MB)</label>
                        <input type="file" name="study_materia_file" id="file">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

</div>