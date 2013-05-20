<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build     : 01/04/2012
	 * @package   : RBids
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	class com_rbidsInstallerScript
	{
		var $installer = null;

		public function install($adapter)
		{

		}

		public function update($adapter)
		{
			$installer = $this->installer;

			$installer->AddMessage("<h1>Your upgrade from Reverse Auction Factory v. " . $installer->versionprevious . " to v. " . $installer->version . " has finished</h1>");
			$installer->AddMessage($installer->askTemplateOverwrite());
		}

		public function uninstall($adapter)
		{

		}

		public function preflight($route, $adapter)
		{
			require_once($adapter->getParent()->getPath('source') . DS . 'administrator' . DS . 'components' . DS . 'com_rbids' . DS . 'thefactory' . DS . 'installer' . DS . 'installer.php');
			require_once($adapter->getParent()->getPath('source') . DS . 'components' . DS . 'com_rbids' . DS . 'installer' . DS . 'rbids_installer.php');
			$this->installer = new TheFactoryRBidsInstaller('com_rbids', $adapter);

			if (!$this->installer->versionprevious) { //new install
				$this->installer->AddMenuItem("Reverse Auction Menu", "Auctions", "listauctions", "index.php?option=com_rbids&task=listauctions", 1);
				$this->installer->AddMenuItem("Reverse Auction Menu", "Categories", "categories", "index.php?option=com_rbids&task=categories", 1);
				$this->installer->AddMenuItem("Reverse Auction Menu", "Auctions on map", "map-offers", "index.php?option=com_rbids&task=googlemaps&controller=maps", 1);
				$this->installer->AddMenuItem("Reverse Auction Menu", "Search", "search-auctions", "index.php?option=com_rbids&task=show_search", 1);
				$this->installer->AddMenuItem("Reverse Auction Menu", "New Offer", "post-offer", "index.php?option=com_rbids&task=form", 2);
				$this->installer->AddMenuItem("Reverse Auction Menu", "My Auctions", "my-auctions", "index.php?option=com_rbids&task=myauctions", 2);
				$this->installer->AddMenuItem("Reverse Auction Menu", "Watchlist", "my-watchlist", "index.php?option=com_rbids&controller=watchlist&task=watchlist", 2);
				$this->installer->AddMenuItem("Reverse Auction Menu", "My Bids", "my-bids", "index.php?option=com_rbids&task=mybids", 2);
				$this->installer->AddMenuItem("Reverse Auction Menu", "Profile", "auctioneer-profile", "index.php?option=com_rbids&task=userdetails&controller=user", 2);

				$this->installer->AddSQLFromFile('install.rbids.inserts.sql');
			}

			$this->installer->AddCBPlugin('Reverse Auction Factory - My Auctions', 'My Auctions', 'rauction_my_auctions', 'getmyrauctionsTab');
			$this->installer->AddCBPlugin('Reverse Auction Factory - My Bids', 'My Bids', 'rauction_my_rbids', 'getmyrbidsTab');
			$this->installer->AddCBPlugin('Reverse Auction Factory - My Ratings', 'My Ratings', 'rauction_my_ratings', 'getmyrratingsTab');
			$this->installer->AddCBPlugin('Reverse Auction Factory - My Watchlist', 'My Watchlist', 'rauction_my_watchlist', 'getmyrwatchlistTab');
			$this->installer->AddCBPlugin('Reverse Auction Factory - My Won Bids', 'My Won Bids', 'rauction_my_wonbids', 'getmywonrbidsTab');
			$this->installer->AddCBPlugin('Reverse Auction Factory - My TaskPad', 'Auction Taskpad', 'rauction_my_taskpad', 'myrTaskPad');
			$this->installer->AddCBPlugin('Reverse Auction Factory - Google Map', 'Google Map', 'rauction_googlemap', 'getrmymap');

			$this->installer->AddMessageFromFile('install.notes.txt');

			$this->installer->AddMessage("Thank you for purchasing <strong>Reverse Auctions Factory</strong>");
			$this->installer->AddMessage("Please set up your <strong>Reverse Auctions Factory</strong> in the <a href='" . JURI::root() . "/administrator/index.php?option=com_rbids&task=settings'>admin panel</a></p>");
			$this->installer->AddMessage("Visit us at <a target='_blank' href='http://www.thefactory.ro'>thefactory.ro</a> to learn  about new versions and/or to give us feedback<br>");
			$this->installer->AddMessage("(c) 2006 - " . date('Y') . " thefactory.ro");


		}

		public function postflight($route, $adapter)
		{

			if ($this->installer->versionprevious) {
				$this->installer->upgrade();
				//$adapter->getParent()->set('redirect_url', "index.php?option=com_rbids&task=postupgrade");
			} else {
				$this->installer->install();
				JFolder::move(JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'templates-dist',
					JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'templates');
				JFile::copy(
					JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'options-dist.php',
					JPATH_SITE . DS . 'components' . DS . 'com_rbids' . DS . 'options.php'
				);

			}

			if (!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'com_rbids' . DS . 'files'))
				JFolder::create(JPATH_SITE . DS . 'media' . DS . 'com_rbids' . DS . 'files');
			if (!JFolder::exists(JPATH_SITE . DS . 'media' . DS . 'com_rbids' . DS . 'images'))
				JFolder::create(JPATH_SITE . DS . 'media' . DS . 'com_rbids' . DS . 'images');
			if (!JFolder::exists(JPATH_ROOT . DS . 'cache' . DS . 'com_rbids' . DS . 'templates'))
				JFolder::create(JPATH_ROOT . DS . 'cache' . DS . 'com_rbids' . DS . 'templates');

			$message = is_array($this->installer->extension_message) ? implode("\r\n", $this->installer->extension_message) : $this->installer->extension_message;
			$error = is_array($this->installer->errors) ? implode("<br/>", $this->installer->errors) : $this->installer->errors;
			$warning = is_array($this->installer->warnings) ? implode("<br/>", $this->installer->warnings) : $this->installer->warnings;

			if ($error) JError::raiseWarning(100, $error);
			if ($warning) JError::raiseNotice(1, $warning);
			$adapter->getParent()->set('extension_message', $message);

			$session = JFactory::getSession();
			$session->set('com_rbids_install_msg', $message);

		}
	}
