<?php

	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');
?>
<script type = "text/javascript">

	<?php $cfg = JTheFactoryHelper::getConfig(); ?>

        var bid_max_availability = '<?php echo $cfg->availability; ?>';
        var bid_date_format = '<?php echo $cfg->date_format; ?>';
//        var bid_date_format = 'Y-m-d H:i:s';

</script>

<?php
	JText::script('COM_RBIDS_FIELD_REQUIRED');
	JText::script('COM_RBIDS_EMAIL_IS_NOT_VALID');

	JText::script('COM_RBIDS_COMPULSORY_FIELDS_UNFILLED');

	JText::script('COM_RBIDS_FIELD_MUST_BE_NUMERIC');
	JText::script('COM_RBIDS_FIELD_MUST_BE_POSITIVE_NUMBER');

	JText::script('COM_RBIDS_AUCTION_START_DATE_IS_NOT_VALID');
	JText::script('COM_RBIDS_AUCTION_END_DATE_IS_NOT_VALID');
	JText::script('COM_RBIDS_AUCTION_END_DATE_EXCEEDS_MAXIMUM_ALLOWED_LENGTH');
