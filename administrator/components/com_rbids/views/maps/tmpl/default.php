<?php

	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');
	$doc = JFactory::getDocument();
	$doc->addScriptDeclaration("
					window.addEvent('domready', function(){
						load_gmaps();
						gmap_selectposition.delay(500);
					});
				");
?>
<h3 style = "color:#FAA000; text-decoration:underline;"><?php echo JText::_("COM_RBIDS_GOOGLEMAPS"); ?></h3>
<div>

	<?php if ($this->cfg->google_key != ""): ?>

    <div id = "map_canvas" style = "width: 500px; height: 300px"></div>

	<?php else: ?>
	<?php echo JText::_('COM_RBIDS_NO_GOOGLE_MAP_DEFINED'); ?>
	<?php endif; ?>
</div>
<div style = "text-align:center;width: 500px; ">
    <input type = "text" readonly = "readonly" id = "gmap_posx" value = "<?php echo $this->googleMapX; ?>" />
    <input type = "text" readonly = "readonly" id = "gmap_posy" value = "<?php echo $this->googleMapY; ?>" />
    <br />

    <h3><a href = "#" style = "color:#FAA000; font-weight:bold;" onclick = "submit_gmap_coords()"><?php echo JText::_("COM_RBIDS_SELECT"); ?></a></h3>
</div>
