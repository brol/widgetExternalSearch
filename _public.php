<?php
/**
 * @brief External Search, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Moe, Pierre Van Glabeke and contributors
 *
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('DC_RC_PATH')) {return;}

/**
@ingroup External Search
@brief Document
*/
class externalSearchDocument extends dcUrlHandlers
{
	/**
	serve the document
	@param	args	<b>string</b>	Arguments
	*/
	public static function page($args)
	{
		$url = preg_replace('/^(http([s]*)\:\/\/)/i','',
			dcCore::app()->blog->url);
		
		$q = !empty($_POST['q']) ? $_POST['q'] : '';
		$q = urlencode($q);
		
		switch($_POST['engine'])
		{
			case 'bing' :
				$url = 'https://www.bing.com/search?q=site:'.$url.' '.$q;
				break;
			default :
				throw new Exception(__('invalid search engine'));
				break;
		}
		
		http::redirect($url);
	}
}