<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.25.0/slimselect.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.25.0/slimselect.min.css" rel="stylesheet"></link>
	
<?php
if(!current_user_can('manage_options'))
				{
					die('Sorry, you are not allowed to access this page.' );
					return;
				}
?>
<script language="javascript" type="text/javascript">


    function imgFlagError(image) {
	image.onerror = "";
	image.src = "<?php echo plugins_url('/images/flags/noFlag.png', AHCFREE_PLUGIN_MAIN_FILE) ?>";
	return true;
    }
	
	
</script>
<style type="text/css">
    i{
	color:#999	
    }
	
	.panel{
	width:90% !important;
    margin-bottom: 10px;
	border: 1px solid transparent;
	padding:20px;
	
   
}
a, div {
    outline: 0;
}
	
body{
		background-color:#F1F1F1
	}
</style>
<?php
//ahcfree_include_scripts();
$msg = '';
if (!empty($_POST['save'])) {
    if (ahcfree_savesettings()) {
	$msg =('<br /><b style="color:green; margin-left:30px; float:left">settings saved successfully</b><br /><b style=" margin-left:30px; float:left"><a href="admin.php?page=ahc_hits_counter_settings">back to settings</a> | <a href="admin.php?page=ahc_hits_counter_menu_free">back to dashboard</a></b>');
    }
}
$ahcfree_get_save_settings = ahcfree_get_save_settings();
$hits_days = $ahcfree_get_save_settings[0]->set_hits_days;
$ajax_check = ($ahcfree_get_save_settings[0]->set_ajax_check * 1000);
$set_ips = $ahcfree_get_save_settings[0]->set_ips;

$delete_plugin_data = get_option('ahcfree_delete_plugin_data_on_uninstall');
$ahcfree_hide_top_bar_icon = get_option('ahcfree_hide_top_bar_icon');

$ahcfree_ahcfree_haships = get_option('ahcfree_ahcfree_haships');
$ahcfree_save_ips = get_option('ahcfree_save_ips_opn');
$ahcproUserRoles = get_option('ahcproUserRoles');
$ahcproRobots = get_option('ahcproRobots');
?>
<div class="ahc_main_container" >
    <h1><img width="40px" src="<?php echo plugins_url('/images/logo.png', AHCFREE_PLUGIN_MAIN_FILE) ?>">&nbsp;Visitor Traffic Real Time Statistics Free <a title="change settings" href="admin.php?page=ahc_hits_counter_settings"><img src="<?php echo plugins_url('/images/settings.jpg', AHCFREE_PLUGIN_MAIN_FILE) ?>" /></a></h1><br />
    <div class="panel">
        <h2 class="box-heading">Settings</h2><hr>
	<div class="panelcontent">
	    <form method="post"  enctype="multipart/form-data" name="myform">
		
		<div class="row">
				<div class="form-group col-md-6">
				
					<label for="exampleInput">show hits in last</label>
					<input type="text" value="<?php echo $hits_days ?>" class="form-control" id="set_hits_days" name="set_hits_days" placeholder="Enter number of days">
					<small id="Help" class="form-text text-muted">this will affect the chart in the statistics page. default: 14 day</small>
				</div>
				
				<div class="form-group col-md-2">
				<label for="exampleFormControlSelect1">Select Timezone</label>
					<select class="form-control" id="set_custom_timezone" name="set_custom_timezone">
					 
					<?php
				    $custom_timezone_offset = get_option('ahcfree_custom_timezone');
				    $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
				    foreach ($timezones as $key => $value) {
					?>
    				    <option value="<?php echo $value; ?>" <?php echo ( $value == $custom_timezone_offset ) ? 'selected' : ''; ?>><?php echo $value; ?></option>
					<?php
				    }
				    ?>
					</select>
				</div>
			<div class="form-group col-md-4">
				<br><span  style="color:red; font-size:13px; ">Please select the same timezone in your</span> <a style="font-size:13px; " href="options-general.php" target="_blank">general settings page</a>
			</div>
					
		</div>
			
			
				
			<div class="row">	
				
				
				<div class="form-group col-md-6">
				
					<label for="exampleInput">check for online users every</label>
					<input type="text" value="<?php echo ($ajax_check / 1000) ?>" class="form-control" id="set_ajax_check" name="set_ajax_check" placeholder="Enter number of days">
					<small id="Help" class="form-text text-muted">Enter total seconds. default: 10 seconds</small>
				
				</div>
				<div class="form-group col-md-6">
					<label for="exampleInput">IP's to exclude</label>
					<textarea placeholder='192.168.0.1' name="set_ips" id="set_ips" rows="3"  class="form-control" ><?php echo $set_ips ?></textarea>
					<small id="Help" class="form-text text-muted">Excluded IPs will not be tracked by your counter, enter IP per line</small>
				</div>
				
				</div>
			
		<div class="row">
			<div class="form-group col-md-6">
			<label for='exampleInput'>Plugin Accessibility</label><br>
