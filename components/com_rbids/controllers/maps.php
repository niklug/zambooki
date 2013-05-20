<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : RBids
	 * @subpackage: Googlemaps
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class MapsController extends JController
	{
		var $_name = 'rbids';
		var $name = 'rbids';

		/**
		 * googlemaps
		 */
		public function googlemaps()
		{
			$search = JRequest::getInt('search', 0);
			$cfg =& JTheFactoryHelper::getConfig();

			$view = $this->getView('maps', 'html');
			$view->assign('search', $search);
                        
                        //get user from com_community
                        $CUser = CFactory::getUser();
                        $CProfileModel = CFactory::getModel('profile');
                        $CProfile       = $CProfileModel->getEditableProfile($CUser->id, $CUser->getProfileType() );
                        $CContactInfo = $CProfile['fields']['Contact Information'];
                        $address = '';
                        $city = '';
                        $state = '';
                        $addressInput = '';
                        foreach($CContactInfo as $info){
                            if($info['fieldcode'] == 'FIELD_ADDRESS') $address = $info['value'];
                            if($info['fieldcode'] == 'FIELD_CITY')    $city = $info['value'];
                            if($info['fieldcode'] == 'FIELD_STATE')   $state = $info['value'];
                        }
                        
                        
                        if($city == '' && $state == ''){
                            $addressInput = $address;
                        }
                        else {
                            $addressInput .=($state != '') ? $state." state ": " ";
                            $addressInput .=($city != '') ? $city." city ": " ";
                        }
			$lists["category"] = JHtml::_('factorycategory.select', 'category',
				'onchange="gmap_refreshauctions();" style="width:350px;"', 0, false, false, true);

			if ($cfg->googlemap_unit_available != "") {
				$radius_units = explode(",", $cfg->googlemap_unit_available);
			} else
				$radius_units = array("10", "20", "50", "100");

			$lists["radius_units"] = $radius_units;
			$view->assign("lists", $lists);
			$view->assign("addressInput", $addressInput );

			if ($search)
				$tmpl = "maps/t_search.tpl";
			else
				$tmpl = "maps/t_listadds.tpl";
			$view->display($tmpl);
		}

		/**
		 * searchBookmarks
		 */
		public function searchBookmarks()
		{

			// Start XML file, create parent node
			$cfg =& JTheFactoryHelper::getConfig();
			$center_lat = JRequest::getFloat('lat', 0);
			$center_lng = JRequest::getFloat('lng', 0);
			$radius = JRequest::getFloat('radius', 0);
			$cat = JRequest::getInt('cat', 0);

			$dom = new DOMDocument("1.0");
			$node = $dom->createElement("markers");
			$parnode = $dom->appendChild($node);

			$db = & JFactory::getDBO();


			$where = array();
			$where[] = " `a`.`close_by_admin` = 0 ";
			$where[] = " `a`.`close_offer` = 0 ";
			$where[] = " `a`.`published` = 1 ";
			$where[] = " `a`.`end_date` > NOW() ";
			$where[] = " `a`.`googlex` != '' ";
			$where[] = " `a`.`googley` != '' ";

			if ($cat)
				$where[] = " `a`.`cat` = '{$cat}' ";


			//To search by kilometers instead of miles, replace 3959 with 6371.
			$distance_setting = 3959;
			if ($cfg->googlemap_distance == 1)
				$distance_setting = 6371;


			// Search the rows in the markers table
			$db->setQuery("SELECT `a`.`id`,
							 `a`.`shortdescription`,
							 `a`.`title` AS `name`,
							 `c`.`catname`,
							 `googlex` AS `lat`,
							 `googley` AS `lng`,
							 ( $distance_setting * acos( cos( radians('$center_lat') ) * cos( radians( googlex ) ) * cos( radians( googley ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( googlex ) ) ) ) AS `distance`
		                                   FROM `#__rbid_auctions` AS `a`
		                                   LEFT JOIN `#__rbid_categories` AS `c` ON `a`.`cat` = `c`.`id`
		                                   WHERE " . implode("AND", $where) . "
		                                   HAVING `distance` < '$radius'
		                                   ORDER BY `distance`
				");
			$rows = $db->loadObjectList();

			ob_end_clean();
			header("Content-type: text/xml");

			// Iterate through the rows, adding XML nodes for each
			foreach ($rows as $row) {
				$node = $dom->createElement("marker");
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("name", $row->name);
				$newnode->setAttribute("info", "<strong><a href='" . RBidsHelperRoute::getAuctionDetailRoute($row->id) . "'>" . $row->name . "</a></strong><br>" .
					$row->catname . "<br />" .
					$row->shortdescription);
				$newnode->setAttribute("lat", $row->lat);
				$newnode->setAttribute("lng", $row->lng);
				$newnode->setAttribute("link", RBidsHelperRoute::getAuctionDetailRoute($row->id));
				$newnode->setAttribute("distance", $row->distance);
			}

			echo $dom->saveXML();
			exit;
		}

		/**
		 * showBookmarks
		 */
		public function showBookmarks()
		{
			//Google maps
			$cat = JRequest::getInt('cat');
			$start = JRequest::getInt('limitstart');
			$limit = JRequest::getInt('limit', RBidsHelperTools::getItemsPerPage());
			if ($limit == 0) {
				$limit_sql = "";
			} else {
				$limit_sql = "LIMIT $start,$limit";
			}
			// Start XML file, create parent node
			$dom = new DOMDocument("1.0");
			$node = $dom->createElement("markers");
			$parnode = $dom->appendChild($node);

			$db = & JFactory::getDBO();

			$where = array();
			$where[] = " `a`.`close_by_admin` = 0 ";
			$where[] = " a.close_offer = 0 ";
			$where[] = " a.published = 1 ";
			$where[] = " a.googlex != '' ";
			$where[] = " a.googley != '' ";

			if ($cat != 0)
				$where[] = " a.cat = '{$cat}' ";

			// Search the rows in the markers table
			$db->setQuery("SELECT `a`.`id`,
							 `a`.`shortdescription`,
							 `a`.`title` AS `name`,
							 `c`.`catname`,
							 `googlex` AS `lat`,
							 `googley` AS `lng`
						   FROM `#__rbid_auctions` AS `a`
						   LEFT JOIN `#__rbid_categories` AS `c` ON `a`.`cat` = `c`.`id`
						   WHERE " . implode("AND", $where) . " $limit_sql
					");
			$rows = $db->loadObjectList();

			ob_end_clean();
			header("Content-type: text/xml");

			// Iterate through the rows, adding XML nodes for each
			foreach ($rows as $row) {
				$node = $dom->createElement("marker");
				$newnode = $parnode->appendChild($node);
				$newnode->setAttribute("name", $row->name);
				$newnode->setAttribute("info", "<strong><a href='" . RBidsHelperRoute::getAuctionDetailRoute($row->id) . "'>" . $row->name . "</a></strong><br>" .
					$row->catname . "<br />" .
					$row->shortdescription);
				$newnode->setAttribute("lat", $row->lat);
				$newnode->setAttribute("lng", $row->lng);
				$newnode->setAttribute("link", JRoute::_(RBidsHelperRoute::getAuctionDetailRoute($row->id)));
			}

			echo $dom->saveXML();
			exit;

		}

		/**
		 * googlemap
		 */
		public function googlemap()
		{
			$app = JFactory::getDocument();
			$app->addStyleDeclaration("
						/* Override 'beez_20'  #main style template */
						#main {
							min-height: 100px;
							padding: 0;
						}
			");
			$smarty = new RBidsSmarty();

			$x = JRequest::getFloat('x');
			$y = JRequest::getFloat('y');

			$view = $this->getView('maps', 'html');
			$view->assign("googleMapX", $x);
			$view->assign("googleMapY", $y);
			$view->display("t_googlemap.tpl");
		}

		/**
		 * googlemap_tool
		 */
		public function googlemap_tool()
		{
			$my =& JFactory::getUser();
			$user =& JTable::getInstance('users', 'Table');
			$user->load($my->id);

			$view = $this->getView('maps', 'html');

			$view->assign("googleMapX", $user->googleMaps_x);
			$view->assign("googleMapY", $user->googleMaps_y);
			$view->display("t_googlemap_tool.tpl");
		}

		/**
		 * saveGoogleCoords
		 */
		public function saveGoogleCoords()
		{
			$x = trim(JRequest::getVar('googleMaps_x'));
			$y = trim(JRequest::getVar('googleMaps_y'));
			$url = base64_decode(JRequest::getVar('return_url'));
			$my =& JFactory::getUser();

			$userprofile = RBidsHelperTools::getUserProfileObject();
			$userprofile->integrationObject->setGMapCoordinates($my->id, $x, $y);

			$db =& JFactory::getDbo();
			$this->setRedirect($url);
		}

	} // End Class
