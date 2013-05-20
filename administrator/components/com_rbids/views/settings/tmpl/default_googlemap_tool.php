<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
    $doc=JFactory::getDocument();
    $doc->addScript("http://maps.googleapis.com/maps/api/js?key={$this->cfg->google_key}&sensor=false");
    $doc->addScriptDeclaration("
        window.addEvent('domready', function(){load_gmaps(); } ); 
        var map; 
    	var pointTox=this.opener.document.getElementById('googlemap_defx').value;
    	var pointToy=this.opener.document.getElementById('googlemap_defy').value;
    	var zoom=parseInt(this.opener.document.getElementById('googlemap_default_zoom').value);
        function load_gmaps() {
            var myOptions = {
              center: new google.maps.LatLng(pointTox, pointToy),
              zoom: zoom,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById('map_canvas'),
                        myOptions);
            var marker = new google.maps.Marker({
              position: new google.maps.LatLng(pointTox, pointToy),
              map: map,
              draggable:true,
              title:'Current Position'
            });
            document.getElementById('gmap_posx').value=marker.getPosition().lat();
            document.getElementById('gmap_posy').value=marker.getPosition().lng();
            document.getElementById('txt_zoom').value=zoom;
            google.maps.event.addListener(marker, 'position_changed', function() {
                document.getElementById('gmap_posx').value=marker.getPosition().lat();
                document.getElementById('gmap_posy').value=marker.getPosition().lng();
              });
            google.maps.event.addListener(map, 'click', function(mouseEvent) {
                    marker.setPosition(mouseEvent.latLng);
              });
            google.maps.event.addListener(map, 'zoom_changed', function() {
                    document.getElementById('txt_zoom').value=map.getZoom();
              });
            
              
        }
    	function submit_gmap_coords()
    	{
    		var xcoord = this.opener.document.getElementById('googlemap_defx');
    		var ycoord = this.opener.document.getElementById('googlemap_defy');
            var zoomi = this.opener.document.getElementById('googlemap_default_zoom')
    		xcoord.value = document.getElementById('gmap_posx').value;
    		ycoord.value = document.getElementById('gmap_posy').value;
            zoomi.value = document.getElementById('txt_zoom').value
    		this.close();
    	}
    
    ");
?>
<h3 style="color:#FAA000; text-decoration:underline;"><?php echo "Google Map";?></h3>
<div>
    <?php
    if (!$this->cfg->google_key) { 
        echo JText::_("COM_RBIDS_NO_API_KEY_DEFINED_SAVE_THE_KEY_FIRST");
    }else{
        ?>
        <div id="map_canvas" style="width: 500px; height: 300px"></div>
        <?php
    }?>
    
</div>

<div style="text-align:center;width: 500px; ">
	<input type="text" readonly="readonly" id="gmap_posx" name="gmap_posx" value=""/>
	<input type="text" readonly="readonly" id="gmap_posy" name="gmap_posy" value=""/>
	<br />
    <h3><a href="#" style="color:#FAA000; font-weight:bold;" onclick="submit_gmap_coords()"><?php echo JText::_("COM_RBIDS_SELECT_THIS_COORDINATES_AND_ZOOM_LEVEL");?></a></h3>
    <?php echo JText::_("COM_RBIDS_ZOOM_LEVEL"),": ";?><input type="text" readonly="readonly" id="txt_zoom" name="txt_zoom" value=""/>
</div>
