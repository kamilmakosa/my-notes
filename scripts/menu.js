function menu() {
	var x = document.getElementById("opacity");
	if (x.className.indexOf("open") == -1) {
        x.className += "open";
    } else { 
        x.className = x.className.replace("open", "");
    }
	
	var x = document.getElementById("menu");
	if (x.className.indexOf("open") == -1) {
        x.className += "open";
    } else { 
        x.className = x.className.replace("open", "");
    }
}