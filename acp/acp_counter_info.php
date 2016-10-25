<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\acp;

class acp_counter_info
{
	function module()
	{
		return array(
			'filename'	=> '\dmzx\counter\acp\acp_counter_module',
			'title'		=> 'ACP_VISITS_COUNTER_TITLE',
			'modes'		=> array(
				'settings'	=> array('title' => 'ACP_VISITS_COUNTER_SETTINGS', 'auth' => 'ext_dmzx/counter && acl_a_board', 'cat' => array('ACP_VISITS_COUNTER_TITLE')),
			),
		);
	}
}
