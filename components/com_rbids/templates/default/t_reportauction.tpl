{set_css}

<form action="{$ROOT_HOST}index.php?option={$option}" name="auctionForm" method="POST" class="report_form">
<input type="hidden" name="Itemid" value="{$Itemid}">
<input type="hidden" name="id" value="{$auction->id}">
<input type="hidden" name="task" value="do_report">
<div class="auction_edit_header" >
     <table width="100%" cellpadding="0" cellspacing="0">
         <tr>
            <td>{"COM_RBIDS_REPORT_OFFER"|translate}</td>
         </tr>
     </table>
</div>
<table width="100%" cellpadding="0" cellspacing="0" class="user_detailstable">
  <tr>
   <td><label class="auction_lables">{"COM_RBIDS_AUCTION_TITLE"|translate}:</label> <span class="auction_title">{$auction->title}</span></td>
  </tr>
  <tr>
   <td>
	<textarea name="message" rows="10" cols="50"></textarea>
   </td>
  </tr>
  <tr>
   <td>
	<a href='javascript:history.go(-1);'><input type="button" class="button" value="{'COM_RBIDS_BACK'|translate}"></a>
	<input type="submit" name="send" value="{'COM_RBIDS_SEND'|translate}" class="button" />
   </td>
  </tr>
</table>
</form>
