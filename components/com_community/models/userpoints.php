<?php
/**
 * @category	Model
 * @package		JomSocial
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT .'/components/com_community/models/models.php' );

/**
 *
 */ 
class CommunityModelUserPoints extends JCCModel
{

	public function getPointData($action)
	{
		$db	 = $this->getDBO();
		
		$query = 'SELECT * FROM '.$db->quoteName('#__community_userpoints');
		$query .= ' WHERE '.$db->quoteName('action_string').' = '.$db->Quote($action);
		$db->setQuery($query);
		
		$result	= $db->loadObject();
// 		$point	= 0;
// 		
// 		if(! empty($result))
// 		{
// 			$published	= $result->published;			
// 			$point		= $result->points;
// 			
// 			if ($published == '0')
// 				$point = 0;
// 								
// 		}
		
		return $result;
	}
}
