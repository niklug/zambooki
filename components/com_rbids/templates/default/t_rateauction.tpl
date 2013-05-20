{set_css}

<form action="{$ROOT_HOST}index.php" name="auctionForm" method="POST" class="rateauction_form">
<input type="hidden" name="option" value="{$option}"/>
<input type="hidden" name="Itemid" value="{$Itemid}"/>
<input type="hidden" name="id" value="{$auction->id}"/>
<input type="hidden" name="user_rated" value="{$user->userid}"/>
<input type="hidden" name="controller" value="ratings"/>
<input type="hidden" name="task" value="reviews_save"/>
    <div class="auction_edit_header" >
         <table width="100%" cellpadding="0" cellspacing="0">
             <tr>
                <td>{"COM_RBIDS_RATE_AUCTION"|translate}</td>
             </tr>
         </table>
    </div>
    <table width="100%" cellpadding="0" cellspacing="0" class="user_detailstable">
      <tr>
       <td><label class="auction_lables">{"COM_RBIDS_AUCTION_TITLE"|translate}</label>: <span class="auction_title">{$auction->title}</span></td>
      </tr>
      <tr>
       <td><label class="auction_lables">{"COM_RBIDS_USER"|translate}</label>: <span class="auction_title">{$user->username}</span></td>
      </tr>
      <tr>
       <td>
    	<select class="inputbox ratingselect" name="vote">
            <option value="5">{"COM_RBIDS___VERY_GOOD"|translate}</option> 
            <option value="4">{"COM_RBIDS___GOOD"|translate}</option> 
            <option value="3">{"COM_RBIDS___NEUTRAL"|translate}</option> 
            <option value="2">{"COM_RBIDS___BAD"|translate}</option> 
            <option value="1">{"COM_RBIDS___HORRIBLE"|translate}</option> 
        </select>
       </td>
      </tr>
      <tr>
       <td>
    	<textarea name="comment" rows="10" cols="50"></textarea>
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
