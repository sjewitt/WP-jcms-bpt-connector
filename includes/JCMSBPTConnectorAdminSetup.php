<?php
/*
 * Set up the admin page for the plugin.
 * */ 

class JCMSBPTConnectorAdminSetup{
	
	var $_opts = null;
	
    function __construct(){
        add_action('admin_menu',[$this,'jcms_bpt_connector_setup_menu']);
        add_action('admin_init',[$this,'jcms_bpt_connector_admin_init']);
        $_opts = get_option('jcms_bpt_connector_opts');
    }
    
    function jcms_bpt_connector_setup_menu(){      //CALLBACK
        add_options_page('JCMS BPT Connector setup','JCMS BPT Connector','manage_options','jcms-bpt-connector',[$this,'jcms_bpt_connector_init']);
    }
    
    function jcms_bpt_connector_init(){     //CALLBACK
        ?>
    <div class="wrap">
    <h2>Configure JCMS Brown Paper Tickets Connector</h2>
    <p>Please supply API key and authorised user.</p>
    <p><i>Advanced setting!</i> Please also set time to cache source data (in seconds). The default if no value provided is 3600 (1 hour). 
    <form action="/wp-admin/options.php" method="post">
    <?php settings_fields('jcms_bpt_connector_opts'); ?>
    <?php do_settings_sections('jcms-bpt-connector'); ?>
    <table class="form-table"> 
      <tr valign="top">
        <td colspan="2">
            <input name="Submit" type="submit" class="button button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
        </td>
      </tr>
    </table>
    </form>
    end form
    </div>
    <?php
    }
    
    function jcms_bpt_connector_admin_init(){     //CALLBACK
    	register_setting('jcms_bpt_connector_opts','jcms_bpt_connector_opts'); //no validate function yet
        add_settings_section('jcms_bpt_connector_s1', 'API Credentials', null, 'jcms-bpt-connector');
        add_settings_field('plugin_text_input1_1', 'Authorised User', [$this,'plugin_input1_1'], 'jcms-bpt-connector', 'jcms_bpt_connector_s1');
        add_settings_field('plugin_text_input1_2', 'API key', [$this,'plugin_input1_2'], 'jcms-bpt-connector', 'jcms_bpt_connector_s1');
        add_settings_field('plugin_text_input1_3', 'Source Cache time', [$this,'plugin_input1_3'], 'jcms-bpt-connector', 'jcms_bpt_connector_s1');
    }
    
    /*
     * Note - for these, the FIRST time they are used, there is no key stored against any of the options internally to WP.
     * Conseuently, I am getting a non-fatal error 'Undefined index...' echoed. Therefore, we need to test for this in the code, so the
     * user does not see an ugly error message:
     *  
     */
    function validateAndGetOptionValue($_optionsName,$_optionsIndex,$_optionsKey){
    	$_out = "";
    	try{
    		//test for key in current index of opts:
    		$_opts = get_option($_optionsName);
    		if(array_key_exists($_optionsKey,$_opts[$_optionsIndex])){
    			$_out = $_opts[$_optionsIndex][$_optionsKey];
    		}
    		
    	}
    	catch(Throwable $_err){
    		//don't do anything, just return empty string
    		$_out = "";
    	}
    	finally{
    		return($_out);
    	}
    		
    }
    
    //callbacks for section 1:
    function plugin_input1_1() {
    	$_val = $this->validateAndGetOptionValue('jcms_bpt_connector_opts',1,'name');
        echo "<input id='plugin_input1_1' class='normal-text code' name='jcms_bpt_connector_opts[1][name]' size='50' type='text' value='{$_val}' />";
    }
    function plugin_input1_2() {
    	$_val = $this->validateAndGetOptionValue('jcms_bpt_connector_opts',1,'key');
        echo "<input id='plugin_input1_2' class='normal-text code' name='jcms_bpt_connector_opts[1][key]' size='50' type='text' value='{$_val}' />";
    }
    function plugin_input1_3() {
    	$_val = $this->validateAndGetOptionValue('jcms_bpt_connector_opts',1,'cache');
    	echo "<input id='plugin_input1_3' class='normal-text code' name='jcms_bpt_connector_opts[1][cache]' size='50' type='text' value='{$_val}' />";
    }
	
    //validation code not added yet:
    function plugin_options_validate($input) {
            $options = get_option('jcms_bpt_connector_opts');
            return $options;
    }
}
