<?php

	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');

	jimport('joomla.application.controller');
	class JRbidsAdminControllerMaps extends JController
	{
		/*public $name = "rbids";*/

		public function __construct()
		{
			parent::__construct();
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

			$db = &JFactory::getDBO();


			$where = array();
			$where[] = " `a`.`close_by_admin` = 0 ";
			$where[] = " a.close_offer = 0 ";
			$where[] = " a.published = 1 ";
			$where[] = " a.googlex != '' ";
			$where[] = " a.googley != '' ";

			if ($cat)
				$where[] = " a.cat = '{$cat}' ";


			//To search by kilometers instead of miles, replace 3959 with 6371.
			$distance_setting = 3959;

			if ($cfg->googlemap_distance == 1)
				$distance_setting = 6371;


			// Search the rows in the markers table
			$db->setQuery("SELECT a.id, a.shortdescription, a.title as name, c.catname, googlex as lat, googley as lng ,
			( $distance_setting * acos( cos( radians('$center_lat') ) * cos( radians( googlex ) ) * cos( radians( googley ) - radians('$center_lng') ) + sin( radians('$center_lat') ) * sin( radians( googlex ) ) ) ) AS distance
		  FROM #__rbid_auctions as a
		  LEFT JOIN `#__rbid_categories` c ON a.cat = c.id
		WHERE " .
				implode("AND", $where)
				. "
		HAVING distance < '$radius' ORDER BY distance
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

			$db = &JFactory::getDBO();

			$where = array();
			$where[] = " `a`.`close_by_admin` = 0 ";
			$where[] = " a.close_offer = 0 ";
			$where[] = " a.published = 1 ";
			$where[] = " a.googlex != '' ";
			$where[] = " a.googley != '' ";

			if ($cat != 0)
				$where[] = " a.cat = '{$cat}' ";

			// Search the rows in the markers table
			$db->setQuery("SELECT a.id, a.shortdescription, a.title as name, c.catname, googlex as lat, googley as lng
		                                FROM #__rbid_auctions as a
		                                LEFT JOIN `#__rbid_categories` c ON a.cat = c.id
		                                WHERE
						" .
				implode("AND", $where)
				. "
		$limit_sql ");
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


		public function googlemap_tool()
		{
			$cfg =& JTheFactoryHelper::getConfig();

			$view = $this->getView('maps', 'html');

			$view->assign("cfg", $cfg);
			// Default coordinates
			$view->assign("googleMapX", $cfg->googlemap_defx);
			$view->assign("googleMapY", $cfg->googlemap_defy);
			$view->display();
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
	}
