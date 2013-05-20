<?php
/**
 * @category	Core
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// Disallow direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<fieldset class="adminform">
	<legend><?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MYBLOG_INTEGRATIONS' ); ?></legend>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td width="300" class="key">
					<span class="hasTip" title="<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MYBLOG_ENABLE_IN_PROFILE' ); ?>::<?php echo JText::_('COM_COMMUNITY_CONFIGURATION_MYBLOG_ENABLE_IN_PROFILE_TIPS'); ?>">
						<?php echo JText::_( 'COM_COMMUNITY_CONFIGURATION_MYBLOG_ENABLE_IN_PROFILE' ); ?>
					</span>
				</td>
				<td valign="top">
					<?php echo JHTML::_('select.booleanlist' , 'enablemyblogicon' , null , $this->config->get('enablemyblogicon') , JText::_('COM_COMMUNITY_YES_OPTION') , JText::_('COM_COMMUNITY_NO_OPTION') ); ?>
				</td>
			</tr>
		</tbody>
	</table>
</fieldset>