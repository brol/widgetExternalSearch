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

dcCore::app()->url->register('externalSearch','externalSearch',
	'^externalSearch$',array('externalSearchDocument','page'));

require_once(dirname(__FILE__).'/_widgets.php');