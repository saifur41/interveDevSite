<?php
/**

include("header.php");
include('libraries/newrow.functions.php');
@Tutor Room
**/


include("header.php");
include('libraries/newrow.functions.php');
$today = date("Y-m-d H:i:s"); // 
 $success_msg=[];
////////////////////////////////////
// echo 'new NewrowToken for admin Creeated!, Reload page again! '; 
  $currToken=_get_token(); 
 // echo 'Token=='.$currToken;  die; 


 // if(!isset($_GET['id'])){
 // exit('?id=4443, Intervention_id missing');
 // }


 $Intervention_id=$_GET['id'];

 $Intervention_id='2892';// Testing
 ///////////////////////////

   $Tutor_id=(isset($_SESSION['ses_tutor_id']))?$_SESSION['ses_tutor_id']:125;

  

  $Room=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_rooms WHERE ses_tutoring_id= '$Intervention_id' ")); #Test
    //print_r($Room); die; 
   $get_newrow_room_id=$Room['newrow_room_id'];  //Intervention_id

   $Tutor_newrow=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_x_tutors WHERE tutor_intervene_id= '$Tutor_id'  "));
   // print_r($Tutor_newrow); die; 
   ///////////Check tutor if aleardy Assgined ////////
   //  echo 'Assgined status==';//$Tutor_id=23;

    $isTutorAdded=mysql_fetch_assoc(mysql_query(" SELECT * FROM newrow_room_users WHERE ses_tutoring_id= '$Intervention_id' 
      AND intervene_user_id= '$Tutor_id' AND user_type= 'tutor' "));

    if(isset($isTutorAdded)&&!empty($isTutorAdded)){
      $canGetRoomAccess='yes';  // then only get ROOM url for-tutor
      $success_msg[]='Tutor aleardy aded to rooom';
     

    }else{
      $canGetRoomAccess='no';
    }


    // var_export($isTutorAddedStatus);


    // die; 

   //////////////////


   $arr_board=[];
   $arr_board['newrow_room_id']=$get_newrow_room_id;
   $arr_board['newrow_tutor_id']=$Tutor_newrow['newrow_ref_id'];



   $arr_board['live_tutoring_id']=$Intervention_id;

   //print_r($arr_board); die; 

   //////Get ROOM Link ///////////////////////////
   if(isset($canGetRoomAccess)&&$canGetRoomAccess=='yes'){
    // var_export($canGetRoomAccess);

    $n_room_id=$arr_board['newrow_room_id'];  //'24041';
  $n_user_id=$arr_board['newrow_tutor_id']; //'32284';




///////////////////////////

$token=$currToken;  //Get token 

 // $Api_url='https://smart.newrow.com/backend/api/rooms/url/'.$n_room_id.'?user_id='.$n_user_id;

  $Api_url='https://smart.newrow.com/backend/api/rooms/url/'.$n_room_id.'?user_id='.$n_user_id;

   //echo $Api_url; die;
/////////////////////////////


 #$url='https://smart.newrow.com/backend/api/rooms/url/23995';
//setup the request, you can also use CURLOPT_URL
 // 'https://smart.newrow.com/backend/api/rooms/url/23995?user_id=32284'




 $ch = curl_init($Api_url);
// Returns the data/output as a string instead of raw data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//Set your auth headers
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
   'Content-Type: application/json',
   'Authorization: Bearer ' . $token
   ));

// get stringified data/output. See CURLOPT_RETURNTRANSFER
$data = curl_exec($ch);
 $Board=json_decode($data);
   //print_r($data); die; 
 

   //print_r($Board->data->url); die;


  // if($Board->status=='success'){
  //   $_SESSION['ses_student_launch_url']=$Board->data->url;
  // }
 
 //print_r($data); die; 

// get info about the request
$info = curl_getinfo($ch);
// close curl resource to free up system resources
curl_close($ch);
////////////////////////////////////////



  //  echo 'Get room link for tuotor'; die; 


   }





 //////////////////


 // print_r($success_msg); die; 




?>


<iframe
  allow="microphone *; camera *; speakers *; usermedia *; autoplay*;" 
  allowfullscreen   
  src="<?php echo $Board->data->url ;?>"  height="100%" width="100%">
</iframe> 