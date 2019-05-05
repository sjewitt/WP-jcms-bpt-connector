<?php
/*
 * Render and manage admin page
 */
class JCMSBPTConnectorActions{
	/*
	 * Name of WP options array for this plugin
	 */
    const JCMS_BPT_CONNECTOR_OPTS = 'jcms_bpt_connector_opts';
    
    /*
     * default cache time for URL contents of BPT endpoint, in seconds.
     * 1 hour
     */
    const JCMS_BPT_CONNECTOR_DEFAULT_CACHETIME = 3600;
    
    /*
     * BPT API endpoint URL:
     */
    const JCMS_BPT_API_URL = 'https://www.brownpapertickets.com/api2/';
    
    /*
     * Some instance vars:
     */
    var $_current_jcms_bpt_settings = null;
    var $_bpt_xml = null;
    var $_eventlist = null;
    var $_cachetime;
    var $_message = '';
    
    /*
     * constructor:
     *  - extract current settings for later use
     *  - get the specific URL endpoint for the service we want (event listing) 
     *  - set up a cachetime value. Use configured value if valid, otherwise use a default value
     *  - build working object froun XML source of events (which may be cached)
     */
    function __construct(){
		
    	$this->_current_jcms_bpt_settings = get_option(JCMSBPTConnectorActions::JCMS_BPT_CONNECTOR_OPTS);
        $_url = $this->buildRequestUrl('eventlist');
		//if no cache specified (empty string evaluates to false):
        $this->_cachetime = JCMSBPTConnectorActions::JCMS_BPT_CONNECTOR_DEFAULT_CACHETIME;
        if(intval($this->_current_jcms_bpt_settings[1]['cache'])){	//settype had unexpected behaviour.
        	$this->_cachetime = $this->_current_jcms_bpt_settings[1]['cache'];
        };

//         try{
        	libxml_use_internal_errors(true);
	        $_eventsobject = simplexml_load_string($this->_urlCacheHandler($_url));
	        if($_eventsobject){
	        	$this->_eventlist = $this->makeEventsArray($_eventsobject);
	        }
	        else{
	        	//error. Want it silent, cos BPT does not return valid XML if no events for specified account are returned.
	        }
	        libxml_clear_errors();
//         }
//         catch(Throwable $err){
// //         	var_dump($err);
//         }
    }
    
    /*
     * Called from shortcode handler:
     * Build output of a single event
     * [specific HTML structure TODO]
     * 
     * see php man - simplexmlelement.
     */
    public function getEventById($_id,$_status){	//note: can use default value syntax here as per python

    	$_out = "";

    	if($this->_eventlist != null){

        	/*
        	 * iterate over the events array built in the constructor. 
        	 */
            foreach($this->_eventlist as $_event){
            	
            	/*
        	 	 * match against the passed event ID and render details (TODO)
            	 */
    			if($_event['event_id'] == $_id){
    				//see SO 1848945, 7153022
    				//Build suitable HTML here
    				$_out = <<<EOT
    				<div id= "event_id_{$_id}" class="bpt_event_detail">
    					<h3>
    						<a href="{$_event['link']}" title="{$_event['description']}">
    						{$_event['title']}
    						</a>
    					</h3>
    					<div class="bpt_event_summary">{$_event['description']}</div>
    				    <div>{$_event['e_description']}</div>
    				    <!-- needs to be conditional: -->
    				    <div>
    						<a href="{$_event['e_web']}" title="{$_event['description']}">Event website</a>
    					</div>
    				    <div>{$_event['e_description']}</div>
    				    <div>{$_event['e_description']}</div>
    				</div>
EOT;
    			}
            }
    	}
        else{
            $_out = 'Specified event not found';
        }
        return($_out);
    }
    
    /*
     * Called from shortcode handler:
     * return all events as list (filtered by status?). Default to live only TODO:
     */
    public function getEventList($_status='l'){
        if($this->_eventlist != null){
        	$_out = '<ul>';
        	foreach($this->_eventlist as $_event){
        		$_out .= '<li data-eventid="' . $_event['event_id']. '">';
        		$_out .= '<a target="_blank" href="' . $_event['link'] . '">'. $_event['title'].'</a><span class="summary">' . $_event['description'] . '</span>';
        		$_out .= '</li>';
        	}
        	$_out .= '</ul>';
        }
        else{
            $_out = 'No events found';
        }
    	return $_out;
    }

    
	/*
	 * build and return the working endpoint. Done like this in case other endpoints for BPT
	 * service are needed in future.
	 * 
	 * To expand if needed
	 * 
	 * NOTE: The storage of the API key should be obfuscated (store as hashed string? TODO)
	 */
    private function buildRequestUrl($endpoint){
    	$url = JCMSBPTConnectorActions::JCMS_BPT_API_URL . $endpoint . "?id=" . $this->_current_jcms_bpt_settings[1]['key'] . '&client=' . $this->_current_jcms_bpt_settings[1]['name'];
    	return($url);
    }
    
    /*
     * make array of plain objects from ximplexmlobject. Specific to structure of BPT return XML:
     */
    private function makeEventsArray($_eventsobject){
    	//when first installed, $_eventsobject comes in here as false - we have not set up the options, we cannot call the URL to intialise teh plugin...
    	try{
	    	$_events = array();	//empty
	    	foreach ($_eventsobject->children() as $_child){
	    		//XML is crappy, so need to filter out non-events:
	    		if($_child->getName() == 'event'){
	    			$_evt = array();
	    			//make event object array (move to helper function...)
	    			foreach($_child-> children() as $_event_property){
	    				//TODO: Add filter for active events only (or a flag for certain state):
	    				$_evt[$_event_property->getName()] = $_event_property->__toString();	//build object
	    			}
	    			$_events[] = $_evt;
	    		}
	    	}
    	}
    	catch(Throwable $err){
    		//do nothing
    	}
    	return($_events);
    }
    
    /*
     * Cache the contents of the URL. This use case is a single endpoint that changes infrequently. Therefore
     * the key is static.
     */
    private function _urlCacheHandler($_url){
    	
    	$_key = "cachefile";
    	$_out = null;
    	$_cachefile = plugin_dir_path(__FILE__) . $_key . ".cache";
    	if(file_exists($_cachefile)){
    		// we have a cached file. test it:
    		if(time() - filemtime($_cachefile) > $this->_cachetime){
    			//refresh cache:

    			$_out = @file_get_contents($_url);//The @ symbol suppresses error output
    			/*
    			 * NOTE: I need to do this check BEFORE I write the cache file, otherwise I will cache responses
    			 * that are not HTTP 200 OK.
    			 *
    			 * Use the following to test:
    			 *
    			 * var_dump($http_response_header);//$http_response_header might be null. Add error trapping/HTTP response code checking
    			 *
    			 * Also, create /cache/ directory for these...
    			 * */
    			if($_out){
    				file_put_contents($_cachefile, $_out);
    			}
    			else{
    				//error getting remote file:
    				$_out = '<xml></xml>';
    			}
    		}
    		else{
    			//get cached contents:
    			$_out = file_get_contents($_cachefile);
    		}
    	}
    	else{
    		//no cache file, create one:
    		$_out = file_get_contents($_url);
    		file_put_contents($_cachefile, $_out);
    	}
    	return($_out);
    }
}

