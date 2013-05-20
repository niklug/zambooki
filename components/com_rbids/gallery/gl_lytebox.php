<?php
	/**------------------------------------------------------------------------
	com_rbids - Reverse Auction Factory 3.0.0
	------------------------------------------------------------------------
	 * @author    TheFactory
	 * @copyright Copyright (C) 2011 SKEPSIS Consult SRL. All Rights Reserved.
	 * @license   - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
	 *            Websites: http://www.thefactory.ro
	 *            Technical Support: Forum - http://www.thefactory.ro/joomla-forum/
	 * @build: 01/04/2012
	 * @package   : RBids
	 * @subpackage: Gallery
	-------------------------------------------------------------------------*/

	defined('_JEXEC') or die('Restricted access');

	require_once(dirname(__FILE__) . DS . "gallery.php");

	class gl_lytebox extends TheFactoryGalleryObject
	{
		function writeJS()
		{
			$jsUrl = JURI::root() . 'components/com_rbids/gallery/js';
			$doc = & JFactory::getDocument();
			$doc->addScript($jsUrl . "/jquery.js");
			$doc->addScript($jsUrl . "/jquery.jcarousel.js");
			$doc->addStyleSheet($jsUrl . "/jquery.jcarousel.css");
			$doc->addStyleSheet($jsUrl . "/skin.css");

			$doc->addStyleDeclaration("
			            .jcarousel-skin-tango.jcarousel-container-horizontal {
			                width: " . $this->medium_width . "px;
			                padding: 20px 40px;
			            }
			            .jcarousel-skin-tango .jcarousel-clip-horizontal {
			                width:  " . $this->medium_width . "px;
			                height: " . $this->medium_height . "px;
			            }
			           .jcarousel-item {
			                width: " . $this->medium_width . "px;
			                height: " . $this->medium_height . "px;
			            }
			            .jcarousel-container {
					width: " . $this->medium_width . "px;
			            }
			        ");
			$doc->addScriptDeclaration("
			            if(typeof window.jQuery != 'undefined') {
			                jQuery.noConflict();
			            }
		        ");
			JHTML::_('behavior.modal');
		}

		public function getGallery()
		{
			$img = "";
			if (count($this->imagelist) > 1) {
				$this->writeJS();
				$nr = count($this->imagelist);
				$doc = & JFactory::getDocument();
				$doc->addScriptDeclaration("
				                jQuery(document).ready(function() {
						                setTimeout(function() {
							                    jQuery('#mycarousel').jcarousel({
							                        size: $nr,
							                        scroll: 1,
							                        itemFallbackDimension: {$this->medium_width}
							                    });
							                });
								}, 2000);
				                jQuery.noConflict();
				            ");
				$img .= '<ul id="mycarousel" class="jcarousel-skin-tango" >';
				for ($i = 0; $i < count($this->imagelist); $i++) {
					$img .= '<li style="background-image:url(); list-style:none;width:' . $this->medium_width . 'px">'
						. '<a href="' . $this->imageUrl . '/' . $this->imagelist[$i] . '" class="modal">'
						. '<img src="' . $this->imageUrl . '/middle_' . $this->imagelist[$i] . '" border=0 />'
						. "</a></li>\n";
				}
				$img .= '</ul>';
			} elseif (count($this->imagelist) == 1) {
				JHTML::_('behavior.modal');
				$img = '<a href="' . $this->imageUrl . '/' . $this->imagelist[0] . '" class="modal">
                        <img src="' . $this->imageUrl . '/middle_' . $this->imagelist[0] . '" border=0 />
                    </a>';
			} else {
				$img = '<img src="' . $this->imageUrl . '/no_image.png" border="0" />';
			}
			return $img;
		}

	}
