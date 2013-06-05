<?php
/**
 * @package		JomSocial
 * @subpackage 	Template
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 *
 * @param	author		string
 * @param	$results	An array of user objects for the search result
 */
defined('_JEXEC') or die();

$for_country = JRequest::getVar('for_country');
$for_state = JRequest::getVar('for_state');
$for_city = JRequest::getVar('for_city');

$country_input = addcslashes(JRequest::getVar('country_input'),"'");
$state_input = addcslashes(JRequest::getVar('state_input'),"'");
$city_input = addcslashes(JRequest::getVar('city_input'),"'");
?>

<div class="cLayout cSearch">
	<form name="jsform-search" method="get" action="" class="cSearch-Form cForm">

		<label for="q" class="cSearch-Input clearfix">
			<input type="text" id="q" class="cSearch-Text cFloat-L" name="q" value="<?php echo $this->escape( $query ); ?>" placeholder="<?php echo JText::_('COM_COMMUNITY_SEARCH_PEOPLE_PLACEHOLDER');?>" />
			<input type="submit" value="<?php echo JText::_('COM_COMMUNITY_SEARCH_BUTTON_TEMP');?>" class="cButton cButton-Blue cFloat-R" name="Search" />
		</label>
                
                <div style=" height: 30px;  position: relative;  width: 100%;clear: both;">
                    <label  style="float: left; width: 80px; margin-left: 0;  padding-left: 0;" class="label-checkbox" >
                        <strong>Search in:</strong>
                    </label>
                    
                    <label  style="float: left; width: 60px; " class="label-checkbox" >
                            <input type="checkbox" name="for_country" id="for_country" value="1" onclick="showLocation(this.id, 'country')" <?php echo ($for_country) ? ' checked="checked"' : ''; ?>>
                            <?php echo JText::_('Country'); ?>
                    </label>

                    <label  style="float: left; width: 50px;" class="label-checkbox" >
                            <input type="checkbox" name="for_state" id="for_state" value="1"  onclick="showLocation(this.id, 'state')" <?php echo ($for_state) ? ' checked="checked"' : ''; ?> >
                            <?php echo JText::_('State'); ?>

                    </label>

                    <label  style="float: left; width: 80px;" class="label-checkbox" >
                             <input type="checkbox" name="for_city" id="for_city" value="1"  onclick="showLocation(this.id, 'city')" <?php echo ($for_city) ? ' checked="checked"' : ''; ?> >
                            <?php echo JText::_('City / Town'); ?>
                    </label>
                    
                    <input style="display:none" type="text" name="country_input" id="country_input" value="" placeholder="Country">
                    <input style="display:none"  type="text" name="state_input" id="state_input" value="" placeholder="State">
                    <input style="display:none"  type="text" name="city_input" id="city_input" value="" placeholder="City">
                </div>

                
		<div  style="position: relative;  width: 310px;clear: both;"  class="cSearch-Helper">
			<label  style="float: left;" class="label-checkbox" >
				<input type="checkbox" name="avatar" id="avatar" value="1" class="input checkbox "<?php echo ($avatarOnly) ? ' checked="checked"' : ''; ?>>
				<?php echo JText::_('COM_COMMUNITY_EVENTS_AVATAR_ONLY'); ?>
			</label>
                    <?php
                    $BBB_Profile = JRequest::getVar('BBB_Profile');
                    
                    ?>
                    	<label style="float: right;" class="label-checkbox">
				<input type="checkbox" name="BBB_Profile" id="BBB_Profile" value="1" class="input checkbox "<?php echo ($BBB_Profile) ? ' checked="checked"' : ''; ?>>
				<?php echo JText::_('BBB Profile'); ?>
			</label>
		</div>

		<input type="hidden" name="option" value="com_community" />
		<input type="hidden" name="view" value="search" />
		<input type="hidden" name="Itemid" value="<?php echo CRoute::_getDefaultItemid();?>">
	</form>

	<?php
	if( $results )
	{
	?>
   
	<div class="cSearch-Result">
		<p>
			<b><?php echo JText::_('COM_COMMUNITY_SEARCH_RESULTS');?></b>
		</p>
		<?php echo $resultHTML;?>
	</div>
   
	<?php
	}
	else if( empty( $results ) && !empty( $query ) )
	{
	?>
	<div class="cAlert cEmpty">
		<?php echo JText::_('COM_COMMUNITY_NO_RESULT_FROM_SEARCH');?>
	</div>
	<?php
	}
	?>

	
</div>
<script type="text/javascript">
    var strip, strcountry, strcity, strregion, strlatitude, strlongitude, strtimezone
    function GetUserInfo(data) {
        strip = data.host; strcountry = data.countryName; strcity = data.city;
        strregion = data.region; strlatitude = data.latitude; strlongitude = data.longitude;
        strtimezone = data.timezone;
    }

</script>
<script type="text/javascript" src="http://smart-ip.net/geoip-json?callback=GetUserInfo"></script>
<script type="text/javascript">
   
    joms.jQuery(document).ready( function() {
        joms.jQuery("#city_input").val(strcity);
        joms.jQuery("#state_input").val(strregion);
        joms.jQuery("#country_input").val(strcountry);
        
        var for_country = '<?php echo $for_country ?>';
        var for_state = '<?php echo $for_state ?>';
        var for_city = '<?php echo $for_city ?>';
        
        
        var country_input = '<?php echo $country_input ?>';
        var state_input = '<?php echo $state_input ?>';
        var city_input = '<?php echo $city_input ?>';
        
        var country_checked = joms.jQuery('#for_country').is(':checked');
        var state_checked = joms.jQuery('#for_state').is(':checked');
        var city_checked = joms.jQuery('#for_city').is(':checked');
        
        if(for_country || country_checked) {
            joms.jQuery("#country_input").show();
            joms.jQuery("#country_input").val(country_input);
        }
        if(for_state || state_checked) {
            joms.jQuery("#state_input").show();
            joms.jQuery("#state_input").val(state_input);
        }
        if(for_city || city_checked) {
            joms.jQuery("#city_input").show();
            joms.jQuery("#city_input").val(city_input);
        }
        
        
    });
    
    function showLocation(id, kind) {
        var checked = joms.jQuery('#' + id).is(':checked');
        if(checked) {
            joms.jQuery("#" + kind + "_input").show();
        } else {
            joms.jQuery("#" + kind + "_input").hide();
        }

    }

</script>

        