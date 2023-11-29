var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		if (xhttp.responseText) {
			if (/exceeded your current quota/i.test(xhttp.responseText) || /incorrect api key/i.test(xhttp.responseText)) {
				if (window.confirm(xhttp.responseText+"\n\nEnter key now?")) window.open(chrome.runtime.getURL("key.html"),"_blank","popup");
			}
			else {
			window.alert(xhttp.responseText);
			if (Math.random()<0.2) if (window.confirm("Buy me a coffee?")) window.open(/*PAYPAL DONATE URL*/,"_blank","popup");
			}
		}
	}
};

chrome.storage.local.get(["oaikey"]).then((result) => {
	var key = result.oaikey; if (!key) key = "EMPTY";
	xhttp.open("POST","https:\/\/"+/*DOMAIN*/+"\/jokemode.php?key="+key+"&url="+window.location.href,true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send(" ");
});
