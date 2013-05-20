<?php defined('_JEXEC') or die('Restricted access'); ?>
<?php
	jimport('joomla.html.pane');
	JHTML::_('behavior.calendar');
	JHTML::_('behavior.tooltip');

	$editor =& JFactory::getEditor();
	JFilterOutput::objectHTMLSafe($this->row, ENT_QUOTES);
	$pane = & JPane::getInstance('sliders', array('allowAllClose' => true));

	if (file_exists(JPATH_COMPONENT_ADMINISTRATOR . DS . 'js' . DS . 'javascript_language.php')) {
		include_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'js' . DS . 'javascript_language.php';
	}
	// Load js image uploader
	$doc = JFactory::getDocument();
	$doc->addScript(JURI::root(true) . '/components/com_rbids/js/date.js');
	$doc->addScript(JURI::root(true) . '/components/com_rbids/js/Stickman.MultiUpload.js');
	$doc->addScript(JURI::base(true) . '/components/com_rbids/js/auction_edit.js');
?>
<style type = "text/css">
    fieldset input, fieldset textarea, fieldset select, fieldset img, fieldset button {
        float: none;
    }

    .auction_notice {
        font-size: 120%;
        font-weight: bolder;
        color: #FFFF00;
        background-color: #A80000;
        -moz-border-radius: 15px;
        border-radius: 12px;
        padding: 20px 10px 20px 10px;
        border: 1px solid #000;
        text-align: center;
    }
</style>
<?php if ($this->cfg->admin_approval && !$this->row->approved) : ?>
<div class = "auction_notice"><?php echo JText::_("COM_RBIDS_THIS_AUCTION_REQUIRES_ADMIN_APPROVAL"); ?></div>
<?php endif; ?>

<form action = "index.php?option=com_rbids" method = "post" name = "adminForm" id = "adminForm" class = "form-validate" enctype = "multipart/form-data">

<input type = "hidden" name = "id" value = "<?php echo $this->row->id; ?>" />
<input type = "hidden" name = "cid" value = "<?php echo $this->row->id; ?>" />
<input type = "hidden" name = "task" value = "save" />
<input type = "hidden" name = "isAdmin" value = "1" />


