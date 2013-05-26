{import_js_file url="http://maps.googleapis.com/maps/api/js?key=`$cfg->google_key`&sensor=false"}
    
{import_js_block}
	var zoom = {$cfg->googlemap_default_zoom|default:"12"};
    
    var map; 
    var infoWindow;
    var markersArray = [];

	var gmap_width = {$cfg->googlemap_gx|default:250}
	var gmap_height = {$cfg->googlemap_gy|default:80}
	
    {if ($googleMapX && $googleMapY)}
		var pointTox={$googleMapX};
		var pointToy={$googleMapY};
	{else}
		var pointTox={$cfg->googlemap_defx|default:33.34433};
		var pointToy={$cfg->googlemap_defy|default:22.23223};
	{/if}
    
    {literal}
    function load_gmaps() {
        var myOptions = {
          center: new google.maps.LatLng(pointTox, pointToy),
          zoom: zoom,
          mapTypeId: google.maps.MapTypeId.{/literal}{$cfg->googlemap_maptype}{literal}
        };
        map = new google.maps.Map(document.getElementById("map_canvas"),
                    myOptions);
        infoWindow = new google.maps.InfoWindow();        
    }
    var gmap_selectposition=function ()
    {
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(pointTox, pointToy),
          map: map,
          draggable:true,
          title:"{/literal}{'COM_RBIDS_CURRENT_POSITION'|translate}{literal}"
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
          });
        
    }
	function submit_gmap_coords()
	{
		var window = this.opener;
		var xcoord = window.document.getElementById("googleX");
		var ycoord = window.document.getElementById("googleY");
                var latitude = document.getElementById("gmap_posx").value;
		xcoord.value = latitude;
                var longitude = document.getElementById("gmap_posy").value;
		ycoord.value = longitude;
                window.getContractorsCountByCat(latitude, longitude);
                window.getContractorsCountAllCat(latitude, longitude);
		this.close();
	}
        
        
    var gmap_showmap=function ()
    {
        var marker = new google.maps.Marker({
          position: new google.maps.LatLng(pointTox, pointToy),
          map: map,
          draggable:false,
          title:"{/literal}{'COM_RBIDS_AUCTION_POSITION'|translate}{literal}"
        });
        markersArray.push(marker);
    }    
    var gmap_refreshauctions=function()
    {
        $("wait_loader").setStyle('display','visible');
   	    limit = $("ads_limitbox").value;
       	cat   = $("category").value;
        searchUrl = 'index.php?option=com_rbids&controller=maps&task=showBookmarks&limit='+limit+'&cat='+cat;
        gmap_clearmarkers();
        new Request({
            method: 'GET', 
            url: searchUrl,
            onSuccess: function(data) {
                gmap_putmarkers(data);
            }
        }).send();   
    }
    var gmap_refreshauctions_search=function()
    {
        $("wait_loader").setStyle('display','visible');
        address = $('addressInput').value;

        $('search_sidebar').set('html','');
        gmap_clearmarkers();
        
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': address },function(results, status){
            if (! results) {
                $('search_sidebar').set('html',language["bid_noresults"]);
            }else
                if (status == google.maps.GeocoderStatus.OK) {
        		radius = $('radiusSelect').value;
                for (var i = 0; i < results.length; i++) {
                    center=results[i].geometry.location;
                    if (i==0) map.panTo(center);
                    var circle=new google.maps.Circle({
                        center:center,
                        radius:radius*1000,
                        map:map,
                        fillOpacity:0.4,
                        editable:false   
                    });
                    markersArray.push(circle);
                    searchUrl = 'index.php?option=com_rbids&controller=maps&task=searchBookmarks&lat=' + center.lat() + '&lng=' + center.lng() + '&radius=' + radius;
                    new Request({
                        method: 'GET', 
                        url: searchUrl,
                        onSuccess: function(data) {
                            gmap_putmarkers(data);
                            gmap_putsidebar(data);
                        }
                    }).send();   
                }             
            }else{
                $('search_sidebar').set('html',language["bid_noresults"]);
            }
        });
        
        
    }
    function gmap_putmarkers(xmldata)
    {
        var xml = parseXml(xmldata);
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
            var marker = new google.maps.Marker({
              position: new google.maps.LatLng(parseFloat(markers[i].getAttribute('lat')), parseFloat(markers[i].getAttribute('lng'))),
              map: map,
              draggable:false,
              title:markers[i].getAttribute('name'),
              info:markers[i].getAttribute('info')
            });
            google.maps.event.addListener(marker,'click', function () {
               infoWindow.setContent(this.info);
               infoWindow.setPosition(this.position);
               infoWindow.open(map);
            });
            markersArray.push(marker);
        }
    }
    function gmap_putsidebar(xmldata)
    {
        var xml = parseXml(xmldata);
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
            var div = new Element('div', {class: 'sidebar_element'});
            div.set('html','<b>' + (i+1)+ '.</b>&nbsp;' +
                     markers[i].getAttribute('info'));
            div.position=new google.maps.LatLng(parseFloat(markers[i].getAttribute('lat')), parseFloat(markers[i].getAttribute('lng')));
            div.addEvent('click',function(el){
               infoWindow.setContent(this.innerHTML);
               infoWindow.setPosition(this.position);
               infoWindow.open(map);
            })
            div.inject($('search_sidebar'));
        }       
    }
    function gmap_clearmarkers()
    {
      if (markersArray) 
        for (var i = 0; i < markersArray.length; i++ ) {
          markersArray[i].setMap(null);
        }
      markersArray=[];
        
    }
    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }
    {/literal}    
{/import_js_block}

