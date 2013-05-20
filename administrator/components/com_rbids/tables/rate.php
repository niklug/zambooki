<?php
/**------------------------------------------------------------------------
com_rbids - Reverse Auction Factory 3.0.0
------------------------------------------------------------------------
 * @author TheFactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thefactory.ro
 * Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
 * @build: 01/04/2012
 * @package: RBids
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class TableRate extends JTable
{
    
      var $id;
      var $voter;
      var $user_rated;
      var $rating;
      var $modified;
      var $message;
      var $auction_id;
      var $rate_type;
      var $rate_ip;

	function __construct(&$db)
	{
		parent::__construct( '#__rbid_rate', 'id', $db );
	}
 
}

?>
