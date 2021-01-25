
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>==Telpas-Intervention Question::Testing</title>

  <script type="text/javascript">
    var base_url = '<?php print $base_url; ?>';
    console.log('Root Url::'+base_url);
  </script>



  <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/tinymce/tinymce.min.js"></script>
  <script type="text/javascript" src="js/tinymce/plugins/asciimath/js/ASCIIMathMLwFallback.js"></script>
  <script type="text/javascript">var AMTcgiloc = "http://www.imathas.com/cgi-bin/mimetex.cgi";</script>
  <script type="text/javascript">
    tinymce.init({
      selector: "textarea#question_question, textarea#question_question_spanish, textarea#response_answer, textarea#passage_content, textarea#passage_content_spanish, textarea#response_answer_spanish, textarea#package_description, textarea#lesson_desc",
      theme : "modern",
      paste_data_images: true,
      plugins : [
        "asciimath code asciisvg",
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern imagetools"
      ],
      tools : "inserttable",
      menubar : false,
      relative_urls : false,
      file_picker_callback: function(callback, value, meta) {
        if (meta.filetype == 'image') {
          $('#upload').trigger('click');
          $('#upload').on('change', function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
              callback(e.target.result, {
                alt: ''
              });
            };
            reader.readAsDataURL(file);
          });
        }
      },
      toolbar1: "insertfile styleselect | bold underline italic | alignleft aligncenter alignright alignjustify | table bullist numlist indent outdent | link image",
      toolbar2: "asciimath asciimathcharmap",
      AScgiloc : 'https://intervene.io/questions/php/svgimg.php',   //change me  
      ASdloc : 'js/tinymce/plugins/asciisvg/js/d.svg',      //change me   
      content_css: "css/content.css"
    });
    tinymce.init({
      selector: "input.answers",
      theme : "modern",
      plugins : [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor colorpicker textpattern imagetools"
      ],
      tools : "inserttable",
      menubar : false,
      relative_urls : false,
      toolbar1: "bold underline italic",
    });
  </script>
  <link type="text/css" href="css/font-awesome.min.css" rel="stylesheet" />
  <link type="text/css" href="css/bootstrap.min.css" rel="stylesheet" />
  <link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet" />
  <link type="text/css" href="css/style.css" rel="stylesheet" />
  <link type="text/css" href="css/form.css" rel="stylesheet" />
  <link type="text/css" href="css/front-end.css" rel="stylesheet" />
  <meta name="Description" content="We're changing the way teachers teach, one question at a time.">
  <meta name="Keywords" content="measurement worksheet, geometry worksheet, math quiz, STAAR questions, STAAR practice, place value worksheet, free worksheet, free homework, free test generator, freebie, teacher freebie, fractions worksheet, word problems, graphs">
  <!-- Quick Sprout: Grow your traffic -->
  <script>
    (function(e,t,n,c,r){c=e.createElement(t),c.async=1,c.src=n,
    r=e.getElementsByTagName(t)[0],r.parentNode.insertBefore(c,r)})
    (document,"script","https://cdn.quicksprout.com/qs.js");
  </script>
  <!-- End Quick Sprout -->
  <!-- Hotjar Tracking Code for www.intervene.io -->
  <script>
    (function(h,o,t,j,a,r){
      h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
      h._hjSettings={hjid:544568,hjsv:5};
      a=o.getElementsByTagName('head')[0];
      r=o.createElement('script');r.async=1;
      r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
      a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
  </script>
</head>



<body style="<?php echo ($_SESSION['login_role'] > 0) ? "-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;" : ""; ?>">
  <?php 
///$logo="st_logo_demo.png";
//include_once("analyticstracking.php"); ?>


<div id="wrapper" class="clear fullwidth">

    <!-- header -->

   <?php  include "header.tpl.php"; ?>

   <!-- /#header -->



 <!-- Header -->
<div id="home main" class="clear fullwidth tab-pane fade in active">
   <div class="container">
      <div id="items_id" style="display:none;">Checking Sesion</div>
        <!-- row -->
      <div class="row">
         <div class="align-center col-md-12" style="margin-top:10px; margin-bottom:20px;">
            <div style=" width:auto;" title="">
               <?php include("nav_students_2.tpl.php"); ?>
            </div>
         </div>
         <!--  NExt col -->
         <div class="align-center col-md-12">
            <!-- StudentWelcome -->
            <div id="content" style="padding:0px;" > 
               <div class="content_wrap">
                  <div class="ct_heading clear" style="text-align: left;">
                     <h3>Welcome</h3>
                  </div>
                  <!-- /.ct_heading -->
                  <div class="ct_display clear">
                     <div class="item-listing clear">
                        <h3 class="notfound align-center">
                           <a href="#" Sut="<?php echo $_SESSION['student_id']?>">Welcome <?=(!empty($_SESSION['student_name']))?$_SESSION['student_name']:''?></a>
                        </h3>
                     </div>
                  </div>
               </div>


               <?php 
               #if(empty($respons['errorcode'])){ 
               if($isStudentShowCourseList=='yes'){
                echo '==Check for courses::<br/>';

                // Get enolled course Array for student
                ?>

            </div>
         </div>
         <br/>  <br/>
        <?php  

        // echo  'student_category_course: <pre>';
        // print_r($student_category_course); die; 

          $courseType=array('reading','listening','speaking','writing' );
        ///
        foreach ($student_category_course as $category_id=>$course_arr) { 
         
          ?>
       <!-- Category-itms -->
       <div class="col-md-12">
		 <div class="w-1000" style="margin: 20px 0px;" >
			 <div class="row text-center">
				  <h3 title="Week Practice:" class="text-center" style="margin-bottom: 30px;"><strong>
            <?=($category_arr[$category_id])?$category_arr[$category_id]:'Course Category';  //$category_id?> </strong></h3>
            <?php
            // Show row
            $courseType=array('reading','listening','speaking','writing' );
            foreach ($courseType as $course_key) {
              $line=$course_arr[$course_key];// $row1=$course_arr['reading'];
              $start_course_url='#NotEnroll';

              if($line['id']>0)
              $start_course_url='https://intervene.io/questions/telpas_practice.php?practStart='.$line['id'];

              $column_border='2px solid #ccc';
               if($course_key=='writing')
                $column_border='1px solid #fff';
          
            ?>

				   <div class="col-md-3" style="border-right:<?=$column_border?>;">
					  <p style="letter-spacing:1;" title="Course: <?php print(ucfirst($course_key)); ?>">
              <strong> <?php print(ucfirst($course_key)); ?>  </strong> </p>

             <?php  if(isset($line['student_progress'])){ //courseEnroll ?>

					  <p>Progress- <?=$line['student_progress']?>% </p>
					  <p> Status- <span style="color:#337ab7"><strong> <?=$line['Status']?> </strong></span> </p>

					  <a href="<?=$start_course_url?>" class="btn btn-primary btn-lg"> <?=$line['buttonState'] //$buttonState?> </a>

          <?php  }//if(isset($line['student_progress'])){ //courseEnroll ?>

				  </div>
          <?php  } ?>

			 </div>
	     </div>	
       </div>
       <?php 
        } // end course-cat items

       ?>
       <?php } else{?>
<br>
<a href="https://intervene.io/questions/telpas_practice.php?crta=1" class="btn btn-primary btn-lg">Please Click here to Start</a>
<?php }?>
         <!-- C0ntent -->
      </div>
   </div>
</div>
<style>
   .w-1000{
	   width:100%;
	   display:inline-block;
	   margin-bottom:40px;
	   border:2px solid #ddd;
	   padding-bottom:30px;
   }
</style>
<?php
/* use CURL Request for Signin Ans Signup Process in moodle */
if(isset($_GET['crta']))
{

    $courseID=$_GET['course'];
    require_once('MoodleWebServices/moodle-create-account.php');
}
?> 