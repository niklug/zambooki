<?php
	// Access the file from Joomla environment
	defined('_JEXEC') or die('Restricted access');
?>

<div class = "info_payment">
	<?php
	echo sprintf(trim(JText::_('FACTORY_WITHDRAW_PAYMENT_INFO')),
		trim($this->lists['amountToPay']),
		trim($this->lists['currency']),
		ucwords(trim($this->lists['receiver']->name)),
		JHtml::date(trim($this->lists['last_withdraw_date']),$this->cfg->date_format)
	);
	?>
</div>
<?php
	// Display PayPal payment form
	echo $this->withdrawForm;


?>

