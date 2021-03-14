function setLanguage(lang) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
			location.reload();
		}
	};
	xmlhttp.open("GET", PATH+"ajax.php?function=set_language_cookie&lang="+lang, true);
	xmlhttp.send();
}

function languageShow() {
	if (document.getElementById("dropdown-lang").style.display == "block") {
	document.getElementById("dropdown-lang").style.display = "none";
	}
	else {
	document.getElementById("dropdown-lang").style.display = "block";
	}

}
