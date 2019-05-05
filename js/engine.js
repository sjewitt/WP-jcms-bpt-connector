/*
 * Actions:
 * !@#$%^&*()_+[]{};:'"\|
 *
 * 
 * JavaScript engine for client-side interactions on JCMS COnnector plugin.
 * 
 * Prinarily this is serialising/deserialising complex object and managing hidden form fields
 */



var engine = {
		

    
    init : function(){
        console.log("start");
    },
    


};

//utility functions:

//unescape stuff:
//http://stackoverflow.com/questions/1147359/how-to-decode-html-entities-using-jquery
function decodeEntities(encodedString) {
    var textArea = document.createElement('textarea');
    textArea.innerHTML = encodedString;
    return textArea.value;
}

//from http://jsfiddle.net/RwE9s/15/
function StringtoXML(text){
    if (window.ActiveXObject){
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.async = 'false';
        doc.loadXML(text);
    } 
    else {
        var parser=new DOMParser();
        var doc=parser.parseFromString(text,'text/xml');
    }
    return doc;
}



jQuery(function(){
	engine.init();
});