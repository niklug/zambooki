<?php
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

class rbidsViewajax extends RBidsSmartyView
{
    function display($tmpl)
    {
        // get contractors for category
        $cat = JRequest::getVar('cat');
        $method = JRequest::getVar('method');
        if($method == 'choosencat') {
            echo $this->getContractorsForCategory($cat);
            die();
        }
        
        // get contractors for all categories
        $categories = $this->getAllCategories($cat);
        
        foreach ($categories as $categorie) {
            $categoriesContractors[$categorie->id] = $this->getContractorsForCategory($categorie->id);
            $categorieName[$categorie->id] = $categorie->catname;
            
        }
        $category_contractors = array_combine($categorieName, $categoriesContractors);
        arsort($category_contractors);
        $category_contractors = json_encode($category_contractors);
        echo $category_contractors;
        die();

        parent::display($tmpl);
    }
    
    public function getContractorsForCategory($cat) {
        if(!isset($cat)) {
            $cat = JRequest::getVar('cat');
        }
        if(!isset($cat)) die('no category');
        $long = JRequest::getVar('long');
        if(!isset($long)) die('no longitude');
        $lat = JRequest::getVar('lat');
        if(!isset($lat)) die('no latitude');
        
        $db = & JFactory::getDbo();
        $user = & JFactory::getUser();
        $query = "SELECT catname FROM #__rbid_categories WHERE id='{$cat}'";
        $db->setQuery($query);
        $catname = $db->loadResult();
        //get list of users following action category
        $query = "SELECT f.user_id as id, u.latitude, u.longitude FROM #__community_fields_values as f
            LEFT JOIN #__community_users as u ON f.user_id=u.userid
            WHERE f.field_id='19' 
            AND f.value LIKE'%$catname%'
            AND f.user_id <> '{$user->id}'";
        $db->setQuery($query);
        $watches = $db->loadObjectList();


        $c = 0;
        foreach ($watches as $watcher) {
            //if($watcher->user_id != '3100') {
            //    unset($watches[$c]);
            //}
            $distance = $this->distance($watcher->latitude, $watcher->longitude, $lat, $long);
            $serviceArea = $this->getServiceArea($watcher->id);
            if($serviceArea) {
                if(($distance > $serviceArea)) {
                    unset($watches[$c]);

                }
            }

            $c++;
        }
        
        return count($watches);
    }
    
    
    
    
    public function getServiceArea($user_id) {
        $db = & JFactory::getDbo();
        $query = "SELECT value FROM #__community_fields_values WHERE user_id='$user_id' AND field_id='24'";
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;
    }

    /** calculate distance between two places 
     * 
     * @param type $lat1
     * @param type $lng1
     * @param type $lat2
     * @param type $lng2
     * @param type $miles
     * @return type
     */
    public function distance($lat1, $lng1, $lat2, $lng2, $miles = true) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;

        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;

        return ($miles ? ($km * 0.621371192) : $km);
    }
    
    
    public function getAllCategories($cat) {
        $db = & JFactory::getDbo();
        $query = "SELECT id, catname  FROM #__rbid_categories WHERE parent='$cat'";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
        
    }


}
