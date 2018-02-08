<style>
.lnk_url{color:#000000 !important; text-decoration:none !important;}
</style>
<div class="offer-popup" id="offer-popup">
  <div class="popup-box">
    <div class="close"><a id="close-pop" class="closepopup" href="javascript: void(0)" tabindex="12"><?php
echo '<img src="' . plugins_url( 'images/close.png' , __FILE__ ) . '" > '; ?></a></div>
    <div class="popup-box-wrap">
      <h2 class="headtabs">
	  	<?php if($offer_url!==''){ ?>
			<a href="<?php echo $offer_url; ?>" title="<?php echo $offer_name; ?>" target="_blank" class="lnk_url"><?php echo $offer_name; ?></a>
		<?php }else{ ?>
        	<?php echo $offer_desc; ?>
        <?php } ?>
		</h2>
      <div class="offer-content">
        <?php echo $offer_desc; ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	var $offerpopup = $('.offer-popup',this);
	var $popupbox = $(".popup-box",$offerpopup);
	$(window).bind('load resize',function(){
		$offerpopup.css({'height':$("html").outerHeight()+'px'});
		$(".popup-box",$offerpopup).css({'margin-left':-$popupbox.outerWidth()/2+'px'});
		$(".popup-box",$offerpopup).css({'margin-top':-$popupbox.outerHeight()/2+'px'});
	});
	$offerpopup.show(); 
	$("body").click(function(event) {
		if(event.target!==$offerpopup){
			$offerpopup.hide();
		}
	});
	$('.closepopup',$offerpopup).click(function() { 
		$offerpopup.hide(); 
		$('html').scrollTop(0);
	});
});

</script>
<style type="text/css">
.admin-bar .offer-popup { top: 32px; }
.offer-popup { position: absolute; left: 0; top: 0; width: 100%; height: 100%; z-index: 99999; background: rgba(0,0,0,0.3); display: none; }
.offer-popup .popup-box { position: absolute; top: 30%; left: 50%; margin-left: -180px; }
.offer-popup .headtabs { border-bottom: 1px solid #6ea2b2; background: none repeat scroll 0 0 #FFFFFF; border-bottom: 1px solid #ccc; padding: 10px; margin: 0; font-size: 18px; }
.offer-popup .popup-box .close { font-family: Arial, Helvetica, sans-serif; font-weight: bold; display: inline-block; height: 31px; position: absolute; right: -18px; top: -13px; width: 31px; }
.offer-popup .popup-box .close:hover { color: #000; }
.offer-popup .popup-box .top { padding: 20px; }
.offer-popup .popup-box .popup-box-wrap { background-color: #eee; box-shadow: 0 0 20px 5px rgba(0,0,0,.1); border: 1px solid #e5e5e5; border-radius: 5px; }
.offer-popup .popup-box .offer-content { padding: 20px; }
.offer-popup .popup-box img{
	max-width: 500px;
	max-height: 500px;
}

<?php echo $offer_custom_css; ?>
</style>