<table width = "100%">
<tr>
<td width = "50%">
    <table class = "paramlist admintable auction_edit_table_left" style = "width:auto;">
        <tr>
            <td class = "paramlist_key">
                <label for = "title"><?php echo JText::_('COM_RBIDS_TITLE'); ?>:</label>
            </td>
            <td class = "paramlist_value">
                <input class = "text_area required"
                       type = "text"
                       name = "title"
                       id = "title"
                       size = "50"
                       maxlength = "50"
                       style = "width: 95%;
                                height: 30px;
                                font-size: 16px;
                                font-weight: bold;"
                       value = "<?php echo $this->row->title; ?>"
                        />
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_CATEGORY'); ?>:</label>
            </td>
            <td class = "paramlist_value"><?php echo $this->category; ?></td>
        </tr>
        <tr>
            <td class = "paramlist_value" colspan = "2"><br /></td>
        </tr>
        <tr>
            <td class = "paramlist_key"><?php echo JText::_("COM_RBIDS_FEATURED_DISPLAY");?>:</td>
            <td class = "paramlist_value"><?php echo $this->featured; ?></td>
        </tr>
        <tr>
            <td class = "paramlist_value" colspan = "2"><br /></td>
        </tr>
        <tr>
            <td class = "paramlist_key"><label width = "100"><?php echo JText::_('COM_RBIDS_MAX_PRICE'); ?>:</label></td>
            <td class = "paramlist_value">
                <input type = "text"
                       name = "max_price"
                       class = "validate-numeric"
                       value = "<?php echo $this->row->max_price; ?>" />
		    <?php echo $this->currency; ?>
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_SHORT_DESCRIPTION'); ?>:</label>
            </td>
            <td class = "paramlist_value">
                <textarea name = "shortdescription" style = "width:99%" rows = "10"><?php echo $this->row->shortdescription; ?></textarea>
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key"><label width = "100"><?php echo JText::_('COM_RBIDS_DESCRIPTION'); ?>:</label></td>
            <td class = "paramlist_value">
		    <?php echo $editor->display('description', $this->row->description, '500', '300', '10', '10', array('pagebreak', 'readmore'));?>
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_TAGS'); ?>:</label>
            </td>
            <td class = "paramlist_value">
                <input type = "text" name = "tags" size = "50" value = "<?php echo $this->row->tags; ?>" />
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_JOB_DEADLINE'); ?>:</label>
            </td>
            <td class = "paramlist_value">
                <input type = "text" name = "job_deadline" size = "5" value = "<?php echo $this->row->job_deadline; ?>" />
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_SHOW_BIDDERS_NUMBER'); ?>:</label>
            </td>
            <td class = "paramlist_value">
		    <?php echo JHTML::_('select.booleanlist', 'show_bidder_nr', '', $this->row->show_bidder_nr);?>
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_SHOW_BEST_BID'); ?>:</label>
            </td>
            <td class = "paramlist_value">
		    <?php echo JHTML::_('select.booleanlist', 'show_best_bid', '', $this->row->show_best_bid);?>
            </td>
        </tr>
        <!-- Picture uploader -->
        <tr>
            <td class = "paramlist_key">
                <label><?php echo JText::_('COM_RBIDS_AUCTION_IMAGES'); ?>:</label><br />
                <small style = "color: Grey;"><?php echo JText::_('COM_RBIDS_PICTURE_MAX_SIZE') . ':' . $this->cfg->max_picture_size; ?>k</small>
            </td>
            <td class = "auction_dbk_c" colspan = 2>
                <input class = "inputbox <?php if ($this->cfg->main_picture_require && !$this->row->picture) echo 'required'; ?>"
			<?php if ($this->row->get('imagecount') >= $this->cfg->maxnr_images) echo 'disabled="disabled"' ?>
                       id = "my_file_element"
                       type = "file"
                       name = "picture" />
                <script type = "text/javascript">
                    window.addEvent('domready', function () {
                        new MultiUpload($('my_file_element'), <?php echo $this->cfg->maxnr_images - $this->row->get('imagecount') ?>, null, true, true,
	                        '<?php echo JURI::root(true); ?>/components/com_rbids/images/'
                        );
                    });
                </script>
            </td>
        </tr>
        <tr>
            <td colspan = "2"><span class = "v_spacer_5"></span></td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_ATTACHED_FILE'); ?>:</label><br />
                <small style = "color: Grey;"><?php echo sprintf(JText::_("COM_RBIDS_MAXIMUM_FILE_SIZE"), $this->cfg->attach_max_size); ?></small>
		    <?php if ($this->cfg->attach_extensions): ?>
                <br />
                <small style = "color: Grey;"><?php echo JText::_("COM_RBIDS_ALLOWED_ATTACHEMENT_EXTENSIONS") . ' ' . $this->cfg->attach_extensions; ?></small>
		    <?php endif; ?>
            </td>
            <td class = "paramlist_value">
		    <?php if ($this->row->has_file): ?>
                <!-- Attachment file is uploaded -->

                <input type = "file" name = "attachment" id = "attachment" value = "" class = "inputbox" />
                <span class = "v_spacer_5"></span>

		    <?php echo $this->row->file_name; ?>
                <input type = "checkbox" name = "delete_atachment" value = "1"
			<?php if ($this->cfg->attach_compulsory): ?>
                       onchange = "deleteAttachmentRequire(this.checked)"
			<?php endif; ?>
                        />

		    <?php else: ?>
                <!-- No Attachment file -->
                <input type = "file" name = "attachment" id = "attachment" value = "" class = "inputbox
                        <?php if ($this->cfg->attach_compulsory) echo ' required';?>
                        " />

		    <?php endif;?>
            </td>
        </tr>
        <tr>
            <td class = "paramlist_key">
                <label width = "100"><?php echo JText::_('COM_RBIDS_NDA_FILE'); ?>:</label>
		    <?php if ($this->cfg->nda_extensions): ?>
                <br />
                <small style = "color: Grey;"><?php echo JText::_("COM_RBIDS_ALLOWED_NDA_EXTENSIONS") . '<br /> ' . $this->cfg->nda_extensions; ?></small>
		    <?php endif; ?>
            </td>
            <td class = "paramlist_value">
		    <?php if ($this->row->NDA) : ?>
                <!-- NDA file is uploaded -->

                <input type = "file" name = "NDA_file" id = "NDA_file" value = "" class = "inputbox" />
                <span class = "v_spacer_5"></span>

		    <?php echo $this->row->NDA_file; ?>
                <input type = "checkbox" name = "delete_NDA" value = "1"
			<?php if ($this->cfg->nda_compulsory): ?>
                       onchange = "deleteNDARequire(this.checked)"
			<?php endif; ?>
                        />

		    <?php else: ?>
                <!--	No NDA file -->
                <input type = "file" name = "NDA_file" id = "NDA_file" value = "" class = "inputbox
		<?php if ($this->cfg->nda_compulsory) echo ' required';?>
                " />

		    <?php endif;?>
            </td>
        </tr>
	    <?php if ($this->row->isCustomFields()): ?>
        <tr>
            <td colspan = "2">
                <fieldset>
                    <legend><?php echo JText::_('COM_RBIDS_CUSTOM_FIELDS');?></legend>
			<?php
			echo JHtml::_('customfields.displayfieldshtml', $this->row, $this->fields);
			?>
                </fieldset>
            </td>
        </tr>
	    <?php endif;?>
    </table>
