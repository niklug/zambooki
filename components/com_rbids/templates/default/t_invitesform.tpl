<form action = "index.php" method = "post">
{* do not delete this *}
{$lists.hiddenInputs}
    <div>
        <table class = "adminlist" style = "margin-left: auto; margin-right: auto; margin-top: 10px;border-collapse: collapse;">
            <tr style="border:0;">
	    {if $lists.users}
                <td style = "border:0;padding-right: 10px;">
                    <span class = "label" style="font-size16px; text-transform: uppercase;color: #494949;">{'Invite users'|translate}</span><br >
                    <span style = "display: inline-block; vertical-align: text-top;">{$lists.users}</span>
                </td>
	    {/if}
	    {if $lists.users}
                <td style = "border:0;padding-left: 10px;">
                    <span class = "label" style="font-size16px; text-transform: uppercase;color: #494949;">{'Invite groups'|translate}</span><br />
                    <span style = "display: inline-block; vertical-align: text-top;">{$lists.groups}</span>
                </td>
	    {/if}
            </tr>
        </table>
    </div>
    <div style = "font-weight: bold; padding-top: 20px;">{'COM_RBIDS_INVITE_FORM_HELP'|translate}</div>
    <div style = "text-align: center; padding-top: 20px;">
    {$lists.submitInvites}
    </div>
</form>
