<?php
include("header.php");

if (!isset($_SESSION['schools_id'])) {
    header("Location: login_principal.php");
    exit();
}
 $today = date("Y-m-d H:i:s");   
$error=NULL;
    $error="New slot created ...";
    
    
    
//Create Slot :: schools 
//var_dump($_POST);
    
if(isset($_POST['slot_submit'])){
    $data=mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE SchoolId='".$_SESSION['schools_id']."' "));  
 $s_id=$_SESSION['schools_id'];
 $th_id=$_POST['teacher_id'];
 // ///////////////// Slot Date
    $var =$_POST['slot_date_time']; # '20/04/2012'
    $date = str_replace('/', '-', $var);
$dt= $start=date('Y-m-d H:i:s', strtotime($date));  // 2012-04-20
  //  time selected =>11:15 
    $timStr=$_POST['time'];
   $a=explode(":",$_POST['time']);
   $hh=intval($a[0]); $ii=intval($a[1]);
 if($hh>0||$ii>0)
  $dt= date('Y-m-d H:i',strtotime('+'.$hh.'hour +'.$ii.' minutes',strtotime($start)));
 
 //$dt= date('Y-m-d H:i',strtotime('+2 hour +20 minutes',strtotime($start)));
  // timHours
  
   // /////////// ses_start_time ses_end_time start_date end_date
    // expired_datetime
 
 
   // int_schools_x_slots_master #  	slot_date_time
      //
    $qqq=" INSERT INTO int_schools_x_slots_master SET slot_date_time='$dt',"
            . "school_id='$s_id',ses_start_time='$dt',"
            . "created_date='$today',teacher_id='$th_id'";  
      $insert=mysql_query($qqq)or die(mysql_error());
     // DecreaseAvailSlot # $  avaiable_slots
      $currAvlSLot=($data['avaiable_slots']>0)?intval($data['avaiable_slots'])-1:0;
      //$currAvlSLot=intval($data['avaiable_slots'])-1;
        $q=" UPDATE schools SET avaiable_slots='".$currAvlSLot."' WHERE SchoolId='".$_SESSION['schools_id']."' ";
    $a=mysql_query($q); //slotUpdated
   // DecreaseAvailSlot
   
   // echo 
      $msg=  $error="New Slot Created.! ";
     
     
     //$error = 'Slot added ... ';  
     
 } 


///////////////////////
 $email = $_SESSION['temp_email'];
    $firstname = $_SESSION['temp_firstname'];
    $lastname = $_SESSION['temp_lastname'];
    $dist_mail_name =  $_SESSION['temp_dist_name'];
    $master_school_name = $_SESSION['temp_master_school'];
    $school_mail_name = $_SESSION['temp_school_name'];
    $phone_number = $_SESSION['temp_phone'];
    $smart_preb_name = $_SESSION['temp_smart_preb'];
    $data_dash_name = $_SESSION['temp_data_dash'];
    $address = $_SESSION['temp_address'];
    $city = $_SESSION['temp_city_name'];
    $zipcode = $_SESSION['temp_zipcode'];
    $billing_state = $_SESSION['temp_billing_state'];
    if($_SESSION['temp_email']){
?>
       <script type="text/javascript">
            var _dcq = _dcq || [];
            var _dcs = _dcs || {};
            _dcs.account = '7926835';

            (function() {
              var dc = document.createElement('script');
              dc.type = 'text/javascript'; dc.async = true;
              dc.src = '//tag.getdrip.com/7926835.js';
              var s = document.getElementsByTagName('script')[0];
              s.parentNode.insertBefore(dc, s);
            })();
          _dcq.push(["identify", {
            email: "<?php print $email; ?>",
            first_name: "<?php print $firstname; ?>",
            last_name: "<?php print $lastname; ?>",
            your_school: "<?php print $school; ?>",
            billing_address: "<?php print $address; ?>",
            phone_number: "<?php print $phone; ?>",
            billing_city: "<?php print $city; ?>",
            data_dash: "<?php print $data_dash_name; ?>",
            billing_zipcode: "<?php print $zipcode; ?>",
            billing_state: "<?php print $billing_state; ?>",
            tags: ["Customer Admin"]
          }]);

          </script>
       
       
     <?php
    }
    
        if( isset($_SESSION['temp_email']) )unset($_SESSION['temp_email']);
        if( isset($_SESSION['temp_firstname']) )unset($_SESSION['temp_firstname']);
        if( isset($_SESSION['temp_lastname']) )unset($_SESSION['temp_lastname']);
        if( isset($_SESSION['temp_dist_name']) )unset($_SESSION['temp_dist_name']);
        if( isset($_SESSION['temp_master_school']) )unset($_SESSION['temp_master_school']);
        if( isset($_SESSION['temp_school_name']) )unset($_SESSION['temp_school_name']);
        if( isset($_SESSION['temp_phone']) )unset($_SESSION['temp_phone']);
        if( isset($_SESSION['temp_smart_preb']) )unset($_SESSION['temp_smart_preb']);
        if( isset($_SESSION['temp_data_dash']) )unset($_SESSION['temp_data_dash']);
        if( isset($_SESSION['temp_address']) )unset($_SESSION['temp_address']);
        if( isset($_SESSION['temp_city_name']) )unset($_SESSION['temp_city_name']);
        if( isset($_SESSION['temp_zipcode']) )unset($_SESSION['temp_zipcode']);
        if( isset($_SESSION['temp_billing_state']) )unset($_SESSION['temp_billing_state']);

