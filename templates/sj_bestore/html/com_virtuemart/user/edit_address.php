<?php
	// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	
	
	vmJsApi ::css('vmpanels');
	
	$this -> cart = VirtueMartCart ::getCart();
	$url = 0;
	if ($this -> cart -> _fromCart or $this -> cart -> getInCheckOut()) {
		$rview = 'cart';
	} else {
		$rview = 'user';
	}
	
	function renderControlButtons($view, $rview)
	{
		?>
		<div class="form-group mt-3 text-center">
			<?php
				if (VmConfig ::get('oncheckout_show_register', 1) && $view -> userDetails -> JUser -> id == 0 && !VmConfig ::get('oncheckout_only_registered', 0) && $view -> address_type == 'BT' and $rview == 'cart') {
					echo '<div class="reg_text">' . vmText ::sprintf('COM_VIRTUEMART_ONCHECKOUT_DEFAULT_TEXT_REGISTER', vmText ::_('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'), vmText ::_('COM_VIRTUEMART_CHECKOUT_AS_GUEST')) . '</div>';
				} else {
					//echo vmText::_('COM_VIRTUEMART_REGISTER_ACCOUNT');
				}
				if (VmConfig ::get('oncheckout_show_register', 1) && $view -> userDetails -> JUser -> id == 0 && $view -> address_type == 'BT' and $rview == 'cart') {
					?>
					<button name="register" class="btn btn-dark" type="submit"
							onclick="javascript:return myValidator(userForm,true);"
							title="<?php echo vmText ::_('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?>"><?php echo vmText ::_('COM_VIRTUEMART_REGISTER_AND_CHECKOUT'); ?></button>
					<?php if (!VmConfig ::get('oncheckout_only_registered', 0)) { ?>
						<button name="save" class="btn btn-dark"
								title="<?php echo vmText ::_('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?>" type="submit"
								onclick="javascript:return myValidator(userForm, false);"><?php echo vmText ::_('COM_VIRTUEMART_CHECKOUT_AS_GUEST'); ?></button>
					<?php } ?>
					<button class="default" type="reset"
							onclick="window.location.href='<?php echo JRoute ::_('index.php?option=com_virtuemart&view=' . $rview . '&task=cancel'); ?>'"><?php echo vmText ::_('COM_VIRTUEMART_CANCEL'); ?></button>
					<?php
				} else {
					?>

					<button class="btn btn-dark" type="submit"
							onclick="javascript:return myValidator(userForm,true);"><?php echo vmText ::_('COM_VIRTUEMART_SAVE'); ?></button>
					<button class="btn btn-outline-dark" type="reset"
							onclick="window.location.href='<?php echo JRoute ::_('index.php?option=com_virtuemart&view=' . $rview . '&task=cancel'); ?>'"><?php echo vmText ::_('COM_VIRTUEMART_CANCEL'); ?></button>
				<?php } ?>
		</div>
		<?php
	}

?>
<h1><?php echo $this -> page_title ?></h1>
<?php
	$task = '';
	if ($this -> cart -> getInCheckOut()) {
		$task = '&task=checkout';
	}
	$url = 'index.php?option=com_virtuemart&view=' . $rview . $task;

?>
<?php
	$document = JFactory ::getDocument();
	$document -> addStyleSheet('templates/sj_bestore/css/modules/nouislider.css');
	$document -> addStyleSheet('templates/sj_bestore/css/modules/css.css');
	$document -> addStyleSheet('templates/sj_bestore/css/modules/owl.carousel.css');
	$document -> addStyleSheet('templates/sj_bestore/css/modules/ekko-lightbox.css');
	$document -> addStyleSheet('templates/sj_bestore/css/modules/style.default.9d9629d7.css');
	$document -> addStyleSheet('htemplates/sj_bestore/css/modules/custom.0a822280.css');
?>
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-lg-12">
			<div class="block">
				<div class="block-body">
					<hr>
					<form method="post" id="userForm" name="userForm" class="form-validate"
						  action="<?php echo JRoute ::_('index.php?option=com_virtuemart&view=user', $this -> useXHTML, $this -> useSSL) ?>">
						<?php
							if (count($this -> userFields['functions']) > 0) {
								echo '<script language="javascript">' . "\n";
								echo join("\n", $this -> userFields['functions']);
								echo '</script>' . "\n";
							}
							
							echo $this -> loadTemplate('userfields');
							
							// captcha addition
							if (VmConfig ::get('reg_captcha') && JFactory ::getUser() -> guest == 1) {
								?>
								<fieldset id="recaptcha_wrapper">
								<?php if (!VmConfig ::get('oncheckout_only_registered')) { ?>
									<span class="userfields_info"><?php echo vmText ::_('COM_VIRTUEMART_USER_FORM_CAPTCHA'); ?></span>
								<?php } ?>
								<?php echo $this -> captcha; ?>
								</fieldset><?php }
							// end of captcha addition
							
							renderControlButtons($this, $rview);
							
							if ($this -> userDetails -> virtuemart_user_id) {
								echo $this -> loadTemplate('addshipto');
							}
						?>

						<input type="hidden" name="option" value="com_virtuemart"/>
						<input type="hidden" name="view" value="user"/>
						<input type="hidden" name="controller" value="user"/>
						<input type="hidden" name="task" value="saveUser"/>
						<input type="hidden" name="layout" value="<?php echo $this -> getLayout(); ?>"/>
						<input type="hidden" name="address_type" value="<?php echo $this -> address_type; ?>"/>
						
						<?php if (!empty($this -> virtuemart_userinfo_id)) {
							echo '<input type="hidden" name="shipto_virtuemart_userinfo_id" value="' . (int)$this -> virtuemart_userinfo_id . '" />';
						}
							echo JHtml ::_('form.token');
						?>
					</form>
				</div>
			</div>


		</div>
	</div>
</div>


