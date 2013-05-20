<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
	JFactory::getDocument()->addScriptDeclaration(
		"
    	function submitbutton(action){
    		if (document.adminForm.message.value || action==''){
    		    submitform(action);
    			return true;
    		}
    		alert('" . JText::_("COM_RBIDS_YOU_CAN_NOT_SEND_AN_EMPTY_MESSAGE") . "');

    	}
        "
	);
?>
<form method = "POST" action = "index.php" name = "adminForm">
	<input type = "hidden" name = "auction_id" value = "<?php echo $this->auction->id;?>" />
	<input type = "hidden" name = "option" value = "com_rbids" />
	<input type = "hidden" name = "task" value = "send_message_auction" />
	<input type = "hidden" name = "isprivate" value = "1" />

	<input type = "hidden" name = "return_task" value = "<?php echo $this->return_task;?>" />
	<table width = "100%">
		<tr>
			<td><h2><?php echo JText::_("COM_RBIDS_TO"); ?>:&nbsp;<?php echo $this->user->name;?>(<?php echo $this->user->username;?>)</h2></td>
		</tr>
		<tr>
			<td><h3><?php echo JText::_("COM_RBIDS_REGARDING_AUCTION"); ?>:&nbsp;<?php echo $this->auction->title;?></h3></td>
		</tr>
		<tr>
			<td><?php echo JText::_("COM_RBIDS_AUCTION_ID"); ?>:&nbsp;<?php echo $this->auction->id;?></td>
		</tr>
		<tr>
			<td>
				<textarea name = "message" class = "auctionmessage" cols = "80" rows = "10"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input type = "submit" name = "submit" value = "<?php echo JText::_("COM_RBIDS_SEND"); ?>" class = "inputbutton" />
			</td>
		</tr>
	</table>
</form>
