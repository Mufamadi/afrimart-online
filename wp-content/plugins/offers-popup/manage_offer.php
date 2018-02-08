<?php
global $wpdb;
$msg = "";
if(count($offer_detail)>0)
{
	$offer_data = $offer_detail[0];
	$offer_name = $offer_data->offer_name;
	$offer_start = $offer_data->offer_start;
	$offer_end = $offer_data->offer_end;
	$offer_url = $offer_data->offer_url;
	$offer_custom_css = $offer_data->offer_custom_css;
	$status = $offer_data->status;
	$offer_desc = $offer_data->offer_desc;
	$offer_id = $offer_data->offer_id;
}

if($_POST)
{
	$offer_id = $_POST['offer_id'];
	
	$offer_name = $_POST['offer_name'];
	$offer_start = $_POST['offer_start'];
	$offer_end = $_POST['offer_end'];
	$offer_url = $_POST['offer_url'];
	$offer_custom_css = $_POST['offer_custom_css'];
	$status = $_POST['status'];
	$offer_desc = stripslashes($_POST['offer_desc']);

	if($offer_id!=''){
		$update_sql = "Update ".$wpdb->prefix."offers SET `offer_name`='".$offer_name."', `offer_start`='".$offer_start."', `offer_end`='".$offer_end."', `offer_url`='".$offer_url."', `offer_custom_css`='".$offer_custom_css."',`status`='".$status."',`offer_desc`='".$offer_desc."' where `offer_id`='".$offer_id."'";
		$wpdb->query($update_sql);
		$msg = "2";
	}else{
		$update_sql = "INSERT INTO ".$wpdb->prefix."offers SET `offer_name`='".$offer_name."', `offer_start`='".$offer_start."', `offer_end`='".$offer_end."', `offer_url`='".$offer_url."', `offer_custom_css`='".$offer_custom_css."',`status`='".$status."',`offer_desc`='".$offer_desc."'";
		$wpdb->query($update_sql);
		$msg = "1";
	}
	
	echo '<meta http-equiv="refresh" content="0;url=admin.php?page=offers_admin.php&info='.$msg.'">';
	exit;
}
?>

<div class="wrap">
  <?php 
if(count($offer_detail)>0){ ?>
  <h2>Edit Offer</h2>
  <?php }else{ ?>
  <h2>Add New Offer</h2>
  <?php } ?>
  <form action="admin.php?page=offers_add_offer" method="post" name="manage_offer" id="manage_offer" onSubmit="return checkallfields()" enctype="multipart/form-data">
    <div class="manage_offer">
      <div class="offer_label">Offer Name</div>
      <div class="offer_input">
        <input type="text" name="offer_name" id="offer_name" value="<?php if(isset($offer_name)){echo $offer_name; } ?>" autofocus />
        <input type="hidden" name="offer_id" id="offer_id" value="<?php if(isset($offer_id)){echo $offer_id; } ?>" />
      </div>
      <div class="clear"></div>
      <div class="offer_label">Offer Start</div>
      <div class="offer_input">
        <input type="text" id="offer_start" name="offer_start" value="<?php if(isset($offer_start)){echo $offer_start; } ?>"/>
      </div>
      <div class="clear"></div>
      <div class="offer_label">Offer End</div>
      <div class="offer_input">
        <input type="text" id="offer_end" name="offer_end" value="<?php if(isset($offer_end)){echo $offer_end; } ?>"/>
      </div>
      <?php if($offer_exist){ ?>
      <div class="offererror"><?php echo $offer_exist; ?></div>
      <?php } ?>
      <div class="clear"></div>
      <div class="offer_label">Description</div>
      <div class="offer_input">
        <?php the_editor($offer_desc, 'offer_desc'); ?>
      </div>
      <div class="clear"></div>
      <div class="offer_label">Offer URL</div>
      <div class="offer_input">
        <input type="text" id="offer_url" name="offer_url" value="<?php if(isset($offer_url)){echo $offer_url; } ?>"/>
        &nbsp;(http://example.com) </div>
      <div class="clear"></div>
      <div class="offer_label">Custom CSS</div>
      <div class="offer_input">
        <textarea name="offer_custom_css" class="wide"><?php if(isset($offer_custom_css)){echo $offer_custom_css; } ?>
</textarea>
        <div class="note">
          <p>You may use these css classes:<br />
            <code>.offer-popup, .popup-box, .headtabs , .close, .popup-box-wrap, .offer-content</code></p>
        </div>
      </div>
      <div class="clear"></div>
      <div class="offer_label">Offer Status</div>
      <div class="offer_input">
        <input type="radio" name="status" id="status" <?php if(isset($status) || $status='1'){ ?> checked="checked" <?php }?> value="1"/>
        Enabled&nbsp;
        <input type="radio" name="status" id="status" <?php if(!isset($status) || $status!='1'){ ?> checked="checked" <?php }?> value="0" />
        Disabled</div>
      <div class="clear"></div>
      <div class="offer_input">
        <?php if(count($offer_detail)>0){ ?>
        <input type="submit" value="Update" name="submit" class="button button-primary button-large"/>
        <?php }else{ ?>
        <input type="submit" value="Save" name="submit" class="button button-primary button-large"/>
        <?php } ?>
        <input type="button" value="Cancel" name="cancel" onclick="location.href='admin.php?page=offers_admin.php'" class="button button-primary button-large" />
      </div>
      <div class="clear"></div>
    </div>
  </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#offer_start').datepicker({
        dateFormat : 'yy-mm-dd',
		minDate: 0
    });
});

jQuery(document).ready(function() {
    jQuery('#offer_end').datepicker({
        dateFormat : 'yy-mm-dd',
		minDate: 0
		
    });
});
</script>