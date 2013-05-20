<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');
	jimport('joomla.application.component.view');

	class JRBidsAdminViewMaps extends JView
	{
		public function display($tpl = null)
		{
			JHTML::_("behavior.mootools");

			$doc = & JFactory::getDocument();
			if ($this->cfg->google_key != "") {
				$doc->addScript("http://maps.googleapis.com/maps/api/js?key=" . $this->cfg->google_key . "&sensor=false");
				$doc->addScriptDeclaration("

				var zoom = " . $this->cfg->googlemap_default_zoom . ";
				var map;
				var infoWindow;
				var markersArray = [];

				var gmap_width = " . $this->cfg->googlemap_gx . ";
				var gmap_height = " . $this->cfg->googlemap_gy . ";

				var pointTox = " . $this->cfg->googlemap_defx . ";
				var pointToy = " . $this->cfg->googlemap_defy . ";

				function load_gmaps() {
					var myOptions = {
						center: new google.maps.LatLng(pointTox, pointToy),
						zoom: zoom,
						mapTypeId: google.maps.MapTypeId." . $this->cfg->googlemap_maptype . "
					};
					map = new google.maps.Map(document.getElementById('map_canvas'),
						myOptions);
					infoWindow = new google.maps.InfoWindow();
				}



				var gmap_selectposition = function ()
				{
					var marker = new google.maps.Marker({
						position: new google.maps.LatLng(pointTox, pointToy),
						map: map,
						draggable:true,
						title:'" . JText::_('COM_RBIDS_CURRENT_POSITION') . "'
					});
					markersArray.push(marker);

					document.getElementById('gmap_posx').value=marker.getPosition().lat();
					document.getElementById('gmap_posy').value=marker.getPosition().lng();

					google.maps.event.addListener(marker, 'position_changed', function() {
						document.getElementById('gmap_posx').value=marker.getPosition().lat();
						document.getElementById('gmap_posy').value=marker.getPosition().lng();
					});
					google.maps.event.addListener(map, 'click', function(mouseEvent) {
						marker.setPosition(mouseEvent.latLng);
						console.log(mouseEvent.latLng);
					});

				}



				function submit_gmap_coords()
				{
					var window = this.opener;
					var xcoord = window.document.getElementById('googlemap_defx');
					var ycoord = window.document.getElementById('googlemap_defy');

					// Coord from map window
					xcoord.value = document.getElementById('gmap_posx').value;
					ycoord.value = document.getElementById('gmap_posy').value;
					this.close();
				}
		");

			}

			parent::display($tpl);
		}

	}