</td>
<td valign = "top">
<?php
	///////////////////// PANE DETAILS START //////////////////////////////////
	echo $pane->startPane("auction-pane");
	echo $pane->startPanel(JText::_('COM_RBIDS_DETAILS'), "detail-page");
?>
<table width = "100%" style = "padding: 5px; margin-bottom: 10px;">
    <tr>
        <td valign = "top">
            <label width = "100">
		    <?php echo JText::_('COM_RBIDS_HITS'); ?>:
            </label>
        </td>
        <td><?php echo $this->row->hits;?></td>
    </tr>
    <tr>
        <td valign = "top">
            <label width = "100">
		    <?php echo JText::_('COM_RBIDS_PUBLISH_STATUS'); ?>:
            </label>
        </td>
        <td><?php echo JHTML::_('select.booleanlist', 'published', '', $this->row->published);?></td>
    </tr>
    <tr>
        <td>
            <label width = "100"><?php echo JText::_("COM_RBIDS_AUCTION_CLOSED");?>:</label>
        </td>
        <td>
		<?php echo JHTML::_('select.booleanlist', 'close_offer', '', $this->row->close_offer);?>
        </td>
    </tr>
    <tr>
        <td><label width = "100"><?php echo JText::_("COM_RBIDS_CLOSED_BY_ADMIN"); ?>:</label></td>
        <td>
		<?php echo JHTML::_('select.booleanlist', 'close_by_admin', '', $this->row->close_by_admin); ?><br />
		<?php if ($this->row->close_offer == 1) echo "Closed on " . $this->row->closed_date; ?>
        </td>
    </tr>
    <tr>
        <td>
            <label width = "100">
		    <?php echo JText::_('COM_RBIDS_AUCTION_TYPE'); ?>:
            </label>
        </td>
        <td>
		<?php echo $this->auctiontype; ?>
            <!-- Link to open modal with users and users groups  to invite -->
		<?php echo $this->inviteSettingsContainer; ?>
        </td>
    </tr>
    <tr>
        <td>
            <label width = "100">
		    <?php echo JText::_('COM_RBIDS_START_DATE'); ?>:
            </label>
        </td>
        <td>
		<?php echo JHTML::_('calendar', JHtml::date($this->row->start_date, 'Y-m-d H:i:s'), 'start_date', 'start_date', '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '25', 'maxlength' => '19')); ?>
        </td>
    </tr>
    <tr>
        <td>
            <label width = "100">
		    <?php echo JText::_('COM_RBIDS_END_DATE'); ?>:
            </label>
        </td>
        <td>
		<?php echo JHTML::_('calendar', JHtml::date($this->row->end_date, 'Y-m-d H:i:s'), 'end_date', 'end_date', '%Y-%m-%d %H:%M:%S', array('class' => 'inputbox', 'size' => '25', 'maxlength' => '19')); ?>
        </td>
    </tr>
</table>
<?php ///////////////////// PANE DETAILS END //////////////////////////////////    ?>
<?php
///////////////////// PANE BIDS LIST START //////////////////////////////////    
	echo $pane->endPanel();
	$title = JText::_("COM_RBIDS_BID_LIST");
	echo $pane->startPanel($title, "bidlist-page");
?>
<table class = "adminlist" width = "100%" style = "padding: 5px; margin-bottom: 10px;">
    <tr>
        <th><?php echo JText::_("COM_RBIDS_FROM");?></th>
        <th><?php echo JText::_("COM_RBIDS_PRICE");?></th>
        <th><?php echo JText::_("COM_RBIDS_MESSAGE");?></th>
        <th><?php echo JText::_("COM_RBIDS_DATE");?></th>
    </tr>
	<?php
	if (isset($this->row->bids) && count($this->row->bids))
		foreach ($this->row->bids as $k => $m) {
			?>
                    <tr>
                        <td><?php echo $m->name; ?></td>
                        <td><?php echo $m->bid_price; ?></td>
                        <td><span class = "editlinktip hasTip" title = "<?php echo $m->message, ".."; ?>"><?php echo substr($m->message, 0, 20); ?></span></td>
                        <td><?php echo $m->modified; ?></td>
                    </tr>
			<?php } ?>
</table>
<?php ///////////////////// PANE BIDS LIST END //////////////////////////////////    ?>
<?php
///////////////////// PANE MESSAGES START //////////////////////////////////    
	echo $pane->endPanel();
	$title = JText::_("COM_RBIDS_MESSAGES");
	echo $pane->startPanel($title, "messages-page");
?>
<table class = "adminlist" width = "100%" style = "padding: 5px; margin-bottom: 10px;">
    <tr>
        <th><?php echo JText::_("COM_RBIDS_FROM");?></th>
        <th><?php echo JText::_("COM_RBIDS_TO");?></th>
        <th><?php echo JText::_("COM_RBIDS_MESSAGE");?></th>
        <th><?php echo JText::_("COM_RBIDS_DATE");?></th>
    </tr>
	<?php
	if (isset($this->row->messages) && count($this->row->messages))
		foreach ($this->row->messages as $k => $m) {
			?>
                    <tr>
                        <td><?php echo $m->fromuser; ?></td>
                        <td><?php echo $m->touser; ?></td>
                        <td><span class = "editlinktip hasTip" title = "<?php echo $m->message, ".."; ?>"><?php echo substr($m->message, 0, 20); ?></span></td>
                        <td><?php echo $m->modified; ?></td>
                    </tr>
			<?php } ?>
    <tr>
        <td colspan = "4" align = "center"><input type = "button" class = 'button'
                                                  onclick = "location.href='index.php?option=com_rbids&task=write_admin_message&return_task=offers&auction_id=<?php echo $this->row->id; ?>'"
                                                  value = "&nbsp;&nbsp;&nbsp;<?php echo JText::_("COM_RBIDS_SEND"); ?>&nbsp;&nbsp;&nbsp;"></td>
    </tr>
</table>
<?php ///////////////////// PANE MESSAGES END //////////////////////////////////    ?>
<?php
	///////////////////// PANE USER DETAILS START //////////////////////////////////
	$title = JText::_('COM_RBIDS_AUCTIONEER_DETAILS');
	echo $pane->endPanel();
	echo $pane->startPanel($title, "detail-page");
?>
<table width = "100%" style = "padding: 5px; margin-bottom: 10px;">

    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_USERNAME");?></span></td>
        <td><?php echo $this->row->userdetails->username;?></td>
    </tr>
    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_NAME");?></span></td>
        <td><?php echo $this->row->userdetails->name, " ", $this->row->userdetails->surname;?></td>
    </tr>
	<?php if (isset($this->row->userdetails->phone)) { ?>
    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_PHONE");?></span></td>
        <td><?php echo $this->row->userdetails->phone; ?></td>
    </tr>
	<?php } ?>
	<?php if (isset($this->row->userdetails->email)) { ?>
    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_EMAIL");?></span></td>
        <td><?php echo $this->row->userdetails->email; ?></td>
    </tr>
	<?php } ?>
	<?php if (isset($this->row->userdetails->address)) { ?>
    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_ADDRESS");?></span></td>
        <td><?php echo $this->row->userdetails->address; ?></td>
    </tr>
	<?php } ?>
    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_CITY");?></span></td>
        <td><?php echo $this->row->userdetails->city; ?></td>
    </tr>
    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_COUNTRY");?></span></td>
        <td><?php echo $this->row->userdetails->country; ?></td>
    </tr>
    <tr>
        <td><span class = "label"><?php echo JText::_("COM_RBIDS_YM");?></span></td>
        <td><?php echo $this->row->userdetails->YM; ?></td>
    </tr>
	<?php if (isset($this->user_fields))
	foreach ($this->user_fields as $field) {
		?>
            <tr>
                <td><span class = "label"><?php echo $field->name; ?></span></td>
                <td><?php echo $this->row->userdetails->{$field->db_name}; ?></td>
            </tr>
		<?php } ?>
</table>
<?php ///////////////////// PANE USER DETAILS END //////////////////////////////////    ?>
<?php
	echo $pane->endPanel();
	echo $pane->endPane();
?>


<!-- Display image gallery if raffle has some images -->
<?php if ($this->row->picture || count($this->row->get('images'))): ?>
<fieldset class = "adminFieldSet">
    <legend><?php echo JText::_('COM_RBIDS_EDIT_RAFFLE_IMAGE_GALLERY');?></legend>
    <table width = "100%">
        <!-- Main Picture -->
	    <?php if ($this->row->picture): ?>
        <tr>
            <td class = "images_type_td" colspan = "4"><span class = "images_type"><?php echo JText::_('COM_RBIDS_EDIT_RAFFLE_MAIN_PICTURE')?></span></td>
        </tr>
        <tr>
            <td>
                <span style = "float: left;"><img src = "<?php echo JURI::root(true) . '/media/com_rbids/images/resize_' . $this->row->picture; ?>" alt = '' /></span>
                <span style = "float: left;clear: both;">
                    <input type = "checkbox" name = "delete_main_picture"
	                    <?php if ($this->cfg->main_picture_require): ?>
                           onchange = "deleteMainToggleRequire(this.checked)"
	                    <?php endif;?>
                           value = "1"
                            /><?php echo JText::_('COM_RBIDS_DELETE'); ?>
                </span>
            </td>
        </tr>
	    <?php endif; ?>
        <!-- Additional pictures -->
	    <?php if (count($this->row->get('images'))): ?>

        <tr>
            <td class = "images_type_td" colspan = "4"><span class = "images_type"><?php echo JText::_('COM_RBIDS_EDIT_RAFFLE_ADDITIONAL_PICTURES')?></span></td>
        </tr>
        <tr>
		<?php foreach ($this->row->get('images') as $k => $image): ?>
            <!-- Display images on 4 columns -->
		<?php if ($k % 4 == 0) echo '</tr><tr>'; ?>

            <td>
                <span style = "float: left;"><img src = "<?php echo JURI::root(true) . '/media/com_rbids/images/resize_' . $image->picture; ?>" alt = '' /></span>
					<span style = "float: left;clear: both;"><input type = "checkbox" name = "delete_pictures[]" value = "<?php echo $image->id; ?>" />
						<?php echo JText::_('COM_RBIDS_DELETE'); ?></span>

            </td>
		<?php endforeach; ?>
        </tr>
	    <?php endif; ?>

    </table>
</fieldset>
	<?php endif; ?>
<!-- Please don't remove this label -->
<label>&nbsp;</label>
</td>
</tr>
</table>
</form>
