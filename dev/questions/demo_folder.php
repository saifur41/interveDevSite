<?php
/**

@Demo user folder access
@ 
**/



include("header.php");


  // print_r($_SESSION);


////////////////////////
$expired_url='demo_user_expire.php';

 if(isset($_SESSION['expired_user'])&&$_SESSION['expired_user']=='expired'){
 	header("Location:".$expired_url); exit;

 }

//echo 'Demo Suhail'; die;
// Get current term id - return 0 if no term is selected
$termId = (isset($_GET['taxonomy']) && is_numeric($_GET['taxonomy'])) ? $_GET['taxonomy'] : 0;

$user_data= mysql_fetch_assoc(mysql_query('SELECT * from demo_users WHERE id='.$_SESSION['demo_user_id']));

// Init condition for shared folders
$condition = "";

// Check if current logged in user is not administrator
 //$shared_folders = mysql_query("SELECT DISTINCT(`termId`) FROM `shared` WHERE `userId` = {$_SESSION['login_id']}");
 




////////Demo user access/////////

            $shared = array();
            $shared=explode(",",$user_data['smart_prep']);// Shared Smart Prep Grade to demo user
         // echo '==';  print_r($arr_grad);
           // $shared[] = 22; $shared[] = 23; $shared[] = 2630;
	if( count($shared) > 0 ) {
		
		
		// Get list parents of shared folders
		$parents = array();
		$shared_parents = mysql_query("SELECT DISTINCT(`parent`) FROM `terms` WHERE `id` IN (" . implode(',', $shared) . ")");
		while( $row = mysql_fetch_array($shared_parents) )
			$parents[] = $row['parent'];
            //
                $condition = ($termId == 0) ? implode(',', $parents) : implode(',', $shared);
	$condition = "AND `id` IN ($condition)";
	}
      //  print_r($shared);   print_r($parents); // Test parent and Allowed folders to Demo user
        
       

	


////////////////////
// Query term title
$select = mysql_fetch_assoc( mysql_query("SELECT `name` FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 AND `id` = {$termId}") );	# Return @boolean false if not found
$title	= $select ? $select['name'] : 'Folder Questions';

// Count children of current taxonomy. Redirect to questions.php if no item found
//$childs =("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 {$condition} AND `parent` = {$termId} ORDER BY `name` ASC");
 $childs =mysql_query("SELECT * FROM `terms` WHERE `taxonomy` = 'category' AND `active` = 1 {$condition} AND `parent` = {$termId} ORDER BY `name` ASC");



 //echo '<pre>',$childs; die;
  
if( mysql_num_rows($childs) == 0 ) {
 $select = mysql_fetch_assoc( mysql_query("SELECT * FROM `terms` WHERE `id` = {$termId} AND `active` = 1") );		# Return @boolean false if not found
	//$select = mysql_fetch_assoc( mysql_query("SELECT * FROM `terms` WHERE `id` =1 AND `active` = 1") );
    
    if( $select ) {
		$title = $select['name'];
		$clause = ( $select['taxonomy'] == 'objective' )
				? " INNER JOIN `term_relationships` r ON q.`id` = r.`question_id` WHERE r.`objective_id` = {$termId}"
				: " WHERE {$select['taxonomy']} = {$termId}";
	} else {
		$title = 'List Questions';
		$clause = '';
	}

	$passage_id = 0;
	if(isset($_GET['passage']) && $_GET['passage']!="")$passage_id = $_GET['passage'];

	if(isset($_SESSION['list'])){
		#if isset $_SESSION['list'] no query it.
		if($clause==""){
			$notin = " WHERE q.`id` NOT IN ( '" . implode($_SESSION['list'], "', '") . "' ) AND q.`passage`<>0"; 
		}else{
			$notin = " AND q.`id` NOT IN ( '" . implode($_SESSION['list'], "', '") . "' ) AND q.`passage`<>0";
		}
	}else{
		$_SESSION['list'] = array();
		if($clause==""){
			$notin = "WHERE q.`passage`<>0";
		}else{
			$notin = " AND q.`passage`<>0";
		}
	}

	$childs =mysql_query("SELECT DISTINCT `passage` FROM `questions` q" . $clause .$notin. ' ORDER BY `date_created` DESC');
	//echo '<pre>',$childs; die;
        
        //////////////mysql_query
	while( $item = mysql_fetch_assoc($childs) ) {
		$array_passage[]=$item['passage'];
	}
	$childs_passage = mysql_query("SELECT `id`,`title`,`date_created` FROM `passages` p WHERE `id` IN ( '" . implode($array_passage, "', '"). "' ) ORDER BY `date_created` DESC");
	
	if($childs_passage && mysql_num_rows($childs_passage)==0){
		header('Location: demo_questions.php?taxonomy=' . $termId);
		exit;
	}
	
}
?>

 


