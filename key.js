var myForm = document.getElementById("keyform");

myForm.addEventListener("submit",setKey);

function setKey(event) {
	chrome.storage.local.set({oaikey: document.getElementById("key").value}).then(() => {});
	document.write("<center><br><br><strong>Cool!</strong></center>");
	event.preventDefault();
}