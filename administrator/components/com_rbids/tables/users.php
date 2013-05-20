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

class TableUsers extends FactoryFieldsTbl
{
    //var $id;
    var $userid;
    var $name;
    var $surname;
    var $address;
    var $city;
    var $country;
    var $phone;
    var $modified;
    var $rating;
    var $paypalemail;
    var $AreasOfExpertise;
    var $Resume;
    var $YM;
    var $Hotmail;
    var $Skype;
    var $googleMaps_x;
    var $googleMaps_y;
    var $verified;
    var $powerseller;
    function __construct(&$db)
    {
    	parent::__construct( '#__rbid_users', 'userid', $db );
    }

}

?>