<?php			
	$html = '';			
			
    global $wp_roles;
    if ( !isset( $wp_roles ) ) $wp_roles = new WP_Roles();
	$capabilites = array();
    $available_roles_names = $wp_roles->get_names();//we get all roles names
    $available_roles_capable = array();
    foreach ($available_roles_names as $role_key => $role_name) { //we iterate all the names
        $role_object = get_role( $role_key );//we get the Role Object
        $array_of_capabilities = $role_object->capabilities;//we get the array of capabilities for this role
       
            $available_roles_capable[$role_key] = $role_name; //we populate the array of capable roles
        
    }
    
		$html .= '';
		$UserRoles = get_option('ahcproUserRoles');
		
		$UserRoles_arr = explode(',',$UserRoles);
		$html .="<select id='ahcproUserRoles' name='ahcproUserRoles[]' multiple='true' style='width:50%;'>";
		foreach($available_roles_capable as $role)
		{
			$translated_role_name = $role;
			if(in_array($translated_role_name, $UserRoles_arr) or $translated_role_name == 'Administrator' or $translated_role_name == 'Super Admin')
			{
				$selected_value = 'selected=selected';
			}else{
				$selected_value = '';
			}
			 $html .="<option ".$selected_value." value='".$translated_role_name."'>".$translated_role_name."</option>";
		}
		
		$html .='</select>';
		
	
	echo $html;
	?>
				
<script language="javascript" type="text/javascript">
new SlimSelect({
  select: '#ahcproUserRoles'
})

</script>
				</div></div>
				


			<div class="row">
				<div class="form-group col-md-6">
					<label for="exampleInput">Hide Top Bar Icon</label>
					<p> <label><input type="checkbox" value="1" name="ahcfree_hide_top_bar_icon" <?php echo ($ahcfree_hide_top_bar_icon == 1) ? 'checked=checked' : ''; ?> > If checked, We will hide the top bar icon. </label></p>
			    <br />
					</div>
					
					
			</div>
			
			<div class="row">
				<div class="form-group col-md-6">
					<label for="exampleInput">Hash IPs</label>
					<p> <label><input type="checkbox" value="1" name="ahcfree_ahcfree_haships" <?php echo ($ahcfree_ahcfree_haships == 1) ? 'checked=checked' : ''; ?> > If checked, We will hide the last 3 digits in all IP's. </label></p>
			    <br />
					</div>
					
					
			</div>
			
			
			<div class="row">
				<div class="form-group col-md-6">
					<label for="exampleInput">Stats Data</label>
					<p> <label style="color:red"><input type="checkbox" value="1" name="delete_plugin_data" <?php echo ($delete_plugin_data == 1) ? 'checked=checked' : ''; ?> > If checked, all the stats data will be deleted on deleting plugin. </label></p>
			    <br />
					</div>
					
					
			</div>
			</div>
		    </div>
		    <input type="submit" name="save" value="save settings" style="font-size:15px" />
		    <input type="button" name="cancel" value="back to dashboard" onclick="javascript:window.location.href = 'admin.php?page=ahc_hits_counter_menu_free'" style="font-size:15px" />
		</div>
<?php
echo $msg;
	?>
	    </form>
	