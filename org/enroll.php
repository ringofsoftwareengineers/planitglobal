<?php

// not needed now == trainee enrolls himslef/herself from moodle no
include_once '../config.php';
include_once '../classes/Organization.php';
include_once '../classes/Course.php';
include_once '../classes/Trainee.php';
$organization = new Trainee();
$organization = new Organization();
$warning_message = "";
if (!$organization->is_org_user_loggedin()) {
    header("location: login.php");
}
$org_id = $_SESSION["org_id"];
$org_type = $_SESSION["org_type"];


if (isset($_POST["enroll_trainee"])) {
    $trainee_id = $_POST["select_trainee"];
    $course_id = $_POST["select_course"];
    if (!$organization->is_trainee_already_enrolled($con, $course_id, $trainee_id, $org_id)) {
        $organization->enroll_trainee($con, $course_id, $trainee_id, $org_id);
        //header("location: enroll.php");
    } else {
        $warning_message = "Trainee already enrolled in this course";
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Planit Global</title>
        <?php include './header-links.php'; ?>
    </head>
    <body>
        <div class="container-fluid">
            <?php
            include './org-account-header.php';
            ?>

            <form method="post">
                <div class="row">
                    <div class="col-md-8 center-aligin">
                        <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Trainee Course Enrollment</h2>
                        <h4 id="warning" style="color: red; text-align: center;">
                            <?php
                            echo $warning_message;
                            ?>
                        </h4>
                        <div class="row">
                            <div class="col-md-8 center-aligin">
                                <div class="form-group">

                                    <label for="select_course">Select Course:</label>
                                    <select id="select_course" name="select_course" class="form-control">
                                        <?php
                                        $course = new Course();
                                        $courses = $course->get_all_active_courses($con);
                                        foreach ($courses as $course) {
                                            ?>
                                            <option value="<?php echo $course->id; ?>"><?php echo $course->course_title . " (" . $course->course_code . ")"; ?></option>
                                            <?php
                                        }
                                        ?>  
                                    </select>
                                </div>

                                <div class="form-group">

                                    <label for="select_trainee">Select Trainee:</label>
                                    <select id="select_trainee" name="select_trainee" class="form-control">
                                        <?php
                                        $organization = new Trainee();
                                        $trainees = $organization->get_all_trainees_by_org_id($con, $org_id);
                                        foreach ($trainees as $organization) {
                                            ?>
                                            <option value="<?php echo $organization->id; ?>"><?php echo $organization->user_name; ?></option>
                                            <?php
                                        }
                                        ?>  
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-default right-align" value="Enroll" name="enroll_trainee">
                                </div>
                            </div> 
                        </div
                    </div>
                </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-8 center-aligin" style="margin-top: 30px; margin-bottom: 30px;">
            <?php
            $trainees = $organization->get_all_enrolled_trainees_by_org_id($con, $org_id);
            if (count($trainees) > 0) {
                ?>
                <table id="enrollments_table" style="margin-top: 30px; margin-bottom: 30px;">               
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Name</th>
                            <th>Login Name</th>
                            <th>Course Code</th>
                            <th>Course Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($trainees as $org_trainee) {
                            ?>
                            <tr>
                                <td><?php echo $counter; ?></td>
                                <td><?php echo $org_trainee->user_name; ?></td>
                                <td><?php echo $org_trainee->user_login; ?></td>
                                <td><?php echo $org_trainee->course_code; ?></td>
                                <td><?php echo $org_trainee->course_title; ?></td>
                            </tr>
                            <?php
                            $counter++;
                        }
                        ?>
                    </tbody>

                </table>
                <script>
                    $(document).ready(function () {
                        $('#enrollments_table').DataTable();
                    });
                </script>

                <?php
            }
            ?>  
        </div>
    </div> 

</div>
</body>
</html>

