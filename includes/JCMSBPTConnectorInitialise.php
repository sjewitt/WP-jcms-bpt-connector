<?php
/*
 * wordpress.stackexchange.com/questions/61437
 */
include "JCMSBPTConnectorActions.php";
include "JCMSBPTConnectorAdminSetup.php";    //functions to set up the settings page

class JCMSBPTConnectorInitialise{

    var $_connector = null;
    function __construct(){
        add_shortcode('jcms_bpt_events', [$this,'jcms_bpt_connector_get_events']);
        $admin = new JCMSBPTConnectorAdminSetup();
        $this->_connector = new JCMSBPTConnectorActions();
    }
    
    /*
     * Handle parsing of shortcode values:
     * Get BPT events:
     * Check for shortcode modifiers. Valid options are:
     * 
     * id = ID of a specific event
     * status = 'pending', 'active', 'expired' - this TODO
     *  - possibly something to control the amount of detail?
     * */
    public function jcms_bpt_connector_get_events($atts){    //shortcode params
        $_out = '';
//         $_connector = new JCMSBPTConnectorActions();

        if(is_array($atts)){

        	if(array_key_exists('id',$atts)){
            	$_out = $this->_connector->getEventById($atts['id'],null);
         	}
         	if(array_key_exists('status',$atts)){
         		$_out = "we have a status flag (default is live=y)...";
        	}
        }
        else{
        	$_out = $this->_connector->getEventList();
        }
        return $_out;
    }
}

