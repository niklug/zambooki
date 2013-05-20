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
-------------------------------------------------------------------------*/ 

defined('_JEXEC') or die('Restricted access');

class TableTags extends JTable
{
    
  var $id;
  var $auction_id;
  var $tagname;
  
	function __construct(&$db)
	{
		parent::__construct( '#__rbid_tags', 'id', $db );
	}
    
    function setTags($parent_id,$tags){

        $cfg=&JTheFactoryHelper::getConfig();

        $db=$this->getDbo();
        $db->setQuery("DELETE FROM ".$this->_tbl." WHERE auction_id='$parent_id'");
        $db->query();

		
        $tag_arr=explode(',',$tags);

        for ($i=0; $i<min(count($tag_arr),$cfg->max_nr_tags); $i++){
			
        	
            $this->id=null;
            $this->auction_id=$parent_id;
            $this->tagname=trim($tag_arr[$i]);
            if( $this->tagname !="")
            	$this->store();
        }
        
    }
    function getTagsAsArray($parent_id)
    {
        $db=$this->getDbo();
    	$db->setQuery("SELECT tagname FROM ".$this->_tbl." WHERE auction_id='".$parent_id."' ORDER BY id");
    	$tmp = $db->loadObjectList("tagname");
		$tagList = array();
		if($tmp)
    	foreach ( $tmp as $key=> $value)
    		$tagList[] = $key;
		return $tagList;
    }
    function getTagsAsString($parent_id)
    {
        $arr=self::getTagsAsArray($parent_id);
        return implode(",", $arr);
    }

 
}

?>
