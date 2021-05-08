<?php
/**
 * Admin View: Product import form
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
	<div class="wrap" id="wpbody">
		<div class="welcome-panel" id="welcome-panel">
			<div class="tista_crud_message content warning-box alert" style="display:none;">	
				<span class="spinner is-active"></span>	
				<span>
				<i class="fa fa-exclamation"></i> &nbsp; &nbsp;<?php esc_html_e( 'Proccessing', 'tista-crud' ); ?>
				</span> <a class="mboxes_close" href="#"><i class="fa fa-times"></i></a> 				
			</div>
			<div class="tista_crud_message success success-box alert" style="display:none;">				
				<span><i class="fa fa-thumbs-o-up"></i> &nbsp; &nbsp;
			 <?php printf( esc_attr__( 'Thank you %s for contact! Your email was successfully sent!', 'tista-crud' ), '<strong>' . esc_attr( $tista_contact->name ) . '</strong>' ); ?>
			 </span> <a class="mboxes_close" href="#"><i class="fa fa-times"></i></a> 
			</div>
		
			<div class="smart-forms bmargin" >			
				<form id="tista_cruds" class="tista_crud_content" enctype="multipart/form-data" method="post">
					<div>
						<div class="section">
						  <label class="field prepend-icon">
							<input type="text" name="name" id="name" class="gui-input" placeholder="Enter name Required">
							<span class="field-icon"><i class="fa fa-user"></i></span> </label>
						</div>            
					   <div class="section">
						  <label class="field prepend-icon">
							<input type="text" name="birthday" id="birthday" class="gui-input" placeholder="Enter birthday Required">
							<span class="field-icon"><i class="fa fa-envelope"></i></span> </label>
						</div>                
						
						<div class="section colm colm6">
						  <label class="field prepend-icon">
							<input type="tel" name="mob" id="mob" class="gui-input" placeholder="Enter Mobile Required">
							<span class="field-icon"><i class="fa fa-phone-square"></i></span> </label>
						</div>            
					   <div class="section">
						  <label class="field prepend-icon">
							<input type="text" name="religion" id="religion" class="gui-input" placeholder="Enter religion Required">
							<span class="field-icon"><i class="fa fa-lightbulb-o"></i></span> </label>
						</div>       
						<div class="section">
						  <label class="field prepend-icon">
							<textarea class="gui-textarea" id="msg" name="msg" placeholder="Enter message Required"></textarea>
							<span class="field-icon"><i class="fa fa-comments"></i></span> <span class="input-hint"> <strong><?php esc_html_e( 'Hint', 'tista' ); ?>: </strong><?php esc_html_e( 'Please enter between 80 - 300 characters.', 'tista' ); ?></span> </label>
						</div>                
						<div class="result"></div>                 
					  </div> 
				
					  <div class="form-footer">
						<input type="submit" class="button btn-primary yellow-green" value="<?php esc_attr_e( 'Submit', 'tista-crud' ); ?>" name="submit" />
						<button type="reset" class="button">  <?php esc_html_e( 'Reset', 'tista-crud' ); ?></button>
					  </div>
						<?php wp_nonce_field('tista_crud', 'tista_crud_nonce'); ?>
				</form>
			</div>
		</div>
	</div>
<script type="text/javascript">
		jQuery(document).ready(function() {
				jQuery('#tista_cruds').on('submit', function() {
								jQuery("html, body").animate({
									scrollTop: 0
								}, {
									duration: 300
								});
								jQuery('.tista_crud_content').slideUp(null, function(){
									jQuery('.tista_crud_message.content').slideDown();
								});
								
								// Proccessing
								jQuery.ajax({
									type: 'POST',
									url: '<?php echo admin_url('admin-ajax.php'); ?>',
									data: jQuery(this).serialize()+'&action=tista_crud_do_ajax',
									success: function(){
										jQuery('.tista_crud_message.content').slideUp();
										jQuery('.tista_crud_message.success').slideDown();

									}
								});
								return false;
				});
		});
</script>
<script type="text/javascript">
		jQuery(document).ready(function() {
			$(".mboxes_close").on("click",function(){
				$(this).closest(".alert").fadeOut();
					return false;
				});
		});
</script>
