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

jimport('joomla.application.component.model');
jimport('joomla.html.parameter');

class JRBidPricingModelComission extends JModel
{
    var $description="Commission for Auctions";
    var $name='comission';
    var $context='comission';
	function __construct()
	{
        $this->context=APP_EXTENSION."_comission.";
        $this->tablename='#__'.APP_PREFIX.'_pricing_comissions';
        parent::__construct();
    }    
    function loadPricingObject()
    {
        $db=&$this->getDbo();
        $db->setQuery("select * from `#__".APP_PREFIX."_pricing` where `itemname`='".$this->name."'");
        return $db->loadObject();
    }
    function getItemPrices()
    {
        $db=&$this->getDbo();
        $r=$this->loadPricingObject();
        $params=new JParameter($r->params);

        $res=new stdClass();
        $res->default_price=$r->price;
        $res->default_currency=$r->currency;
        $res->price_powerseller=$params->get('price_powerseller');
        $res->price_verified=$params->get('price_verified');
        $res->category_pricing_enabled=$params->get('category_pricing_enabled');

        $db->setQuery("select category,price from `#__".APP_PREFIX."_pricing_categories` where `itemname`='".$this->name."'");
        $res->category_pricing=$db->loadAssocList('category');


        return $res;
    }
    function getItemPrice($category)
    {
        $userprofile=RBidsHelperTools::getUserProfileObject();
        $userprofile->getUserProfile();

        $r=$this->loadPricingObject();
        $params=new JParameter($r->params);
        if ($userprofile->powerseller)
            $defaultprice=$params->get('price_powerseller',$r->price);
        elseif($userprofile->verified)
            $defaultprice=$params->get('price_verified',$r->price);
        else
            $defaultprice=$r->price;
        $db=&$this->getDbo();
        $db->setQuery("select price from `#__".APP_PREFIX."_pricing_categories` where `itemname`='".$this->name."' and category='$category'");
        $price=$db->loadResult();
        $res=new stdClass();
        $res->price=($price===NULL)?$defaultprice:$price;
        $res->currency=$r->currency;
        return $res;
    }
    function saveItemPrices($d)
    {
        $db=&$this->getDbo();

        $params=new JParameter();
        $params->set('price_powerseller',JArrayHelper::getValue($d,'price_powerseller'));
        $params->set('price_verified',JArrayHelper::getValue($d,'price_verified'));
        $params->set('category_pricing_enabled',JArrayHelper::getValue($d,'category_pricing_enabled'));
        $email_text=JArrayHelper::getValue($d,'email_text');
        $email_text=base64_encode($email_text);
        $params->set('email_text',$email_text);
        
        $p=$params->toString('INI');

        $price=JArrayHelper::getValue($d,'default_price'); 
        $currency=JArrayHelper::getValue($d,'currency');

        $db->setQuery("update `#__".APP_PREFIX."_pricing`
            set `price`='$price',`currency`='$currency',
            `params`='$p'
            where `itemname`='{$this->name}'");
        $db->query();

        $db->setQuery("delete from `#__".APP_PREFIX."_pricing_categories` where `itemname`='".$this->name."'");
        $db->query();

        $category_pricing=JArrayHelper::getValue($d,'category_pricing',array(),'array');
        foreach($category_pricing as $k=>$v)
        if (!empty($v)||($v==='0'))
        {
            $db->setQuery("insert into `#__".APP_PREFIX."_pricing_categories` (`category`,`price`,`itemname`) values ('$k','$v','".$this->name."')");
            $db->query();

        }

    }
    function getLastPaymentDate($userid=null)
    {
        if(!$userid) {
            $user=&JFactory::getUser();
            $userid=$user->id;
        }
        $db=&JFactory::getDbo();
        $db->setQuery("select max(orderdate) from `#__".APP_PREFIX."_payment_orders` o
                    left join `#__".APP_PREFIX."_payment_orderitems` oi on oi.orderid=o.id
                    where (oi.itemname='Balance' or oi.itemname='{$this->name}') and o.userid='$userid'
        ");
        return $db->loadResult();
        
    }
    function getPaymentsList()
    {
        $db=&$this->getDbo();
        $app=&JFactory::getApplication();

        $limit=$app->getUserStateFromRequest($this->context."limit" , 'limit',$app->getCfg('list_limit') );
        $limitstart=$app->getUserStateFromRequest($this->context."limitstart" , 'limitstart',0);

        jimport('joomla.html.pagination');

        $db->setQuery("select count(*) from `#__".APP_PREFIX."_payment_orderitems` oi 
                    left join `#__".APP_PREFIX."_payment_orders` o on oi.orderid=o.id
                    where (oi.itemname='Balance' or oi.itemname='{$this->name}') and o.status='C' 
        ");
        $this->pagination=new JPagination($db->loadResult(), $limitstart, $limit);

        $db->setQuery("select oi.*,o.orderdate,o.userid,u.username from `#__".APP_PREFIX."_payment_orderitems` oi 
                        left join `#__".APP_PREFIX."_payment_orders` o on oi.orderid=o.id
                        left join `#__users` u on u.id=o.userid
                        where (oi.itemname='Balance' or oi.itemname='{$this->name}') and o.status='C' 
                        order by o.orderdate ",$limitstart,$limit);

        return $db->loadObjectList();
    }
    function getAuctionComissions()
    {
        $db=&$this->getDbo();
        $app=&JFactory::getApplication();

        $limit=$app->getUserStateFromRequest($this->context."limit" , 'limit',$app->getCfg('list_limit') );
        $limitstart=$app->getUserStateFromRequest($this->context."limitstart" , 'limitstart',0);

        jimport('joomla.html.pagination');
        $where="";
        if($this->get('filters')) $where="where ".$this->get('filters');

        $db->setQuery("select count(*) from `{$this->tablename}` c
                        left join `#__rbid_auctions` a on a.id=c.auction_id
                        left join `#__rbids` b on b.id=c.bid_id
                        $where
                        ");
        $this->pagination=new JPagination($db->loadResult(), $limitstart, $limit);

        $db->setQuery("SELECT `c`.`auction_id`,
					 `c`.`comission_date`,
					 `c`.`amount`,
					 `c`.`currency`,
					 `a`.`title`,
					 `b`.`bid_price`
				  FROM `{$this->tablename}` AS `c`
				  LEFT JOIN `#__rbid_auctions` AS `a` ON `a`.`id` = `c`.`auction_id`
				  LEFT JOIN `#__rbids` AS `b` ON `b`.`id` = `c`.`bid_id`
                        {$where}
                        ORDER BY `c`.`comission_date` ", $limitstart, $limit);

        return $db->loadObjectList();
    }
    function getNegativeBalanceUsers($userid=null)
    {
        if ($userid) $w=" and u.id='$userid'";
        else $w='';
        
        $db=&JFactory::getDbo();
        $db->setQuery("select * from `#__".APP_PREFIX."_payment_balance` b
                        left join `#__users` u on u.id=b.userid
                    where balance<0 $w
        ");
        return $db->loadObjectList();
    }
    function sendNotificationMail($user)
    {
		$config = &JFactory::getConfig();
		$mail_from = $config->getValue("mailfrom");
		$sitename = $config->getValue("sitename");

        JTheFactoryHelper::modelIncludePath('payments');
        $balancemodel=&JModel::getInstance('Balance','JTheFactoryModel');
        $balance=$balancemodel->getUserBalance($user->id);
        
        $r=$this->loadPricingObject();
        $params=new JParameter($r->params);
        $email_text=base64_decode($params->get('email_text'));
        
        $link=JURI::root().'/index.php?option='.APP_EXTENSION.'&task=paycomission';
        
        $email_text=str_replace('%USERNAME%',$user->username,$email_text);
        $email_text=str_replace('%NAME%',$user->name,$email_text);
        $email_text=str_replace('%BALANCE%',$balance->balance." ".$balance->currency,$email_text);
        $email_text=str_replace('%LINK%',$link,$email_text);

        JUTility::sendMail($mail_from, $sitename, $user->email, JText::_("COM_RBIDS_COMISION_PAYMENT_NOTIFICATION") , $email_text, true);
        
    }
    function getOderItem($auction,$winningbid)
    {
        $price=$this->getItemPrice($auction->cat);
        $item=new stdClass();
        $item->itemname=$this->name;
        $item->itemdetails=JText::_($this->description);
        $item->iteminfo=$auction->id;
        $item->price=$winningbid->bid_price*$price->price/100;
        $item->currency=$auction->currency;
        $item->quantity=1;
        $item->params='';
        return $item;
    }
    function getOderItemFromBalance($balance)
    {
        $price=$this->getItemPrice();
        $item=new stdClass();
        $item->itemname=$this->name;
        $item->itemdetails=JText::_($this->description);
        $item->iteminfo=$balance->id;
        $item->price=-$balance->balance;
        $item->currency=$balance->currency;
        $item->quantity=1;
        $item->params='';
        return $item;
    }
    
}
