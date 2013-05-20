<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action = "index.php" method = "post" name = "adminForm">
    <table width = "100%">
        <tr>
            <td width = "80%" valign = "top">
                <table class = "adminform">
                    <tr>
                        <td width = "250" style = " font-size: 14px;font-weight: bold; color: #494949;text-transform: uppercase;"><?php echo JText::_("COM_RBIDS_USER_BALANCE"); ?></td>
                        <td align = "left" style = "font-size: 24px; font-weight:bold; color: #2b4e70;"><?php echo $this->balance->balance, " ", $this->balance->currency; ?></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td width = "80%" valign = "top">
                <fieldset>
                    <legend style = "font-weight:bold; font-size:16px; color: #494949;"><?php echo JText::_("COM_RBIDS_USER_DETAILS"); ?></legend>
                    <table class = "adminform no_border">

                        <tr>
                            <td width = "20%"><?php echo JText::_("COM_RBIDS_USERNAME"); ?></td>
                            <td><?php echo $this->user->username; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_("COM_RBIDS_NAME"); ?></td>
                            <td><?php echo $this->user->name; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_("COM_RBIDS_SURNAME"); ?></td>
                            <td><?php echo $this->user->surname; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_("COM_RBIDS_ADDRESS"); ?></td>
                            <td><?php echo $this->user->address; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_("COM_RBIDS_PHONE"); ?></td>
                            <td><?php echo $this->user->phone; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_("COM_RBIDS_EMAIL"); ?></td>
                            <td><a href = "mailto:<?php echo $this->user->email; ?>"><?php echo $this->user->email;?></a></td>
                        </tr>
			    <?php if (isset($this->user_fields))
			    foreach ($this->user_fields as $field) {
				    ?>
                                <tr>
                                    <td><?php echo $field->name; ?></td>
                                    <td><?php echo $this->user->{$field->db_name}; ?></td>
                                </tr>
				    <?php } ?>
                    </table>
                </fieldset>

                <fieldset>
                    <legend style = "font-weight:bold; font-size:16px; color: #494949;"><?php echo JText::_("COM_RBIDS_STATISTICS"); ?></legend>
                    <table class = "adminform no_border">
                        <tr>
                            <td valign = "top" width = "20%"><?php echo JText::_("COM_RBIDS_NR_AUCTIONS"); ?></td>
                            <td valign = "top">
				    <?php echo JText::_("COM_RBIDS_NO"); ?>: <?php echo $this->nr_ads;?><br />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_("COM_RBIDS_LAST_AUCTION"); ?></td>
                            <td><?php if ($this->last_ads_placed) echo $this->last_ads_placed; else echo '-';?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_("COM_RBIDS_FIRST_AUCTION"); ?></td>
                            <td><?php if ($this->first_ads_placed) echo $this->first_ads_placed; else echo "-";?></td>
                        </tr>
                    </table>
                </fieldset>
            </td>
        </tr>
    </table>

    <input type = "hidden" name = "option" value = "com_rbids">
    <input type = "hidden" name = "task" value = "detailuser">
    <input type = "hidden" name = "id" value = "<?php echo isset($this->user->userid) ? $this->user->userid : $this->user->user_id; ?>">
</form>
