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
 * @subpackage: CBPlugins
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class getrmymap extends cbTabHandler {

	function getrmymapTab() {
		$this->cbTabHandler();
	}

	function getDisplayTab($tab,$user,$ui){

		$LO = JFactory::getLanguage();
		$LO->load('com_rbids');
		
		$database = & JFactory::getDbo();
		$my = & JFactory::getUser();

		if(!file_exists(JPATH_ROOT.DS."components".DS."com_rbids".DS."rbids.php")){
			  return "<div>You must First install <a href='http://www.thefactory.ro/shop/joomla-components/auction-factory.html'> Reverse Auction Factory </a></div>";
		}

        global $_CB_framework;

        $js =
        "jQuery(document).ready(function(){
            jQuery('.tab').click(function(){
                if(jQuery('#cbtab".$tab->tabid."').css('display')=='block') {
                    google.maps.event.trigger(map, 'resize');
                }
            });
        });";
        $_CB_framework->outputCbJQuery($js);

		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."options.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."tools.php");
		require_once(JPATH_ROOT.DS."components".DS."com_rbids".DS."helpers".DS."route.php");

        $cfg=new RbidConfig();

		$cb_fields = array();

        $database->setQuery("SELECT `field`,`assoc_field` FROM `#__rbid_fields_assoc`");
        $cb_fields = $database->loadAssocList("field","assoc_field");
 
    	if( empty($cb_fields['googleMaps_x']) || empty($cb_fields['googleMaps_y']) )
		{
			$return = JText::_("COM_RBIDS_DEFINE_MAP_COORDINATE_FIELDS_IN_INTEGRATION_SETUP");
    		return $return;
    	}
    	 
    	$googleMaps = array();
    	$database->setQuery("SELECT {$cb_fields['googleMaps_x']},{$cb_fields['googleMaps_y']} FROM #__comprofiler WHERE user_id = '{$user->id}'");
		$googleMaps=$database->loadRow();
        $gmap_x=$googleMaps[0];
        $gmap_y=$googleMaps[1];
        if (!$gmap_x || !$gmap_y){
            if($my->id!=$user->id)
            {
                $return = "<div>";
                $return .= JText::_("COM_RBIDS_NO_MAP_FOUND");
                $return .= "</div>";
                return $return;
            }
            $gmap_x=floatval($cfg->googlemap_defx);
            $gmap_y=floatval($cfg->googlemap_defy);
        }
        $zoom=intval($cfg->googlemap_default_zoom);
        if($my->id==$user->id)
            $draggable="true";
        else
            $draggable="false";
        
        
        $doc=JFactory::getDocument();
        $doc->addScript("http://maps.googleapis.com/maps/api/js?key={$cfg->google_key}&sensor=false");
        $script="
            window.addEvent('domready', function(){load_gmaps(); } ); 
            var map; 
        	var pointTox=$gmap_x;
        	var pointToy=$gmap_y;
        	var zoom=$zoom;
            function load_gmaps() {
                var myOptions = {
                  center: new google.maps.LatLng(pointTox, pointToy),
                  zoom: zoom,
                  mapTypeId: google.maps.MapTypeId.$cfg->googlemap_maptype
                };
                map = new google.maps.Map(document.getElementById('map_canvas'),
                            myOptions);
                var marker = new google.maps.Marker({
                  position: new google.maps.LatLng(pointTox, pointToy),
                  map: map,
                  draggable:$draggable,
                  title:'".JText::_('COM_RBIDS_CURRENT_POSITION')."'
                });";
        if($my->id==$user->id)
            $script.="
                google.maps.event.addListener(marker, 'position_changed', function() {
                    document.getElementById('gmap_posx').value=marker.getPosition().lat();
                    document.getElementById('gmap_posy').value=marker.getPosition().lng();
                  });
                google.maps.event.addListener(map, 'click', function(mouseEvent) {
                        marker.setPosition(mouseEvent.latLng);
                  });";
        $script.="
            }
        ";
        $doc->addScriptDeclaration($script);
            	
        $return = "<div>";
    	$return .= "<div id=\"map_canvas\" style=\"width: {$cfg->googlemap_gx}px; height: {$cfg->googlemap_gy}px\"></div>";
        if($my->id==$user->id)
            $return .= "
                <form name='googlemapauctionForm' method='post' action='index.php'>
                    <input type='hidden' value='com_rbids' name='option'>
                    <input type='hidden' value='maps' name='controller'>
                    <input type='hidden' value='savegooglecoords' name='task'>
                    <input type='hidden' value='".base64_encode(JURI::getInstance()->toString())."' name='return_url'>
                     <div align='center'>
                        <br/>".JText::_('COM_RBIDS_COORDINATE_X').": <input class='inputbox' type='text' id='gmap_posx' name='googleMaps_x' value='".$gmap_x."' size='20' />
                        <br/>".JText::_('COM_RBIDS_COORDINATE_Y').": <input class=''inputbox' type=''text' id='gmap_posy' name=''googleMaps_y'' value='".$gmap_y."' size='20' />
                    </div>
                    <input type='submit' value='".JText::_('COM_RBIDS_SAVE_COORDINATES')."'>
                </form>
            ";
        $return .= "</div>";

		return $return;
	}
}
?>
