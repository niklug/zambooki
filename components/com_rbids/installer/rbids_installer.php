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


	class TheFactoryRBidsInstaller extends TheFactoryInstaller
	{

		public function askTemplateOverwrite()
		{
			ob_start();
			?>
                <table width = "100%">
                    <tr>
                        <td>
                            <h1>
                                The installation detected that you already had a previous installed version of Reverse Auction Factory.
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h2>
                                The previously existing Reverse Auctions Factory Template folder WAS NOT overwritten in order to preserve any changes you might have done. If you like to overwrite the
                                contents of the template folder please click the button below
                            </h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button style = "background-color:red;color:black;"
                                    onclick = "if(confirm('Are you sure that you want to overwrite your existing Reverse Auctions Factory templates?')) window.location='index.php?option=com_rbids&task=installtemplates'">
                                Overwrite Templates now!
                            </button>
                        </td>
                    </tr>
                </table>
		<?php

			return ob_get_clean();
		}
	}
