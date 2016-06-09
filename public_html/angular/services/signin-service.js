app.constant("SIGNIN_ENDPOINT", "php/api/login/");
app.service("signinService", function($http, SIGNIN_ENDPOINT) {
	function getUrl() {
		return(LOGIN_ENDPOINT);
	}
	this.login = function(signin) {
		return($http.post(getUrl(), signin));
	};
});
