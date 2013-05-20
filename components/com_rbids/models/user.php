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

	class rbidsModelUser extends rbidsModelGeneric
	{
		var $_name = 'User';
		var $name = 'User';
		var $page = 'user_profile';
		var $context = 'userModel';
		var $knownFilters = array(
			'keyword' => array('type' => 'string'),
			'country' => array('type' => 'string'),
			'city' => array('type' => 'string')
		);

		/**
		 * getUserData
		 *
		 * @param $id
		 *
		 * @return JTheFactoryUserProfile
		 */
		public function getUserData($id)
		{
			$user = RBidsHelperTools::getUserProfileObject();
			$user->getUserProfile($id);
			return clone $user;
		}

		/**
		 * saveUserDetails
		 *
		 * @return mixed
		 */
		public function saveUserDetails()
		{

			$my =& JFactory::getUser();
			$db =& $this->getDbo();
			$user =& JTable::getInstance('users', 'Table');

			if (!$user->load($my->id)) {
				$db->setQuery("insert into " . $user->getTableName() . "(`userid`) values ({$my->id})");
				$db->query();
			}
			$user->bind($_POST);

			$user->activity_domains = '';
			if (isset($_POST['activity_domains'])) {
				$user->activity_domains = implode(',', $_POST['activity_domains']);
			}


			$user->paypalemail_is_visible = 0;
			$user->YM_is_visible = 0;
			$user->Hotmail_is_visible = 0;
			$user->Skype_is_visible = 0;
			$user->Linkedin_is_visible = 0;
			$user->Facebook_is_visible = 0;

			if (isset($_POST['paypalemail_is_visible'])) {
				$user->paypalemail_is_visible = 1;
			}
			if (isset($_POST['YM_is_visible'])) {
				$user->YM_is_visible = 1;
			}
			if (isset($_POST['Hotmail_is_visible'])) {
				$user->Hotmail_is_visible = 1;
			}
			if (isset($_POST['Skype_is_visible'])) {
				$user->Skype_is_visible = 1;
			}
			if (isset($_POST['Linkedin_is_visible'])) {
				$user->Linkedin_is_visible = 1;
			}
			if (isset($_POST['Facebook_is_visible'])) {
				$user->Facebook_is_visible = 1;
			}

			$user->userid = $my->id;
			$date =& JFactory::getDate();
			$user->modified = $date->toSQL();

			return $user->store();
		}

		/**
		 * getUserCountries
		 *
		 * @return mixed
		 */
		public function getUserCountries()
		{
			$db =& $this->getDbo();
			$query = JTheFactoryDatabase::getQuery();

			$profile = rBidsHelperTools::getUserProfileObject();

			$field = $profile->getFilterField('country');
			$table = $profile->getFilterTable('country');


			$query->select("distinct `{$field}` country");
			$query->from($table);
			$query->where("`{$field}`<>'' and `{$field}` is not null");

			$db->setQuery((string)$query);

			return $db->loadObjectList();

		}

		/**
		 * buildQuery
		 *
		 * @return JTheFactoryDatabaseQuery
		 */
		public function buildQuery()
		{
			$db = &$this->getDbo();

			$query = JTheFactoryDatabase::getQuery();
			$query->select('`u`.*,`u`.`id` AS userid');
			$query->select('null AS password');
			$query->select('AVG(r.rating) AS rating_overall');
			$query->select('COUNT(DISTINCT au.id) AS nr_auctions');
			$query->select('COUNT(DISTINCT bi.id) AS nr_bids');

			$query->from('#__users', 'u');
			$query->join('left', '#__rbid_rate', 'r', '`u`.`id`=`r`.`user_rated`');
			$query->join('left', '#__rbid_auctions', 'au', '`u`.`id`=`au`.`userid` and au.`published`=1');
			$query->join('left', '#__rbids', 'bi', '`u`.`id`=`bi`.`userid`');

			$profile = RBidsHelperTools::getUserProfileObject();

			$this->buildCustomQuery($query, $profile, null, $profile->getProfileFields());

			$queriedTables = $query->getQueriedTables();

			//all the filters are profile related; in order to work, we have to append them to the query AFTER the join has been made with the profile tables, in parent::buildCustomQuery()
			$keyword = $this->getState('filters.keyword');
			$name = $this->getState('filters.name');
			$k = $keyword ? $keyword : ($name ? $name : null);
			if ($k) {
				$s = array();

				$table = $profile->getFilterTable('username');
				$field = $profile->getFilterField('username');
				$alias = array_search($table, $queriedTables);
				$s[] = ' (`' . $alias . '`.`' . $field . '` LIKE \'%' . $db->escape($k) . '%\') ';

				$table = $profile->getFilterTable('name');
				$field = $profile->getFilterField('name');
				$alias = array_search($table, $queriedTables);
				$s[] = ' (`' . $alias . '`.`' . $field . '` LIKE \'%' . $db->escape($k) . '%\') ';

				$table = $profile->getFilterTable('surname');
				$field = $profile->getFilterField('surname');

				$alias = array_search($table, $queriedTables);
				$s[] = ' (`' . $alias . '`.`' . $field . '` LIKE \'%' . $db->escape($k) . '%\') ';

				$table = $profile->getFilterTable('about_me');
				$field = $profile->getFilterField('about_me');

				$alias = array_search($table, $queriedTables);
				$s[] = ' (`' . $alias . '`.`' . $field . '` LIKE \'%' . $db->escape($k) . '%\') ';

				$table = $profile->getFilterTable('activity_domains');
				$field = $profile->getFilterField('activity_domains');

				$alias = array_search($table, $queriedTables);
				$s[] = ' (`' . $alias . '`.`' . $field . '` LIKE \'%' . $db->escape($k) . '%\') ';

				$query->where($s, 'OR');
			}
			if ($this->getState('filters.country')) {
				$table = $profile->getFilterTable('country');
				$field = $profile->getFilterField('country');
				;
				$alias = array_search($table, $queriedTables);
				$query->where(' (`' . $alias . '`.`' . $field . '` =\'' . $db->escape($this->getState('filters.country')) . '\') ');
			}

			if ($this->getState('filters.city')) {
				$table = $profile->getFilterTable('city');
				$field = $profile->getFilterField('city');
				;
				$alias = array_search($table, $queriedTables);
				$query->where(' (`' . $alias . '`.`' . $field . '` =\'' . $db->escape($this->getState('filters.city')) . '\') ');
			}

			$filter_order = $this->getState('filters.filter_order');
			if ($filter_order) {
				$filter_order_Dir = $this->getState('filters.filter_order_Dir');
				$query->order($db->escape($filter_order . ' ' . $filter_order_Dir));
			}

			$query->group('`u`.`id`');

			return $query;
		}

		/**
		 * getTotal
		 *
		 * @return mixed|null
		 */
		public function getTotal()
		{
			if (empty($this->total)) {
				$db =& $this->getDbo();
				$query = $this->buildQuery();
				$query->set('select', array("count(distinct `u`.id)"));
				$query->set('order', null);
				$query->set('group', null);
				$db->setQuery((string)$query);
				$this->total = $db->loadResult();
			}
			return $this->total;
		}
	} //End Class
