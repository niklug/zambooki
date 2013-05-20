{include file='js/t_javascript_language.tpl'}
{set_css}
<h2><strong>{$page_title}</strong></h2>
<form action="{$ROOT_HOST}/index.php" method="get" name="rbidsForm">
<input type="hidden" name="option" value="{$option}">
<input type="hidden" name="task" value="{$task}">
<input type="hidden" name="controller" value="user">
<input type="hidden" name="Itemid" value="{$Itemid}">
{"COM_RBIDS_TOTAL_FOUND"|translate}: {$pagination->total}
{foreach from=$users item=user}
<div class="rbid_user">
	<div class="user_head">
		<a href="{$user->link}">
			{$user->username}
		</a>
        <div style="float:right">
            <span class="rating">{$user->ratings.rating_overall}</span>        
        </div>
        {positions position="header" item=$user page="user_profile"}
	</div>
	<div class="user_info">
		<table width="100%">
			<tr>
				<td>
				    {if $cfg->google_key!="" && ( ($user->googleMaps_x!="" && $user->googleMaps_y!="") )}
				    	<a class="modal" {literal} rel="{handler: 'iframe', size: { {/literal} x:{$cfg->googlemap_gx+25|default:'260'}, y:{$cfg->googlemap_gy+20|default:'100'}} {literal} } " {/literal} href="{$ROOT_HOST}index.php?option=com_rbids&controller=maps&tmpl=component&task=googlemap&x={$user->googleMaps_x}&y={$user->googleMaps_y}"></a>
				    {/if}
                    {positions position="googlemaps" item=$user page="user_profile"}
				</td>
				<td>
					{if !$cfg->hide_contact}
						<label class="auction_lables">{"COM_RBIDS_CITY"|translate}</label>:<span>{$user->city|default:"--"}</span> ,<br>
						{if $user->country_name}
							<label class="auction_lables">{"COM_RBIDS_COUNTRY"|translate}</label>: <span>{$user->country_name}</span>,<br>
						{/if}
						{if $cfg->allow_messenger== "1"}
						    <label class="auction_lables">{"COM_RBIDS_YM"|translate}</label>: <span>{$user->YM|default:"--"}</span><br>
							<label class="auction_lables">{"COM_RBIDS_HOTMAIL"|translate}</label>:<span>{$user->Hotmail|default:"--"}</span><br>
							<label class="auction_lables">{"COM_RBIDS_SKYPE"|translate}</label>:<span>{$user->Skype|default:"--"}</span><br>
						{/if}
					{/if}
					{if $user->AreasOfExpertise}
					<strong><label class="auction_lables">{"COM_RBIDS_AREA_OF_EXPERTISE"|translate}</label>:</strong><br/>
                    <div class="rbid_area_expertise">
					   {$user->AreasOfExpertise}
                    </div>
					{/if}
                    {positions position="details" item=$user page="user_profile"}                    
				</td>
			</tr>
		</table>
	</div>
</div>
{/foreach}
{include file='elements/t_listfooter.tpl'}
</form>
