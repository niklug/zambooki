<?php
/**
 * @category	Helper
 * @package		JomSocial
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

require_once( JPATH_ROOT.'/components/com_community/helpers/validate.php' );

class CPhone{
     /**
        * Deprecated since 1.8
        */
        function cValidatePhone($phone)
        {	
                return CValidateHelper::phone( $phone );
        }
   
}
