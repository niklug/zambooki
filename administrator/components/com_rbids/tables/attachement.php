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

class TableAttachement extends JTable {

    var $id=null;
    var $auctionId=null;
    var $userId=null;
    var $fileName=null;
    var $fileExt=null;
    var $fileType=null;

    function __construct(&$db) {
        parent::__construct('#__rbid_attachments', 'id', $db);
    }
}
