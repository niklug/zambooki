<?php
/**------------------------------------------------------------------------
thefactory - The Factory Class Library - v 2.0.0
------------------------------------------------------------------------
 * @author TheFactory
 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.thefactory.ro
 * Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
 * @build: 01/04/2012
 * @package: thefactory
 * @subpackage: installer
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class TheFactoryInstallerCBPluginHelper
{
    var $_sourcepath=null;
    function __construct($sourcepath)
    {
        $this->_sourcepath=$sourcepath;
    }
    function InstallCBPlugin($plugintitle,$tabtitle,$pluginname,$folder,$class)
    {
       $database = &JFactory::getDBO();
    
       $query = "SELECT id FROM #__comprofiler_plugin where element='$pluginname.plugin'";
       $database->setQuery($query);
       $plugid = $database->loadResult();
       
       if(!$plugid){
    		$query = "INSERT INTO #__comprofiler_plugin set
    			`name`='$plugintitle',
    			`element`='$pluginname.plugin',
    			`type`='user',
    			`folder`='$folder',
    			`ordering`=99,
    			`published`=1,
    			`iscore`=0
    		";
    		$database->setQuery( $query );
    		$database->query();
    	
    		$plugid=$database->insertid();
       }
       $query = "SELECT count(*) FROM #__comprofiler_tabs where pluginid='{$plugid}'";
       $database->setQuery($query);
       $tabs = $database->loadResult();
       if(!$tabs){
        	$query = "INSERT INTO #__comprofiler_tabs set
        		`title`='$tabtitle',
        		`ordering`=999,
        		`enabled`=1,
        		`pluginclass`='$class',
        		`pluginid`='{$plugid}',
        		`fields`=0,
        		`displaytype`='tab',
        		`position`='cb_tabmain'
        
        	";
        	$database->setQuery( $query );
        	$database->query();
       }
       JFolder::create(JPATH_ROOT.'/components/com_comprofiler/plugin/user/'.$folder);
       
        

       $source_file = $this->_sourcepath.DS."$pluginname.plugin.php";
       $destination_file = JPATH_ROOT."/components/com_comprofiler/plugin/user/$folder/$pluginname.plugin.php";
       JFile::copy($source_file,$destination_file);
    
       $source_file = $this->_sourcepath.DS."$pluginname.plugin.xml";
       $destination_file = JPATH_ROOT."/components/com_comprofiler/plugin/user/$folder/$pluginname.plugin.xml";
       JFile::copy($source_file,$destination_file);
       echo "<span>Installed Plugin ".$plugintitle."</span><br/>";
    }

}


?>
