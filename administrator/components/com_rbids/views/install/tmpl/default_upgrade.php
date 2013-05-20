<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php 
	$app = JFactory::getApplication();
    $message=$app->getUserState('com_installer.extension_message');
    
    echo $message;
?>
<table width="100%">
<tr>
    <td>
        <h1>
        The installation detected that you already had a previous installed version of Reverse Auctions Factory.
        </h1>
    </td>
</tr>
<tr>
    <td>
        <h2>
        The previously existing Reverse Auctions Template folder WAS NOT overwritten in order to preserve any changes you might have done. If you like to overwrite the contents of the template folder please click the button below
        </h2>            
    </td>        
</tr>
<tr>
    <td>
        <button onclick="if(confirm('Are you sure that you want to overwrite your existing Reverse Auctions templates?')) window.location='index.php?option=com_rbids&task=installtemplates'">
        Overwrite Tempates now!
        </button> 
    </td>        
</tr>
</table>
