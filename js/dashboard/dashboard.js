function checkSession(url) {
    jQuery.ajax({url: url+"index.php/login/is_session_expired", async: false, success: function(data) {
		if (data == "true") {
			window.location = url+"/index.php/view/home";
		}
	}});
}

var DashboardUI = {
	
    initialize: function(url) {
		
		this.url = url;
		
		var validateSession = setInterval(function(){checkSession(url)}, 60000);
	}
};
