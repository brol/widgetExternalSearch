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

dcCore::app()->addBehavior('initWidgets',array('externalSearchWidget',
	'initWidgets'));

/**
@ingroup External Search
@brief Widget
*/
class externalSearchWidget
{
	/**
	widget
	@param	w	<b>object</b>	Widget
	*/
	public static function initWidgets($w)
	{
		$w->create('externalSearchWidget',__('External search engine'),
			array('externalSearchWidget','show'),
			null,
			__('External search engine form'));
		
		$w->externalSearchWidget->setting('title',__('Title (optional)').' :',__('Search'));
		
		$w->externalSearchWidget->setting('engine',__('Search engine:'),
			null,'combo',array(
				'Bing' => 'bing',
				'Google' => 'google',
				'Yahoo!' => 'yahoo',
				)
			);
		$w->externalSearchWidget->setting('homeonly',__('Display on:'),0,'combo',
			array(
				__('All pages') => 0,
				__('Home page only') => 1,
				__('Except on home page') => 2
				)
		);
    $w->externalSearchWidget->setting('content_only',__('Content only'),0,'check');
    $w->externalSearchWidget->setting('class',__('CSS class:'),'');
		$w->externalSearchWidget->setting('offline',__('Offline'),0,'check');
	}
	
	/**
	show widget
	@param	w	<b>object</b>	Widget
	@return	<b>string</b> XHTML
	*/
	public static function show($w)
	{

		if ($w->offline)
			return;

		if (($w->homeonly == 1 && dcCore::app()->url->type != 'default') ||
			($w->homeonly == 2 && dcCore::app()->url->type == 'default')) {
			return;
		}
				
		$url = preg_replace('/^(http([s]*)\:\/\/)/i','',
			dcCore::app()->blog->url);
		
		switch($w->engine)
		{
			case 'bing' :
				# add site:http://example.com/ to bing search
				$form =
					'<form method="post" action="'.dcCore::app()->blog->url.
						dcCore::app()->url->getBase('externalSearch').'">'.
					'<p><input type="text" size="10" maxlength="255" name="q" />'.
					form::hidden(array('engine'),$w->engine).
					' <input class="submit" type="submit" value="ok" /></p>'.
				'</form>';
				break;
			case 'google' :
				$form =
					'<form method="get" action="https://www.google.com/search">'.
					'<p><input type="text" size="10" maxlength="255" '.
					'name="q" />'.
					form::hidden(array('domains'),$url).
					form::hidden(array('sitesearch'),$url).
					' <input class="submit" type="submit" value="ok" /></p>'.
					'</form>';
				break;
			case 'yahoo' :
				$form =
					'<form method="get" action="https://search.yahoo.com/search">'.
					'<p><input type="text" size="10" maxlength="255" '.
					'name="p" />'.
					form::hidden(array('vs'),$url).
					' <input class="submit" type="submit" value="ok" /></p>'.
					'</form>';
				break;
			
			default :
				throw new Exception(__('invalid search engine'));
				break;
		}

		$res =
		($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '').
		$form;

		return $w->renderDiv($w->content_only,'externalSearch '.$w->class,'',$res);
	}
}