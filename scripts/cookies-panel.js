function cookiesInfo() {
	var x = document.getElementById("cookies-panel");
	if (x != null) {
		x.className = "show";
	}
}
window.onload = function() {cookiesInfo()};

function cookiesInfoClose() {
	var x = document.getElementById("cookies-panel");
	x.className = "noshow";
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("login_reserved_status").innerHTML = this.responseText;
			//alert(this.responseText);
		}
	};
	xmlhttp.open("GET", PATH+"ajax.php?function=hidden_cookies_panel", true);
	xmlhttp.send();
}
