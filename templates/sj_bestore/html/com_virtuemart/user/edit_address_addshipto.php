<?php
	defined('_JEXEC') or die('Restricted access');
?>
<div class="row">
	<div class="form-group col-md-6">
		<label class="form-label"
			   for="fullname_invoice"><?php echo '<span class="userfields_info">' . vmText ::_('COM_VIRTUEMART_USER_FORM_SHIPTO_LBL') . '</span>'; ?></label>
		<?php echo $this -> lists['shipTo']; ?>
	</div>
</div>

