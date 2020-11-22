<?php
if ( ! defined( 'ABSPATH' ) ) {
  // Exit if accessed directly.
  exit;
}

// please use your plugin here
if (!function_exists('visitors_traffic_real_time_statistics_footer')){
function visitors_traffic_real_time_statistics_footer(){


	$deactivation_options_slug = 'visitors-traffic-real-time-statistics'; // plugin slug
	$deactivation_options_vote_id = 10622878; //ref: https://polldaddy.com
	?>
	
<style type="text/css">
  .<?php echo $deactivation_options_slug;?>-deactivate-survey-modal {
    display: none;
    table-layout: fixed;
    position: fixed;
    z-index: 9999;
    width: 100%;
    height: 100%;
    text-align: center;
    font-size: 14px;
    top: 0;
    left: 0;
    background: rgba(0,0,0,0.8);
  }
  .<?php echo $deactivation_options_slug;?>-deactivate-survey-wrap {
    display: table-cell;
    vertical-align: middle;
  }

  .<?php echo $deactivation_options_slug;?>-deactivate-survey {
    
    border: 0 solid #ccc;
    border-radius: 3px;
    margin: 0 auto;
    padding: 12px;
    width: 400px;
    direction: ltr;
  }
  
  @media only screen and (max-width: 360px)
  {
	.<?php echo $deactivation_options_slug;?>-deactivate-survey {
    
    border: 0 solid #ccc;
    border-radius: 3px;
    margin: 0 auto;
    padding: 12px;
    width: 100%;
    direction: ltr;
	}  
  }
  .pds-question-top
  {
	  margin-bottom:20px;
  }
  #PDI_container<?php echo $deactivation_options_vote_id;?> .pds-box
	  {
		  width:400px !important
	  }

  .<?php echo $deactivation_options_slug;?>-deactivate-survey a.button {
    white-space: normal;
    height: auto;
	background-color: #F1F1F1 !important;
	border: #829237 solid 1px !important;
	margin-top:-50px;
	color:#829237;
	

  }
  .<?php echo $deactivation_options_slug;?>-deactivate-survey a.button:hover {
	 
	  color:black;
	  background-color:#cccccc !important;
  }
  .css-links pds-links{
	  display:none !important;
  }
  
  @media only screen and (max-width: 360px) {
	  #PDI_container<?php echo $deactivation_options_vote_id;?> .pds-box
	  {
		  width:95% !important
	  }
  }
  #PDI_container<?php echo $deactivation_options_vote_id;?> .pds-box .pds-vote-button, .pds-box .pds-vote-button-load
  {
	      background-color: #0073AA !important;
		  cursor: pointer !important;
		  border: none !important;
		  
  }
  
  #PDI_container<?php echo $deactivation_options_vote_id;?> .pds-box
  {
	  min-height: 100px !important;
	  text-align: center;
  }
  
  #PDI_container<?php echo $deactivation_options_vote_id;?> .pds-box .pds-links a, .pds-box .pds-links-back a{
	  font-size:10px;
	  margin-bottom:-20px
	  

  }
  
  .pds-return-poll, .pds-pd-link{
	  display:none !important;
  }
</style>


	<script type="text/javascript">
  jQuery(function($){
    var deactivateLink = $('#the-list').find('[data-slug="<?php echo $deactivation_options_slug;?>"] span.deactivate a');
    var overlay = $('#<?php echo $deactivation_options_slug;?>-deactivate-survey');
    var closeButton = $('#<?php echo $deactivation_options_slug;?>-deactivate-survey-close');
    var formOpen = false;

    deactivateLink.on('click', function(event) {
      event.preventDefault();
      overlay.css('display', 'table');
      formOpen = true;
    });

    closeButton.on('click', function(event) {

	  event.preventDefault();
      overlay.css('display', 'none');
      formOpen = false;
      location.href = deactivateLink.attr('href');
    });

    $(document).keyup(function(event) {
      if ((event.keyCode === 27) && formOpen) {
        location.href = deactivateLink.attr('href');
      }
    });
	
	$('#pd-vote-button10622878').html('Submit');
	
	
  });
</script>

<div class="<?php echo $deactivation_options_slug;?>-deactivate-survey-modal" id="<?php echo $deactivation_options_slug;?>-deactivate-survey">
  <div class="<?php echo $deactivation_options_slug;?>-deactivate-survey-wrap">
    <div class="<?php echo $deactivation_options_slug;?>-deactivate-survey">

      <script type="text/javascript" charset="utf-8" src="https://secure.polldaddy.com/p/<?php echo $deactivation_options_vote_id;?>.js"></script>

      <a class="button" id="<?php echo $deactivation_options_slug;?>-deactivate-survey-close"><?php _e('Close this window & deactivate', $deactivation_options_slug); ?></a>
    </div>
  </div>
</div>

<?php
}
}
add_action( 'admin_footer', 'visitors_traffic_real_time_statistics_footer');
?>