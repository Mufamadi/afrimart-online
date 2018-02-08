<?php

echo' 	<div class="mo_otp_form"><input type="checkbox" '.$disabled.' id="wc_checkout" data-toggle="wc_checkout_options" class="app_enable" name="mo_customer_validation_wc_checkout_enable" value="1"
						'.$wc_checkout.' /><strong>'. mo_( "Woocommerce Checkout Form" ) . '</strong>';

				get_plugin_form_link(MoConstants::WC_FORM_LINK);

echo'			<div class="mo_registration_help_desc" '.$wc_checkout_hidden.' id="wc_checkout_options">
				<b>'. mo_( "Choose between Phone or Email Verification" ).'</b>
				<p><input type="radio" '.$disabled.' id="wc_checkout_phone" class="app_enable" name="mo_customer_validation_wc_checkout_type" value="'.$wc_type_phone.'"
						'.($wc_checkout_enable_type == $wc_type_phone ? "checked" : "" ).' />
							<strong>'. mo_( "Enable Phone Verification" ).'</strong>
				</p>
				<p><input type="radio" '.$disabled.' id="wc_checkout_email" class="app_enable" name="mo_customer_validation_wc_checkout_type" value="'.$wc_type_email.'"
						'.($wc_checkout_enable_type == $wc_type_email ? "checked" : "" ).' />
							<strong>'. mo_( "Enable Email Verification" ).'</strong>
				</p>
				<p style="margin-left:2%;margin-top:3%;">
					<input type="checkbox" '.$disabled.' '.$guest_checkout.' class="app_enable" name="mo_customer_validation_wc_checkout_guest" value="1" ><b>'. mo_( "Enable Verification only for Guest Users." ).'</b>'; 

					mo_draw_tooltip(MoMessages::showMessage('WC_GUEST_C