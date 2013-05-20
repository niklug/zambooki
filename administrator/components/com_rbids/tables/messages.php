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

class TableMessages extends JTable
{
  var $id;
  var $auction_id;
  var $userid1;
  var $userid2;
  var $parent_message;
  var $message;
  var $bid_id;
  var $modified;
  var $wasread;
  var $published;    

	function __construct(&$db)
	{
		parent::__construct( '#__rbid_messages', 'id', $db );
	}
}

?>
