{* Include Validation script *}
{include file='js/t_javascript_language.tpl'}
{set_css}

{if $payment_items_header}
<div class = "rbids_headerinfo">{$payment_items_header}</div>
{/if}
{* Please don't remove or change action params (option, task and id) *}
<form action = "{$ROOT_HOST}index.php?option={$option}&task=save&id={$auction->id}"
      method = "post"
      name = "rAuctionForm"
      id = "rAuctionForm"
      enctype = "multipart/form-data">
<input type = "hidden" name = "Itemid" value = "{$Itemid}" />
<input type = "hidden" name = "option" value = "{$option}" />
<input type = "hidden" name = "id" value = "{$auction->id}" />
<input type = "hidden" name = "task" value = "save" />
<input type = "hidden" name = "oldid" value = "{$oldid}" />
<input type = "hidden" name = "has_custom_fields_with_cat" value = "{$custom_fields_with_cat}" />

<input type = "hidden" name = "photoid" value = "{$hidden_photoid}" />
<input type = "hidden" name = "albumid" value = "{$hidden_albumid}" />

{$form_token}


<table width = "100%" cellpadding = "0" cellspacing = "0" class = "table_edit_form_box">
    <tr>
        <td align = "left">
            <h2>{"COM_RBIDS_BID_REQUEST"|translate}:
                <span style = "font-size: 11px;">{if $auction->id}{"COM_RBIDS_EDIT"|translate}{else}{"COM_RBIDS_NEW"|translate}{/if}</span>
	    {if $auction->title}
                <span style = "font-size: 11px;">[{$auction->title}]</span>
	    {/if}
            </h2>
        </td>
    </tr>
</table>

<a title = "rbid_details" name = "rbid_details" id = "rbid_details"></a>

{include file="elements/auctiondetail/users_emails.tpl"}

<table width = "100%" cellpadding = "0" cellspacing = "0" border = "0" class = "table_edit_form_box">


{if $lists.userid}
    <tr>
        <td class = "auction_dbk_c" align = "right"  width="150px">
            <label class = "auction_lables">{'COM_RBIDS_SELLER'|translate}: </label>
        </td>
        <td class = "auction_dbk_c">
		{$lists.userid}
        </td>
    </tr>
{/if}
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_TYPE_OF_AUCTION"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
	{if $cfg->auctiontype_enable || $auction->id}
		{if $task=='editauction' && $auction->published}
                    <strong>
			    {if $auction->auction_type == $AUCTION_TYPES.AUCTION_TYPE_PRIVATE}
	                            {"COM_RBIDS_PRIVATE"|translate}
			    {elseif $auction->auction_type == $AUCTION_TYPES.AUCTION_TYPE_INVITE}
	                            {"COM_RBIDS_INVITE"|translate}
	                    {else}
	                            {"COM_RBIDS_PUBLIC"|translate}
	                    {/if}
                    </strong>
			{else}
			{$lists.auctiontype}
			{$lists.inviteSettingsContainer} {* Open Modal *}

                    &nbsp;{infobullet text="bid_auction_type_help"|translate}
		{/if}
		{else}
            <strong>
		    {if $cfg->auctiontype_val == $AUCTION_TYPES.AUCTION_TYPE_PRIVATE}{"COM_RBIDS_PRIVATE"|translate}
                    {elseif $cfg->auctiontype_val == $AUCTION_TYPES.AUCTION_TYPE_INVITE}{"COM_RBIDS_INVITE"|translate}
                    {else}
	                {"COM_RBIDS_PUBLIC"|translate}
                {/if}
            </strong>
	{/if}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_TITLE"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
	{if $task=='editauction' && $auction->published}
		{$auction->title}
		{else}
            <input class = "inputbox required" type = "text" size = "75" name = "title" value = "{$auction->title}" />
	{/if}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_CATEGORY"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
	{if $task=='editauction'  && $auction->published}
		{$auction->get('catname')}
            <input type = "hidden" name = "cat" value = "{$auction->cat}">
		{else}
		{$lists.cats}
	{/if}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_START_DATE"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
	{if $task=='editauction'  && $auction->published}
		{printdate date=$auction->start_date}
            <input class = "text_area" type = "hidden" name = "start_date" id = "start_date" value = "{$auction->start_date}" />
		{else}
		{$lists.start_date_html}
	{/if}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_END_DATE"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
	{if $task=='editauction'  && $auction->published}
		{printdate date=$auction->end_date use_hour=1}
            <input class = "text_area" type = "hidden" name = "end_date" id = "end_date" value = "{$auction->end_date}" />
		{else}
		{$lists.end_date_html}
		{if $cfg->enable_hour}
                    <input type = "text" name = "end_hour" size = "1" value = "{$lists.end_hour}" alt = "" class = "inputbox" /> :
                    <input type = "text" name = "end_minutes" size = "1" value = "{$lists.end_minute}" alt = "" class = "inputbox" />
		{/if}
            &nbsp;{$lists.tip_max_availability}
	{/if}
        </td>
    </tr>
</table>
<a title = "rbid_description" name = "rbid_description" id = "rbid_description"></a>
<table width = "100%" cellpadding = "0" cellspacing = "0" class = "table_edit_form_box">
{* Project descriptions *}

    <tr>
        <td class = "auction_dbk_c" align = "right" width="150px">
            <label class = "auction_lables">{"COM_RBIDS_SHORT_DESCRIPTION"|translate}:</label>
        </td>
        <td class = "auction_dbk">
            <input class = "inputbox" name = "shortdescription" type = "text" size = "60" value = "{$auction->shortdescription}">
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right" valign = "top">
            <label class = "auction_lables">{"COM_RBIDS_DESCRIPTION"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
	{$lists.description}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_TAGS"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
            <input name = "tags" class = "inputbox" value = "{$auction->tags}" size = "50" type = "text" /> {infobullet text="COM_RBIDS_INSERT_TAGS_COMMA_SEPARATED"|translate}
        </td>
    </tr>
{if $cfg->google_key!=""}
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_GOOGLE_MAP"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
		{if $user->googleMaps_x !="" && $user->googleMaps_y !="" }
                    <a href = "#"
                       onclick = "document.getElementById('googleX').value='{$user->googleMaps_x}';document.getElementById('googleY').value='{$user->googleMaps_y}'; return false;">{"COM_RBIDS_KEEP_PROFILE_COORDINATES"|translate}</a>
                    |
		{/if}
            <a href = "#"
               onclick = "window.open('index.php?option=com_rbids&controller=maps&tmpl=component&task=googlemap_tool','SelectGoogleMap','width=650,height=500');return false;">{"COM_RBIDS_SELECT_COORDINATES"|translate}</a>

        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_COORDINATE_X"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
            <input class = "inputbox" type = "text" id = "googleX" name = "googlex" value = "{$auction->googlex}" size = "20" />
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_COORDINATE_Y"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
            <input class = "inputbox" type = "text" id = "googleY" name = "googley" value = "{$auction->googley}" size = "20" />
        </td>
    </tr>
{/if}
    <tr>
        <td colspan = "2" class="custom_fields_box">
	{$custom_fields_html}
        </td>
    </tr>
</table>

<table width = "100%" cellpadding = "0" cellspacing = "0" class = "table_edit_form_box">
{* additional settings *}
    <tr>
        <td class = "auction_dbk_c" align = "right" width = "150px">
            <label class = "auction_lables">{"COM_RBIDS_MAXIMUM_PRICE"|translate}:</label>
        </td>
        <td class = "auction_dbk">
	{if $task=='editauction'  && $auction->id && $auction->published}
		{$auction->max_price|string_format:"%.2f"}&nbsp;{$auction->currency}
		{else}
            <input class = "inputbox validate-numeric" type = "text" size = "7" name = "max_price" value = "{$auction->max_price}" alt = "max_price">
		{$lists.currency}
	{/if}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_JOB_TIMEFRAME"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
	{if $task=='editauction'  && $auction->id && $auction->published}
		{$auction->job_deadline}
		{else}
            <input class = "inputbox validate-numeric" type = "text" size = "7" name = "job_deadline" value = "{$auction->job_deadline}" alt = "job_deadline"
                   value = "{$auction->job_deadline}">
	{/if}
            &nbsp;{"COM_RBIDS_DAYS"|translate}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_SHOW_BIDDERS_NUMBER"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
            <input type = "radio" name = "show_bidder_nr" value = "1" {if $auction->show_bidder_nr!=='0'}checked{/if}>{"COM_RBIDS_YES"|translate}
            <input type = "radio" name = "show_bidder_nr" value = "0" {if $auction->show_bidder_nr==='0'}checked{/if}>{"COM_RBIDS_NO"|translate}
        </td>
    </tr>
    <tr>
        <td class = "auction_dbk_c" align = "right">
            <label class = "auction_lables">{"COM_RBIDS_SHOW_BEST_BID"|translate}:</label>
        </td>
        <td class = "auction_dbk_c">
            <input type = "radio" name = "show_best_bid" value = "1" {if $auction->show_best_bid!=='0'}checked{/if}>{"COM_RBIDS_YES"|translate}
            <input type = "radio" name = "show_best_bid" value = "0" {if $auction->show_best_bid==='0'}checked{/if}>{"COM_RBIDS_NO"|translate}
        </td>
    </tr>
{if !$cfg->disable_images}

    <tr>
        <td class = "auction_dbk_c" align = "right" valign = "top">
            <label class = "auction_lables">{"COM_RBIDS_ATTACH_PHOTO"|translate}:</label><br />
            <small style = "color: Grey;">{"COM_RBIDS_PICTURE_MAX_SIZE"|translate}:{$cfg->max_picture_size}k</small>
        </td>
        <td class = "auction_dbk_c">
            <input class = "inputbox" {if $auction->get('imagecount')>=$cfg->maxnr_images}disabled{/if} id = "my_file_element" type = "file" name = "picture" />
		{if $cfg->main_picture_require}
                    <span style = "color: red; " title = "Field Required" class = "required_span">(*)</span>
		{/if}
		{import_js_block}
			{literal}
                            window.addEvent('domready', function(){
                            new MultiUpload( $('my_file_element'), {/literal}{$cfg->maxnr_images}-{$auction->get('imagecount')}{literal}, '_{id}', true, true, '{/literal}{$IMAGE_ROOT}{literal}');{/literal}
			{if $cfg->main_picture_require && !$auction->picture}
				{literal}
                                    $('my_file_element').addClass('required');
				{/literal}
			{/if}
			{literal}
                            });
			{/literal}
		{/import_js_block}
        </td>
    </tr>

{* Picture Gallery *}

{* ===> if images are allowed they are showed/ upload fields are showed *}
    <tr>
        <td></td>
	    {if $auction->CPhoto}
                <td class = "auction_dbk_c">
                    <img src = "{$JURI_BASE}/{$auction->CPhoto->thumbnail}" />
                    <input type="hidden" name="cphotoid" value="{$auction->CPhoto->id}">
                    <input type = "checkbox" name = "delete_cphoto_picture" id = "delete_cphoto_picture"
			    {if $cfg->main_picture_require}
                           onchange = "deleteMainToggleRequire(this.checked)"
			    {/if}
                           value = "1" />{"COM_RBIDS_DELETE"|translate}
                </td>
	    {/if}
            {if $auction->picture}
                <td class = "auction_dbk_c">
                    <img src = "{$AUCTION_PICTURES}/resize_{$auction->picture}" />
                    <input type = "checkbox" name = "delete_main_picture" id = "delete_main_picture"
			    {if $cfg->main_picture_require}
                           onchange = "deleteMainToggleRequire(this.checked)"
			    {/if}
                           value = "1" />{"COM_RBIDS_DELETE"|translate}
                </td>
	    {/if}


    </tr>

	{if $auction->get('images')|@count}
		{assign var="images" value=$auction->get('images')}
		{foreach name="extraimages" from=$images item=image}
                    <tr>
                        <td></td>
                        <td class = "auction_dbk_c">
                            <img src = "{$AUCTION_PICTURES}/resize_{$image->picture}" />
                            <input type = "checkbox" name = "delete_pictures[]" value = "{$image->id}" />{"COM_RBIDS_DELETE"|translate}
                        </td>
                    </tr>
		{/foreach}
	{/if}


{/if}
    <tr>
        <td></td>
    </tr>
{if $cfg->enable_attach || $cfg->nda_option}

{* attachments *}

	{if $cfg->nda_option}
		{if $task != 'republish' && $auction->NDA_file}
                    <tr>
                        <td class = "auction_dbk_c" align = "right">
                            <input type = "hidden" name = "nda_uploaded" value = "1">
                            <label class = "auction_lables">{"COM_RBIDS_NDA_FILE"|translate}:</label>&nbsp;
                        </td>
                        <td class = "auction_dbk">
                            <strong><a href = "{$auction->get('links.download_nda')}" target = "_blank">{$auction->NDA_file}</a></strong>&nbsp;&nbsp;&nbsp;
                            <a href = "{$auction->get('links.deletefile_nda')}">{"COM_RBIDS_DELETE"|translate}</a>
                        </td>
                    </tr>
			{else}
                    <tr>
                        <td class = "auction_dbk_c" align = "right">
                            <label class = "auction_lables">{"COM_RBIDS_NDA_FILE"|translate}:</label>&nbsp;
                        </td>
                        <td class = "auction_dbk">
                            <input name = "NDA_file" class = "inputbox{if $cfg->nda_compulsory} required{/if}" type = "file" size = "70"
                                   id = "NDA_file">{infobullet text="COM_RBIDS_NON_DESCLOSIOUR_AGREEMENT"|translate}
				{if $cfg->nda_extensions}
                                    <br />
                                    <small style = "color: Grey;">{"COM_RBIDS_ALLOWED_NDA_EXTENSIONS"|translate} {$cfg->nda_extensions}</small>
				{/if}
                        </td>
                    </tr>
		{/if}
	{/if}
	{if $cfg->enable_attach}
            <tr>
		    {if  $task != 'republish' &&  $auction->file_name}
                        <td class = "auction_dbk_c" align = "right">
                            <span class = "auction_lables">{"COM_RBIDS_ATTACHEMENT"|translate}:</span>&nbsp;<br />
                        </td>
                        <td class = "auction_dbk">
                            <strong><a href = "{$auction->get('links.download_file')}" target = "_blank">{$auction->file_name}</a></strong>&nbsp;&nbsp;&nbsp;
                            <a href = "{$auction->get('links.deletefile_file')}">{"COM_RBIDS_DELETE"|translate}</a>
                        </td>
			    {else}
                        <td class = "auction_dbk_c" align = "right">
                            <label class = "auction_lables">{"COM_RBIDS_ATTACHEMENT"|translate}:</label>&nbsp;<br />
                            <small style = "color: Grey;">{"COM_RBIDS_MAXIMUM_FILE_SIZE"|translate|replace:"%s":$cfg->attach_max_size}</small>
				{if $cfg->attach_extensions}
                                    <br />
                                    <small style = "color: Grey;">{"COM_RBIDS_ALLOWED_ATTACHEMENT_EXTENSIONS"|translate} {$cfg->attach_extensions}</small>
				{/if}
                        </td>
                        <td class = "auction_dbk">
                            <input name = "attachment" class = "inputbox{if $cfg->attach_compulsory} required{/if}" type = "file" size = "70" />
                        </td>
		    {/if}
            </tr>
	{/if}
    <tr>
    {* Please don't remove this label *}
        <td class = "auction_dbk_c" colspan = 2><label>&nbsp;</label></td>
    </tr>
</table>
{/if}

<table width = "100%" cellpadding = "0" cellspacing = "0" class = "table_edit_form_box">

    <tr>
        <td class = "auction_dbk_c" align = "right" width="150px">
            <label class = "auction_lables">{"COM_RBIDS_PUBLISHED"|translate}:</label>
        </td>
        <td class = "auction_dbk">
	{if $cfg->auctionpublish_enable}
            {if $task=='editauction' && $auction->published}{"COM_RBIDS_YES"|translate}{else}{$lists.published}{/if}
        {else}
            {if ($auction->id && $auction->published)||(!$auction->id && $cfg->auctionpublish_val)}{"COM_RBIDS_YES"|translate}{else}{"COM_RBIDS_NO"|translate}{/if}
        {/if}
        </td>
        <td align = "right" class = "auction_edit_section" colspan = "2">
            <input type = "submit" name = "save" value = "{'COM_RBIDS_SAVE'|translate}" class = "button validate" />
        </td>
    </tr>
</table>
</form>
