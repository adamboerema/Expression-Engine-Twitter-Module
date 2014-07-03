<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */
 
// ------------------------------------------------------------------------

/**
 * Prpl_Twitter Module Install/Update File
 *
 * @package		ExpressionEngine
 * @subpackage	Addons
 * @category	Module
 * @author		Adam Boerema
 * @link		http://purplerockscissors.com
 */

class Prpl_twitter_upd {
	
	public $version = '1.0';
	
	private $EE;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
        $this->EE =& get_instance();
        ee()->load->dbforge();
	}
	
	// ----------------------------------------------------------------
	
	/**
	 * Installation Method
	 *
	 * @return 	boolean 	TRUE
	 */
	public function install()
	{
		//Build the schema the twitter table
        ee()->dbforge->drop_table('prpl_twitter');
        ee()->dbforge->add_field('id');
        ee()->dbforge->add_field(array(
            'user_id' => array(
                'type'			=> 'VARCHAR',
                'constraint'	=> 64,
                'null'			=> TRUE,
                'default'		=> 0
            ),
            'user_name' => array(
                'type'			=> 'VARCHAR',
                'constraint'	=> 64,
                'null'			=> TRUE,
                'default'		=> 0
            ),
            'tags' => array(
                'type'			=> 'VARCHAR',
                'constraint'	=> 64,
                'null'			=> TRUE,
                'default'		=> 0
            ),
            'tweet' => array(
                'type'			=> 'VARCHAR',
                'constraint'	=> 140,
                'null'			=> TRUE,
                'default'		=> 0
            ),
            'tweet_time' => array(
                'type'			=> 'INT',
                'constraint'	=> 32,
                'null'			=> TRUE,
                'default'		=> 0,
                'unsigned'      => TRUE,
            ),
            'database_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ));

        //Add the table
        ee()->dbforge->create_table('prpl_twitter');

		$mod_data = array(
			'module_name'			=> 'Prpl_twitter',
			'module_version'		=> $this->version,
			'has_cp_backend'		=> "n",
			'has_publish_fields'	=> 'n'
		);
		$this->EE->db->insert('modules', $mod_data);


        //Actions
        $data = array(
            'class'     => 'Prpl_twitter' ,
            'method'    => 'cron'
        );
        ee()->db->insert('actions', $data);

		
		return TRUE;
	}

	// ----------------------------------------------------------------
	
	/**
	 * Uninstall
	 *
	 * @return 	boolean 	TRUE
	 */	
	public function uninstall()
	{
		ee()->db->select('module_id');
        $query = ee()->db->get_where('modules', array('module_name' => 'Prpl_Twitter'));
        $module_id_row = $query->row();
        $module_id = $module_id_row->module_id;

        ee()->db->where('module_id', $module_id);
        ee()->db->delete('module_member_groups');

        ee()->db->where('module_name', 'Prpl_Twitter');
        ee()->db->delete('modules');

        ee()->db->where('class', 'Prpl_Twitter');
        ee()->db->delete('actions');

        ee()->db->where('class', 'Prpl_Twitter');
        ee()->db->delete('actions');

        ee()->dbforge->drop_table('prpl_twitter');

        return TRUE;
	}
	
	// ----------------------------------------------------------------

    /**
     * @param string $current
     * @return bool
     */
    public function update($current = '')
	{
		return FALSE;
	}
	
}
/* End of file upd.prpl_twitter.php */
/* Location: /system/expressionengine/third_party/prpl_twitter/upd.prpl_twitter.php */