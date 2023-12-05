var myForm = document.getElementById("keyform");

myForm.addEventListener("submit",setKey);

function setKey(event) {
	//Set 'oaikey' in Chromium Local Storage to value submitted by user
	chrome.storage.local.set({oaikey: document.getElementById("key").value}).then(() => {});
	//Rewrite page, display success message
	document.write("<center><br><br><strong>Cool!</strong></center>");
	//Prevent actual form submission, so that the page is not reloaded
	event.preventDefault();
}