if($_GET['view'] == 'teacher' && $_GET['id'] > 0) {
     $login_teacher = "SELECT * FROM users WHERE id = " . $_GET['id'];
    $teacher_data = mysql_fetch_assoc(mysql_query($login_teacher));

    $_SESSION['login_id'] = $teacher_data['id'];
    $_SESSION['login_user'] = $teacher_data['user_name'];
    $_SESSION['login_mail'] = $teacher_data['email'];
    $_SESSION['login_role'] = 1;
    $_SESSION['login_status'] = 1;
     echo "<script type='text/javascript'>window.location.href = 'folder.php';</script>";
	exit();
}

/* Process logout */
if (isset($_POST['logout'])) {
    unset($_SESSION['schools_id']);
    header("Location: login.php");
    exit();
}
$error = '';
if ($_POST['upload_logo']) {
    // print_r($_FILES); die;
    $cwd = getcwd();
    $uploads_dir = $cwd . '/uploads/schoollogo';

    $school_logo_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `schools` WHERE `SchoolId` = {$_SESSION['schools_id']}"));
    if (trim($school_logo_res['schoollogo']) != '') {
        unlink($uploads_dir . '/' . $school_logo_res['schoollogo']);
    }
    $tmp_name = $_FILES["schoollogo"]["tmp_name"];
    // basename() may prevent filesystem traversal attacks;
    // further validation/sanitation of the filename may be appropriate
    $name = $_SESSION['schools_id'] . '_' . basename($_FILES["schoollogo"]["name"]);
    //echo "$uploads_dir/$name"; die;

    move_uploaded_file($tmp_name, "$uploads_dir/$name");
    mysql_query("UPDATE schools SET schoollogo = \"" . $name . "\" WHERE `SchoolId` = {$_SESSION['schools_id']}  ");
    $error = 'Logo has been updated successfully.';
}
$school = mysql_fetch_assoc(mysql_query("SELECT s.*, d.district_name FROM `schools` s LEFT JOIN loc_district d ON s.district_id = d.id WHERE `SchoolId` = {$_SESSION['schools_id']}"));
$folders = mysql_query("
	SELECT *
	FROM `terms`
	WHERE `id`
	IN (
		SELECT `termId`
		FROM `purchasemeta`
		WHERE `purchaseId` = (
			SELECT `id`
			FROM `purchases`
			WHERE `schoolID` = {$_SESSION['schools_id']}
		)
	)
	ORDER BY `name` ASC
");

function sendEmailNoticeShared($email, $teacher) {
    require 'inc/PHPMailer-master/PHPMailerAutoload.php';

    $sqluser = "SELECT * FROM users WHERE id = " . $teacher;
    $sqlQu = mysql_query($sqluser);

    $toData = mysql_fetch_assoc($sqlQu);
    $to = $toData['email'];

    $message = "Dear {$toData['first_name']},
	<br /><br />
	Congratulations, you now have access to the Intervene Question Database.<br />
	Login https://intervene.io/questions/login.php to get started! Let us know if you need any help.
	<br /><br />
	Best regards,<br />
	<strong>Intervene Team</strong><br/>
	www.intervene.io<br />
	<br /><br />
	<img alt='Less Test Prep' src='https://intervene.io/questions/images/logo.png'>";

    // Create a new PHPMailer instance
    $mail = new PHPMailer;
    // Set who the message is to be sent from
    $mail->setFrom('pathways2greatness@gmail.com', 'Intervene Support');
    // Set an alternative reply-to address
    $mail->addReplyTo('pathways2greatness@gmail.com', 'Intervene Support');
    // Set who the message is to be sent to
    $mail->addAddress($to, '');
    // Set the subject line
    $mail->Subject = 'Access Granted to Intervene Question Database';
    // Replace the plain text body with one created manually
    $mail->Body = $message;
    $mail->AltBody = $message;
    // send the message, check for errors
    if (!$mail->send()) {
        return false;
    } else {
        return true;
    }
}

/* Process share folders */
if (isset($_POST['share'])) {
    $userId = $_POST['share'];
    
    mysql_query("DELETE FROM `techer_permissions` WHERE `school_id` = {$_SESSION['schools_id']} AND `teacher_id` = {$userId}");
    if (isset($_POST['teacher_permission'][$userId])) {
        
        foreach ($_POST['teacher_permission'][$userId] as $item) {
            $grade_res = mysql_fetch_assoc(mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category'  AND id IN ({$item}) AND `active` = 1"));
            mysql_query('INSERT INTO techer_permissions SET '
                    . 'teacher_id = \''.$userId.'\' , '
                    . 'permission = \'data dash\' , '
                    . 'grade_level_id = \''.$item.'\' , '
                    . 'grade_level_name = \''.$grade_res['name'].'\' , '
                    . 'school_id = \''.$_SESSION['schools_id'].'\' ');
        }
    }
    if (isset($_POST['folders'][$userId])) {
        // Delete existed data
        mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");

        // Insert new data
        foreach ($_POST['folders'][$userId] as $item) {
            mysql_query("INSERT INTO `shared` (`schoolId`, `userId`, `termId`) VALUES ({$_SESSION['schools_id']}, {$userId}, {$item})");
        }

        //Send email
        sendEmailNoticeShared($school['SchoolMail'], $userId);
    } else {
        echo "<script type='text/javascript'>alert('Please choose your registered folder(s) to share!');</script>";
    }
}

/* Process revoke */
if (isset($_POST['revoke'])) {
    $userId = $_POST['revoke'];
    mysql_query("DELETE FROM `shared` WHERE `schoolId` = {$_SESSION['schools_id']} AND `userId` = {$userId}");
    mysql_query("DELETE FROM `techer_permissions` WHERE `school_id` = {$_SESSION['schools_id']} AND `teacher_id` = {$userId}");
}

$users = mysql_query("
	SELECT `users`. * , GROUP_CONCAT( `shared`.`termId` SEPARATOR ',' ) AS shared_terms
	FROM `users`
	LEFT JOIN `shared` ON `users`.`id` = `shared`.`userId`
	WHERE `users`.`school` = {$_SESSION['schools_id']}
	GROUP BY `users`.`id` 
");

$registered_folders = array();
$arra_grade_id = array();
if (mysql_num_rows($folders) > 0) {
    while ($folder = mysql_fetch_assoc($folders)) {
        $registered_folders[$folder['id']] = $folder['name'];
        $arra_grade_id[] = $folder['id'];
    }
}

$students_result = mysql_fetch_assoc(mysql_query('SELECT COUNT(id) as count FROM students WHERE school_id = \''.$_SESSION['schools_id'].'\' '));

?>

<div class="container">
      <?php  include "school_header.php";?>
</div>
          
          
          
          
          
   <!----Create Slotts------>
   <div class="container">
   <div id="content" class="col-md-12">
				<div id="single_question" class="content_wrap">
					<div class="ct_heading clear">
						<h3><i class="fa fa-plus-circle"></i>
                                                    <?php  //echo $result?'Edit':'Add';?> Create a Time Slot </h3>
					</div>		<!-- /.ct_heading -->
					<div class="ct_display clear">
	<form name="form_passage" id="form_passage" method="post" action="" enctype="multipart/form-data">
		<?php if(isset($msg)){?>					
            <p class="text-danger"><strong><?=$msg?></strong>
            <a href="view-slots.php" target="_blank" class="btn btn-success btn-xs">view Slots</a>
                </p><br/> <?php }?>
            
            <h4>+Create a Time Slot</h4>
							<div class="add_question_wrap clear fullwidth">
                                                            <p class="col-md-6">
                                                                 <?php 
                                                                 $today = date("Y-m-d H:i:s");   // def
                                                                 //$fddf=dat("d/m");
                                                                 ?>   
                                                                    
									<label for="lesson_name">Select Date:</label>
                                                                        <input  type="date" name="slot_date_time"
                                                                 id="slot_date_time" class="required textbox" 
                                                                 value="" />
								</p>
								
                                   <p class="col-md-6">
                                                                <label for="distrator_id">Time:</label>
                                                      <select name="time" id="time" class="required textbox">
                                                                     <option value="0:0">Time</option> 
                                                    <?php
                                                    $startTime  = new \DateTime('2010-01-01 00:00');
$endTime    = new \DateTime('2010-01-01 23:55');
$timeStep   = 15;// 5
$timeArray  = array();
                                                    while($startTime <= $endTime){
    $timeArray[] = $startTime->format('H:i');
    $startTime->add(new \DateInterval('PT'.$timeStep.'M'));
    //echo '<p>'.$startTime->format('H:i').'</p><br/>';  
    ?>
                                     <option 
                                         value="<?=$startTime->format('H:i')?>"><?=$startTime->format('H:i')?></option>                          
                                                                
                                                       <?php
                                                    }


                                                    
                                                    ?> 
                                                   </select>                 
                                                                
                                                                    
                                                                </p>                         
                                                                
                                                                
                                                                
                                                                <p>
									<label for="distrator_id">Select Teacher:</label>
						<?php
                                                    
                                                
                                                                   //  $q=" SELECT * FROM `users` WHERE 1 AND role=1 AND ";
                                                                //   $teacher= mysql_query($q);
     $teacher= mysql_query("
	SELECT `users`. * , GROUP_CONCAT( `shared`.`termId` SEPARATOR ',' ) AS shared_terms
	FROM `users`
	LEFT JOIN `shared` ON `users`.`id` = `shared`.`userId`
	WHERE `users`.`school` = {$_SESSION['schools_id']}
	GROUP BY `users`.`id` 
");                                                               
                                                                    
                                                                    
								//$teacher= mysql_query("SELECT * FROM `distrators` WHERE id NOT IN  (SELECT distrator_id FROM lessons)");
									
                                                                        
                                                                        if( mysql_num_rows($teacher) > 0 ) {
									?>
									
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        <select name="teacher_id" id="teacher_id" class="required textbox">
										<option value="0">Select Techer</option>
										<?php
										while($data= mysql_fetch_assoc($teacher) ) {
						//echo '<option value="' . $distrator['id'] . '" '.($default['distrator_id']==$distrator['id']?'selected':'').'>' . $distrator['name'] . '</option>';
										?>
                                                                                
                                                                      <option value="<?=$data['id']?>"><?=$data['first_name']?></option>          
                                                                                
                                                                                <?php  } ?>
                                                                        
                                                                        
                                                                                
									</select>
									<?php } ?>
								</p>
								
                                                               
                                                                
                                                                
								
								<!----<p>
									<label for="lesson_desc">Description:</label>
									<textarea name="lesson_desc" id="lesson_desc" class="textbox" rows="5">
                                                                           </textarea>
								</p>---->
                                                                
                                                                
                                                                
                                                                
                                                                
                                                                
							</div>
                                      <?php  if( mysql_num_rows($teacher) > 0 ) {?>
							<p>
					
			<input type="submit" name="slot_submit" id="slot_submit" class="form_button submit_button" value="Submit" />
								
                   <input type="reset" name="lesson_reset" id="lesson_reset" class="form_button reset_button" value="Reset" />
							</p>
                                                        <?php }else echo "No Teachers,You can not Assigned slot to Teachers"; ?>
                                                        
						</form>
						<div class="clearnone">&nbsp;</div>
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
                        
                        </div> 
          
          
          
          
          
          
          
          

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invitations</h4>
            </div>
            <div class="modal-body">
                <section id ="contact">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="header-section text-center">
                                <h2>Invite Your Teachers</h2>
                                <p>You can either copy and paste the URL below and send your own invite<br>or just insert your teachers' emails and we'll send it!<br>Here's the link your teachers need: <a style="color:blue">http://www.intervene.io/questions/signup.php</a></p>
                                <hr class="bottom-line">
                            </div>

                            <form id="form-invitation" method="post" action="email-invite.php">
                                <div id="mailboxes" class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <input name="email[]" type="email" size="30" placeholder="Insert a teacher's email address">
                                    </div>
                                </div>

                                <p>Please select registered grades to share:</p>
                                <?php
                                if (count($registered_folders) > 0) {
                                    echo "<div class='row'>
                                        <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6' style='border: 2px solid;padding: 0px;margin: 0px -4px 0px 3px;'>
                                        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'>
                                         <b>SmartPrep</b>
                                        </div>";
                                    foreach ($registered_folders as $key => $val) {
                                        echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
						<input type='checkbox' class='folders' name='folders[]' value='{$key}' /> {$val}
						</div>";
                                    }
                                    echo "</div>";
                                    
                                ?>
                                <?php
                            $school_data_dash_res = mysql_query('SELECT * FROM school_permissions WHERE school_id = \'' . $_SESSION['schools_id'] . '\' ');
                            if (mysql_num_rows($school_data_dash_res) > 0) {
                                echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-6' style='border: 2px solid;margin-left: 10px;padding: 0;width:48%;'>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12' style='text-align: center;'>
                                    <b>Data Dash</b>
                                </div>";
                                while ($school_permission = mysql_fetch_assoc($school_data_dash_res)) {
                                    ?>
                                <div class='col-lg-6 col-md-6 col-sm-6 col-xs-6'>
                                <input type="checkbox"  name="teacher_invite_permission[] ?>" value="<?php print $school_permission['grade_level_id'] ?>"  /><?php print $school_permission['grade_level_name'] ?>
                                </div>
                                <?php }
                                echo "</div>";
                            }
                            echo "</div><br>";
                            echo '<input type="submit" name="invite" value="Invite" class="btn btn-primary" >';
                                } else {
                                    echo "<p>Please purchase grades to share!</p>";
                                }
                            ?>
                            </form>
                        </div>
                    </div>
                </section>
            </div>	<!-- /Invite -->

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    <?php if ($error != '') { echo "alert('{$error}');"; } ?>
    $(document).ready(function () {
<?php
if (isset($_GET['invited'])) {
    echo 'alert("Invites have been sent to your teachers. Please ask them to check their spam folders in case they don\'t receive it in 5 minutes!");';
    echo 'location.replace("school.php");';
}
?>

        /* Validate invitation form */
        $('#form-invitation').on('submit', function () {
            var validmail = false;
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            $('#mailboxes input').each(function () {
                if (filter.test($(this).val())) {
                    validmail = true;
                    return false;
                }
            });
            if (!validmail) {
                alert('Please enter at least a valid email address!');
                return false;
            }

            var validgrade = false;
            $('#form-invitation input.folders').each(function () {
                if ($(this).is(':checked')) {
                    validgrade = true;
                    return false;
                }
            });

            if (!validgrade) {
                alert('Please select at least a grade to share!');
                return false;
            }
        });

        $('button[name=share]').on('click', function () {
            var checked = false;
            $(this).closest('tr').find('input.folders').each(function () {
                if ($(this).is(':checked')) {
                    checked = true;
                    return false;
                }
            });
            if (checked) {
                return true;
            } else {
                alert('Please choose your registered folder(s) to share!');
                return false;
            }
        });

        $('button[name=revoke]').on('click', function () {
            return confirm('Are you sure you want to revoke this shared folder?') ? true : false;
        });
    });
</script>

<?php include("footer.php"); ?>

<script type="text/javascript">
<?php 


if ($error != '') echo "alert('{$error}')"; ?>
</script>