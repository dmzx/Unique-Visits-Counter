<?php
/**
*
* @package phpBB Extension - Unique Visits Counter
* @copyright (c) 2016 dmzx - http://www.dmzx-web.net
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace dmzx\counter\controller;

class admin_controller
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\log\log_interface */
	protected $log;

	/** @var \phpbb\user */
	protected $user;

	/** @var ContainerBuilder */
	protected $phpbb_container;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 *
	 * @param \phpbb\config\config											$config
	 * @param \phpbb\template\template										$template
	 * @param \\phpbb\log\log_interface										$log
	 * @param \phpbb\user													$user
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface 	$phpbb_container
	 * @param \phpbb\request\request										$request
	 *
	 */
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\template\template $template,
		\phpbb\log\log_interface $log,
		\phpbb\user $user,
		$phpbb_container,
		\phpbb\request\request $request)
	{
		$this->config 			= $config;
		$this->template 		= $template;
		$this->log 				= $log;
		$this->user 			= $user;
		$this->phpbb_container 	= $phpbb_container;
		$this->request 			= $request;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		add_form_key('acp_counter');

		$this->version_check = $this->phpbb_container->get('dmzx.counter.version.check');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_counter'))
			{
				trigger_error('FORM_INVALID');
			}

			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'UNIQUE_VISITS_COUNTER_SAVED');

			trigger_error($this->user->lang('UNIQUE_VISITS_COUNTER_SAVED') . adm_back_link($this->u_action));
		}

		$this->template->assign_vars(array(
			'U_ACTION'								=> $this->u_action,
			'ALLOW_VISITS_COUNTER'					=> $this->config['allow_visits_counter'],
			'VISITS_COUNTER_GC'			 			=> $this->config['visits_counter_gc'] / 3600,
			'UNIQUE_VISITS_COUNTER_HOURS'			=> $this->user->lang('UNIQUE_VISITS_COUNTER_HOURS', $this->config['visits_counter_gc'] / 3600),

		));
		$this->version_check->check();
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('allow_visits_counter', $this->request->variable('allow_visits_counter', 1));
		$this->config->set('visits_counter_gc', $this->request->variable('visits_counter_gc', 0) * 3600);
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
