var xhttp = new XMLHttpRequest();
xhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		if (xhttp.responseText) { //If response text from PHP script is not empty:
			if (/exceeded your current quota/i.test(xhttp.responseText) || /incorrect api key/i.test(xhttp.responseText)) { //If response text contains error message from api,
				//display dialog which opens 'key.html' in popup if OK is clicked
				if (window.confirm(xhttp.responseText+"\n\nEnter key now?")) window.open(chrome.runtime.getURL("key.html"),"_blank","popup");
			}
			else { //Otherwise:
				//display alert with response text
				window.alert(xhttp.responseText);
				//20% probability of displaying "Buy me a coffee?" dialog which opens popup if OK is clicked
				if (Math.random()<0.2) if (window.confirm("Buy me a coffee?")) window.open(/*PAYPAL DONATE URL*/,"_blank","popup");
			}
		}
	}
};

chrome.storage.local.get(["oaikey"]).then((result) => {
	//Initialize variable 'key' with value 'oaikey' from Chromium Local Storage
	var key = result.oaikey;
	//If 'key' is empty, set it to "EMPTY"
	if (!key) key = "EMPTY";
	//Send request to PHP script with 'key' and current page body
	xhttp.open("POST","https:\/\/"+/*DOMAIN*/+"\/jokemode.php?key="+key,true);
	xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhttp.send("body="+document.body.innerText);
});
