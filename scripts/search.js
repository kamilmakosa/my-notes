function search(inputsearch) {
	textsearch = inputsearch.value;
	tagsrow = document.querySelectorAll("tbody tr");
	tagsrow = tagsrow.length;
	licznik=0;
	for (i=1;i<tagsrow+1;i++) {
		wiersz = "rows"+i;
		pole1 = document.getElementById("tags"+i).innerHTML;
		pole1 = pole1.split(",");
		pole2 = document.getElementById("name"+i);
		var wzor = new RegExp("^.*("+textsearch+").*$","i");
		flag = false;
		for (k=0;k<pole1.length;k++) {
			if(wzor.test(pole1[k])) {
				flag = true;
			}
		}
		if(wzor.test(pole2.innerHTML)) {
			flag = true;
		}
		if(wzor.test(pole2.href)) {
			flag = true;
		}
		
		if(flag == true) {
			document.getElementById(wiersz).style.display = "table-row";
		}
		else {
			document.getElementById(wiersz).style.display = "none";
		}
	}
}