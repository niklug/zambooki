{* Include Validation script *}
{include file='js/t_javascript_language.tpl'}
{set_css}
{include file='elements/userprofile/t_profile_tabs.tpl'}
<div class = "rbids_userprofile">
    <form action = "{$ROOT_HOST}index.php?option={$option}" method = "post" name = "rAuctionForm" id = "rAuctionForm" enctype = "multipart/form-data">
        <input type = "hidden" name = "Itemid" value = "{$Itemid}" />
        <input type = "hidden" name = "task" value = "saveUserDetails" />
        <input type = "hidden" name = "controller" value = "user" />

        <table id = "table_profile_box" width = "100%" cellpadding = "0" cellspacing = "0">
            <tr>
                <td class = "auction_dbk" align = "left">
                    <label class = "rbid_user_label">{"COM_RBIDS_NAME"|translate}:</label>
                </td>
                <td class = "auction_dbk">
                    <input class = "inputbox" type = "text" name = "name" value = "{$user->name}" size = "40" />
                </td>
            </tr>
{*            <tr>
                <td class = "auction_dbk_c" align = "left">
                    <label class = "rbid_user_label">{"COM_RBIDS_SURNAME"|translate}:</label>
                </td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "surname" value = "{$user->surname}" size = "40" />
                </td>
            </tr>*}
            <tr {if !$cfg->allow_paypal}class = "bottom_separator"{/if}>
                <td class = "auction_dbk_c" align = "left">
                    <label class = "rbid_user_label">{"COM_RBIDS_PHONE"|translate}:</label>
                </td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "phone" value = "{$user->phone}" size = "40" />
                </td>
            </tr>
	{if $cfg->allow_paypal}
            <tr class = "bottom_separator">
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_PAYPAL_EMAIL"|translate}:</label></td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "paypalemail" value = "{$user->paypalemail}" size = "40" />
			{if $cfg->allow_user_set_messenger_fields_visibility}
                            <input type = "checkbox" name = "paypalemail_is_visible" {if $user->paypalemail_is_visible}checked = "checked"{/if}
                                   title = "{'COM_RBIDS_TIP_IS_DISPLAYED_IN_PROFILE'|translate}"
                                   class = "hasTip" />
				{else}
                            <input type = "hidden" name = "paypalemail_is_visible" value = "1" />
			{/if}
                </td>
            </tr>
	{/if}
	{if $cfg->allow_messenger}
            <tr>
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_YM"|translate}:</label></td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "YM" value = "{$user->YM}" size = "40" />
			{if $cfg->allow_user_set_messenger_fields_visibility}
                            <input type = "checkbox" name = "YM_is_visible" {if $user->YM_is_visible}checked = "checked"{/if}
                                   title = "{'COM_RBIDS_TIP_IS_DISPLAYED_IN_PROFILE'|translate}"
                                   class = "hasTip" />
				{else}
                            <input type = "hidden" name = "YM_is_visible" value = "1">
			{/if}
                </td>
            </tr>
            <tr>
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_HOTMAIL"|translate}:</label></td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "Hotmail" value = "{$user->Hotmail}" size = "40" />
			{if $cfg->allow_user_set_messenger_fields_visibility}
                            <input type = "checkbox" name = "Hotmail_is_visible" {if $user->Hotmail_is_visible}checked = "checked"{/if}
                                   title = "{'COM_RBIDS_TIP_IS_DISPLAYED_IN_PROFILE'|translate}"
                                   class = "hasTip" />
				{else}
                            <input type = "hidden" name = "Hotmail_is_visible" value = "1">
			{/if}
                </td>
            </tr>
            <tr class = "bottom_separator">
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_SKYPE"|translate}:</label></td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "Skype" value = "{$user->Skype}" size = "40" />
			{if $cfg->allow_user_set_messenger_fields_visibility}
                            <input type = "checkbox" name = "Skype_is_visible" {if $user->Skype_is_visible}checked = "checked"{/if}
                                   title = "{'COM_RBIDS_TIP_IS_DISPLAYED_IN_PROFILE'|translate}"
                                   class = "hasTip" />
				{else}
                            <input type = "hidden" name = "Skype_is_visible" value = "1">
			{/if}
                </td>
            </tr>
            <tr>
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_LINKEDIN"|translate}:</label></td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "Linkedin" value = "{$user->Linkedin}" size = "40" />
			{if $cfg->allow_user_set_messenger_fields_visibility}
                            <input type = "checkbox" name = "Linkedin_is_visible" {if $user->Linkedin_is_visible}checked = "checked"{/if}
                                   title = "{'COM_RBIDS_TIP_IS_DISPLAYED_IN_PROFILE'|translate}"
                                   class = "hasTip" />
				{else}
                            <input type = "hidden" name = "Linkedin_is_visible" value = "1">
			{/if}
                </td>
            </tr>
            <tr class = "bottom_separator">
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_FACEBOOK"|translate}:</label></td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "Facebook" value = "{$user->Facebook}" size = "40" />
			{if $cfg->allow_user_set_messenger_fields_visibility}
                            <input type = "checkbox" name = "Facebook_is_visible" {if $user->Facebook_is_visible}checked = "checked"{/if}
                                   title = "{'COM_RBIDS_TIP_IS_DISPLAYED_IN_PROFILE'|translate}"
                                   class = "hasTip" />
				{else}
                            <input type = "hidden" name = "Facebook_is_visible" value = "1">
			{/if}
                </td>
            </tr>
	{/if}
            <tr>
                <td colspan = "2">
		{$custom_fields_html}
                </td>
            </tr>


            <tr class = "bottom_separator">
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_ACTIVITY_DOMAINS"|translate}:</label></td>
                <td class = "auction_dbk_c">
		{$lists.activity_domains}
                </td>
            </tr>
            <tr class = "bottom_separator">
                <td class = "auction_dbk_c" align = "left" colspan = "2">
                    <label class = "rbid_user_label">{"COM_RBIDS_ABOUT_ME"|translate}:</label><br />
		{$lists.about_me}
                </td>
            </tr>


            <tr>
                <td class = "auction_dbk" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_ADRESS"|translate}:</label></td>
                <td class = "auction_dbk">
                    <input class = "inputbox" type = "text" name = "address" value = "{$user->address}" size = "40" />
                </td>
            </tr>
            <tr>
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_CITY"|translate}:</label></td>
                <td class = "auction_dbk_c">
                    <input class = "inputbox" type = "text" name = "city" value = "{$user->city}" size = "40" />
                </td>
            </tr>
            <tr>
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_COUNTRY"|translate}:</label></td>
                <td class = "auction_dbk_c">
		{$lists.country}
                </td>
            </tr>
	{if $cfg->google_key!=""}
            <tr>
                <td class = "auction_dbk_c" align = "center" colspan = "2">
                    <a href = "#"
                       onclick = "window.open('index.php?option=com_rbids&amp;controller=maps&amp;tmpl=component&amp;task=googlemap_tool','SelectGoogleMap','width=650,height=500');return false;">{"COM_RBIDS_SELECT_COORDINATES"|translate}</a>
                </td>
            </tr>
            <tr>
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_COORDINATE_X"|translate}:</label></td>
                <td class = "auction_dbk_c"><input class = "inputbox" type = "text" id = "googleX" name = "googleMaps_x" value = "{$user->googleMaps_x}" size = "20" /></td>
            </tr>
            <tr>
                <td class = "auction_dbk_c" align = "left"><label class = "rbid_user_label">{"COM_RBIDS_COORDINATE_Y"|translate}:</label></td>
                <td class = "auction_dbk_c"><input class = "inputbox" type = "text" id = "googleY" name = "googleMaps_y" value = "{$user->googleMaps_y}" size = "20" /></td>
            </tr>
            <tr class = "bottom_separator">
                <td class = "auction_dbk_c" align = "center" colspan = "2">
                    <br />
			{if $user->googleMaps_x!="" && $user->googleMaps_y!=""}
                            <iframe src = "{$ROOT_HOST}index.php?option=com_rbids&amp;controller=maps&amp;tmpl=component&amp;task=googlemap&amp;x={$user->googleMaps_x}&amp;y={$user->googleMaps_y}"
                                    style = "width:{$cfg->googlemap_gx+30|default:'260'}px; height:{$cfg->googlemap_gy+20|default:'100'}px;border:none;"></iframe>
			{/if}
                </td>
            </tr>
	{/if}

            <tr>
                <td align = "right" class = "user_edit_section" colspan = "2">
                    <input name = "save" value = "{'COM_RBIDS_SAVE'|translate}" class = "button validate" type = "submit" />
                    <input name = "cancel" value = "{'COM_RBIDS_CANCEL'|translate}" class = "button" type = "submit" />
                </td>
            </tr>
        </table>

    </form>
</div>