<div id="main" class="clear fullwidth"   >
	<div class="container">
		<div class="row">
			<div id="sidebar" class="col-md-4">
				<?php include("demo_sidebar.php"); ?>
			</div>		<!-- /#sidebar -->
			
                        
                        
                        
                        <div id="content" class="col-md-8">
				<div id="folder_wrap" class="content_wrap">
					<div class="ct_heading clear">
						<h3><?php echo $title; ?></h3>
						<?php if($role==0 && !$childs_passage):?>
						<ul>
							<li><a href="javascript: void(0);" class="popup_form" data-target="#folder_dialog" title="New Folder"><i class="fa fa-plus-circle"></i></a></li>
							<li><a href="javascript: void(0);" class="popup_edit" data-target="#folder_dialog"><i class="fa fa-pencil"></i></a></li>
							<?php if( isGlobalAdmin() ) : ?>
								<li><a href="javascript: void(0);" class="remove_items" data-type="category"><i class="fa fa-trash"></i></a></li>
							<?php endif; ?>
						</ul>
						<?php endif; ?>
					</div>		
                                    
                                    
                                    <!-- /.ct_heading -->
					
                                        <div class="ct_display clear" >
						<?php
                                             if(!empty($user_data['smart_prep']))
					$arr_grad=explode(",",$user_data['smart_prep']);   
                                    //print_r($arr_grad); // die;
                                                ///////////////////////
					if(!$childs_passage):
						$i = 1;
						while( $item = mysql_fetch_assoc($childs) ) {
                                                    
                                                    // 1 Subject:: math
                                                //if(!isset($_GET['taxonomy'])&&$i==2)break;
                                                   // 1 Subject:: math
                                                
							$url = 'demo_folder.php?taxonomy=' . $item['id'];
                                                        /////////Grad Check # taxonomy=1
                                                        if(isset($_GET['taxonomy'])&&$_GET['taxonomy']==1){
                                                            
                                     if(count($arr_grad)>0&&!in_array($item['id'], $arr_grad))continue;
                                                    
                                                        }

                                                   // $str_grad=$user_data['smart_prep'];   
                                           ////////////////      
                                                        ?>
		<div class="item-wrap col-lg-3 col-md-3 col-sm-6 col-xs-6">
							<? //=$str_grad ?>	<p><a href="<?php echo $url; ?>"><i class="fa fa-folder-open fa-4x"></i></a></p>
								<p class="item-title">
									<?php if($role==0):?>
									<input type="checkbox" class="edit_items" value="<?php echo $item['id']; ?>" data-parent="<?php echo $item['parent']; ?>" title="Edit Folder (<?php echo $item['name']; ?>)" />
									<?php endif;?>
	<a class="item_name" href="<?php echo $url; ?>"><?php echo $item['name']; ?></a>
								</p>
								<!--
								<p class="item-desc"><?php echo date('Y M d', strtotime($item['date_created'])); ?></p>
								-->
							</div>
						<?php
			//echo ($i % 4 == 0 || $i == mysql_num_rows($childs)) ? '<div class="clearnone">&nbsp;</div>' : '';
							$i++;
						}   ?>
                                                    
                                           <div class="clearnone">&nbsp;</div>         
                                            <!-- Passagee---->
                                            <?php
                                                
                                                ////////////// Section-2 
                                                
						else:
						// echo "<p>No item found!</p>";
						// return;
						$i = 1;
						while( $item = mysql_fetch_assoc($childs_passage) ) {
							$url = 'demo_questions.php?taxonomy=' .$termId.'&passage='. $item['id'];
													$passageId=$item['id'];
                                                    $sql="SELECT * FROM `questions` WHERE passage=".$passageId." AND public=1 ";
                                                     if(mysql_num_rows(mysql_query($sql))<=0)continue;
						?>
			<div class="item-wrap col-lg-3 col-md-3 col-sm-4 col-xs-6">
			<p><a href="<?php echo $url; ?>"><i class="fa fa-paragraph fa-4x"></i></a></p>
								<p class="item-title">
									<a class="item_name" href="<?php echo $url; ?>"><?php echo $item['title']; ?></a>
								</p>
								<p class="item-desc"><?php echo date('Y M d', strtotime($item['date_created'])); ?></p>
							</div>
						<?php
							//echo ($i % 4 == 0 || $i == mysql_num_rows($childs)) ? '<div class="clearnone">&nbsp;</div>' : '';
							$i++;
						}
						
						endif;
						?>
                                           
                                           <!-- Passagee---->
                                                    
                      <div class="clearnone">&nbsp;</div>                              
                                                   
					</div>		<!-- /.ct_display -->
				</div>
			</div>		<!-- /#content -->
			<div class="clearnone">&nbsp;</div>
		</div>
	</div>
</div>		<!-- /#header -->

<?php include("footer.php"); ?>
<?php ob_flush(); ?>