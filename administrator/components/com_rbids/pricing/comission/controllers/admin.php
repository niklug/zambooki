<?php
/**------------------------------------------------------------------------
com_rbids - Reverse Auction Factory 3.0.0
------------------------------------------------------------------------
 * @author TheFactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thefactory.ro
 * Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
 * @build: 01/04/2012
 * @package: RBids
 * @subpackage: Comission
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class JRBidAdminComissionController extends JController
{
    var $name='AdminComission';
    var $_name='AdminComission';
    var $itemname='comission';
    var $itempath=null;

	function __construct()
	{
	   JHtml::_('behavior.framework');
	    $this->itempath=JPATH_COMPONENT_ADMINISTRATOR.DS.'pricing'.DS.$this->itemname;
	   $config=array(
            'view_path'=>$this->itempath.DS."views"
       );
       JLoader::register('JRBidAdminComissionToolbar',$this->itempath.DS.'toolbars'.DS.'toolbar.php');
       JLoader::register('JRBidAdminComissionHelper',$this->itempath.DS.'helpers'.DS.'helper.php');
       jimport('joomla.application.component.model');
       JModel::addIncludePath($this->itempath.DS.'models');
       JTable::addIncludePath($this->itempath.DS.'tables');
       $lang=&JFactory::getLanguage();
        $lang->load(APP_PREFIX.'.'.$this->itemname);
       parent::__construct($config);

    }
    function getView( $name = '', $type = 'html', $prefix = 'JRBidPricingViewComission', $config = array() )
    {
        $MyApp=&JTheFactoryApplication::getInstance();
        $config['template_path']=$this->itempath.DS.'views'.DS.strtolower($name).DS."tmpl";
        return parent::getView($name,$type,$prefix,$config);
    }

    function execute($task)
    {
        JRBidAdminComissionToolbar::display($task);
        return parent::execute($task);
    }
    function config()
    {
        jimport('joomla.html.editor');
        JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'htmlelements');

        $model=&JModel::getInstance('Comission','J'.APP_PREFIX.'PricingModel');
        $r=$model->getItemPrices();

        JModel::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'thefactory'.DS.'category'.DS.'models');
        $catModel=&JModel::getInstance('Category','JTheFactoryModel');
        $cattree=$catModel->getCategoryTree();
        
        $pricing=$model->loadPricingObject();
        $params=new JParameter($pricing->params);
        
        
        $editor=&JFactory::getEditor();

        $view=$this->getView('Config');

        $view->assign('default_price',$r->default_price);
        $view->assign('price_powerseller',$r->price_powerseller);
        $view->assign('price_verified',$r->price_verified);
        $view->assign('category_pricing_enabled',$r->category_pricing_enabled);
        $view->assign('category_pricing',$r->category_pricing);
        $view->assign('category_tree',$cattree);
        $view->assign('itemname',$this->itemname);
        $view->assign('editor',$editor);
        $view->assign('email_text',base64_decode($params->get('email_text')));

        $view->display();
    }
    function save()
    {
        $d=JRequest::get('post',JREQUEST_ALLOWHTML);
        $model=&JModel::getInstance('Comission','J'.APP_PREFIX.'PricingModel');
        $model->saveItemPrices($d);

        $this->setRedirect('index.php?option='.APP_EXTENSION.'&task=pricing.config&item='.$this->itemname,JText::_('COM_RBIDS_SETTINGS_SAVED'));
    }
    function balance()
    {
        $filter=JRequest::getString('filter');
        
        JTheFactoryHelper::modelIncludePath('payments');
        $balancemodel=&JModel::getInstance('Balance','JTheFactoryModel');
        $model=&JModel::getInstance('Comission','J'.APP_PREFIX.'PricingModel');

        if ($filter=='negative')
            $balancemodel->set('filters','p.balance<0');
        $rows=$balancemodel->getBalancesList();
        foreach($rows as $row)
        {
            $row->lastpayment=$model->getLastPaymentDate($row->userid);
        }
        $filterbox=JHTML::_('select.genericlist',  
            array(
                JHTML::_('select.option', '', JText::_("COM_RBIDS__FILTER" )),
                JHTML::_('select.option', 'negative', JText::_("COM_RBIDS_JUST_NEGATIVE_BALANCES" )),
            )
            , 'filter', "onchange='this.form.submit()'",  'value', 'text', $filter);
        
        
        $view=$this->getView('Balance');
        $view->assign('filterbox',$filterbox);
        $view->assign('userbalances',$rows);
        $view->assign('pagination',$balancemodel->get('pagination'));
        $view->display();
     
    }
    function payments()
    {
        $model=&JModel::getInstance('Comission','J'.APP_PREFIX.'PricingModel');
        $payments=$model->getPaymentsList();

        $view=$this->getView('Payments');
        $view->assign('payments',$payments);
        $view->assign('pagination',$model->get('pagination'));
        $view->display();
        
    }
    function notices()
    {
        JTheFactoryHelper::modelIncludePath('payments');
        $balancemodel=&JModel::getInstance('Balance','JTheFactoryModel');
        $balancemodel->set('filters','p.balance<0');
        $rows=$balancemodel->getBalancesList();

        $view=$this->getView('Notices');
        $view->assign('userbalances',$rows);
        $view->assign('pagination',$balancemodel->get('pagination'));
        $view->display();
        
    }
    function sendnotice()
    {
        $userid=JRequest::getInt('userid'); //If null or 0 - ALL USERS
        $model=&JModel::getInstance('Comission','J'.APP_PREFIX.'PricingModel');
        $users=$model->getNegativeBalanceUsers($userid);
        
        foreach($users as $user)
        {
            $model->sendNotificationMail($user);
        }
        
        $this->setRedirect('index.php?option='.APP_EXTENSION.'&task=pricing.notices&item=comission',count($users)." ".JText::_('COM_RBIDS_NOTIFICATIONS_SENT'));
        
    }
    function auctions()
    {
        $model=&JModel::getInstance('Comission','J'.APP_PREFIX.'PricingModel');
        $auctions=$model->getAuctionComissions();

        $view=$this->getView('Auctions');
        $view->assign('auctions',$auctions);
        $view->assign('pagination',$model->get('pagination'));
        $view->display();
    }
    function cancel()
    {
        $this->setRedirect('index.php?option='.APP_EXTENSION.'&task=pricing.listing');//redirect to main listing
        
    }
}
