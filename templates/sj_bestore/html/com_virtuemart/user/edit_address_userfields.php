<?php
	
	/**
	 *
	 * Modify user form view, User info
	 *
	 * @package    VirtueMart
	 * @subpackage User
	 * @author Oscar van Eijk, Eugen Stranz, Max Milbers
	 * @link https://virtuemart.net
	 * @copyright Copyright (c) 2004 - 2019 VirtueMart Team. All rights reserved.
	 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
	 * VirtueMart is free software. This version may have been modified pursuant
	 * to the GNU General Public License, and as distributed it includes or
	 * is derivative of works licensed under the GNU General Public License or
	 * other free or open source software licenses.
	 * @version $Id: edit_address_userfields.php 10163 2019-10-09 07:09:10Z Milbo $
	 */
// Check to ensure this file is included in Joomla!
	defined('_JEXEC') or die('Restricted access');
	
	$closeDelimiter = false;
	$openTable = true;
	$hiddenFields = '';
	$i = 0;
	$tmp = false;
	foreach ($this -> userFields['fields'] as $k => $field) {
		if ($field['type'] == 'delimiter') {
			$tmp = $field;
			$pos = $k;
			$i++;
		}
		
		if ($i > 1) {
			$tmp = false;
			break;
		}
	}
	
	if ($tmp) {
		unset($this -> userFields['fields'][$pos]);
		array_unshift($this -> userFields['fields'], $tmp);
	}
	
	foreach ($this -> userFields['fields'] as $field) {
		if ($field['type'] == 'delimiter') {
			if ($closeDelimiter) {
				?>
				</div></div>
				<?php
				$closeDelimiter = false;
			} else if (!$openTable) {
				?>
				</div>
				<?php
			}
			
			if ($field['name'] == 'delimiter_userinfo') {
				if ($this -> getLayout() == 'edit') {
					echo $this -> loadTemplate('vmshopper');
				}
			} else {
				?>
				<div>
				<legend class="userfields_info">
					<?php echo $field['title'] ?>
				</legend>
				<?php
			}
			
			$closeDelimiter = true;
			$openTable = true;
			
		} elseif ($field['hidden'] == true) {
			$hiddenFields .= $field['formcode'] . "\n";
		} else {
			if ($openTable) {
				$openTable = false;
				?>
				<div class="block-body">
				
				<?php
			}
			
			$descr = empty($field['description']) ? $field['title'] : $field['description'];
			
			?>
			<div class="form-group col-md-6" title="<?php echo strip_tags($descr) ?>">
				<label class="form-label" class="<?php echo $field['name'] ?>" for="<?php echo $field['name'] ?>_field">
					<?php echo $field['title'] . ($field['required'] ? ' <span class="asterisk">*</span>' : '') ?>
				</label>
				<?php echo $field['formcode'] ?>
			</div>
			<?php
		}
	}
	
	if ($closeDelimiter) {
		?>
		</div></div>
		<?php
		$closeDelimiter = false;
	}
?>
	</div></div>
<?php // Output: Hidden Fields
	echo $hiddenFields
?>