<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'UNIQUE_VISITS_COUNTER'			=> array(
		1 => '<strong>%d</strong> visit',
		2 => '<strong>%d</strong> visits',
	),
	'UNIQUE_VISITS_COUNTER_HOUR'	=> array(
		1 => 'last hour',
		2 => 'last <strong>%d</strong> hours',
	),
	'UNIQUE_VISITS_COUNTER_VERSION_CHECK'				=> 'Unique Visits Counter Version Check',
	'UNIQUE_VISITS_COUNTER_AUTHOR'						=> 'Author',
	'UNIQUE_VISITS_COUNTER_ANNOUNCEMENT_TOPIC'			=> 'Release Announcement',
	'UNIQUE_VISITS_COUNTER_CURRENT_VERSION'				=> 'Current Version',
	'UNIQUE_VISITS_COUNTER_DOWNLOAD_LATEST'				=> 'Download Latest Version',
	'UNIQUE_VISITS_COUNTER_DOWNLOAD'					=> 'Download',
	'UNIQUE_VISITS_COUNTER_LATEST_VERSION'				=> 'Latest Version',
	'UNIQUE_VISITS_COUNTER_NOT_UP_TO_DATE'				=> '%s is not up to date',
	'UNIQUE_VISITS_COUNTER_RELEASE_ANNOUNCEMENT'		=> 'Announcement Topic',
	'UNIQUE_VISITS_COUNTER_UP_TO_DATE'					=> '%s is up to date',
	'UNIQUE_VISITS_COUNTER_SAVED'						=> '<strong>Unique Visits Counter settings saved</strong>',
	// ACP
	'UNIQUE_VISITS_COUNTER_ALLOW'						=> 'Enable unique visits counter',
	'UNIQUE_VISITS_COUNTER_ALLOW_EXPLAIN'				=> 'If this option is on No, the unique visits counter is completely disabled.',
	'UNIQUE_VISITS_COUNTER_TIME_VALUE'					=> 'Set time period',
	'UNIQUE_VISITS_COUNTER_TIME_VALUE_EXPLAIN'			=> 'Set time period for show visits.<br />Same time will be used for prune visits.<br />Default is 24 hours.',
	'UNIQUE_VISITS_COUNTER_HOURS'	=> array(
		1 => 'Hour',
		2 => 'Hours',
	),
));
