<?php defined('_JEXEC') or die('Restricted access'); ?>
<div id="cpanel">
<?php
  $link = 'index.php?option=com_rbids&amp;task=orders.listing';
  JTheFactoryAdminHelper::quickiconButton( $link, 'admin/paymentitems.png', JText::_("COM_RBIDS_VIEW_ORDERS" ));

  $link = 'index.php?option=com_rbids&amp;task=payments.listing';
  JTheFactoryAdminHelper::quickiconButton( $link, 'admin/payments.png', JText::_("COM_RBIDS_VIEW_PAYMENTS" ));

    $link = 'index.php?option=com_rbids&amp;task=balances.listing';
    JTheFactoryAdminHelper::quickiconButton( $link, 'admin/payments.png', JText::_("COM_RBIDS_USER_PAYMENT_BALANCES" ));

  ?>
</div>
<div style="height:100px">&nbsp;</div>
<div style="clear:both;"> </div>
