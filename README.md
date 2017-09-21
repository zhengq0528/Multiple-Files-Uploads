-------------------------------------------------------------------------

BigUpload

version 1.0.1    
Created by: Sean Thielen <sean@p27.us>

-------------------------------------------------------------------------

BigUpload is a tool for handling large file uploads (tested up to 2GB) through the browser.

![Screenshot](http://i.imgur.com/vESk5dp.png)

-------------------------------------------------------------------------

It uses the HTML5 FileReader library to split large files into manageable chunks,
and then sends these chunks to the server one at a time using an XmlHttpRequest.

The php script then pieces these chunks together into one large file.

Because the chunks are all the same size, it is easy to calculate an accurate progress bar
and a fairly accurate time remaining variable.

This tool is capable of handling file uploads of up to 2GB in size, without the need to tweak
the max_upload and timeout variables on your httpd.

If you want to deploy this as-is, the variables you need to worry about are in the top of    
	* index.html (for js variables)    
	* inc/bigUpload.php (for the folder paths--make sure they're writable)


Please feel free to contribute!

-------------------------------------------------------------------------

v 1.0.1    
*Added time remaining calculator    
*Response from php script is now a json object, allowing for error processing    
*Minor script changes and bugfixes    
*Better comments

v 1.0.0    
*Initial version




var chunkSize = 1000000;
var maxFileSize = 2147483648;
var aborted = false;
var key = 0;
var timeStart = 0;
var file;
var progressBarFilled = "progressBarFilled";
var uploadResponse = "uploadResponse";
function up(){
	//alert("1");
	var files = document.getElementById("file").files;
	//alert(files[0].name);
	//for(var i = 0 ; i < files.length; i++)
	//{
	//alert(files[i].name);
	//}
	document.getElementById(progressBarFilled).style.backgroundColor = 'rgb(91, 183, 91)';
	document.getElementById(uploadResponse).textContent = '';
	timeStart = new Date().getTime();
	file = files[0];
	key = 0;
	var fileSize = file.size;
	if(fileSize > maxFileSize) return;

	var numberOfChunks = Math.ceil(fileSize/this.chukSize);
	//Main Start Upload files
	sendFile(0,numberOfChunks);
}
function sendFile(chunk,numberOfChunks)
{
	var start = chunk * chunkSize;
	var stop = start + chunkSize;
	var reader = new FileReader();
	reader.onloadend = function(evt) {
		xhr = new XMLHttpRequest();
		xhr.open("POST", 'inc/bigUpload.php?action=upload&key=' + key, true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() {
			if(xhr.readyState == 4) {
				var response = JSON.parse(xhr.response);
				if(response.errorStatus !== 0 || xhr.status != 200) {
					//Call the error method
					printResponse(response.errorText, true);
					return;
				}
				if(chunk === 0 || key === 0) {
					//If it's the first chunk, set this.key to the server response
					key = response.key;
				}

				if(chunk < numberOfChunks) {
					//Update the progress bar
					//progressUpdate(chunk + 1, numberOfChunks);
					//Run this function again until all chunks are uploaded
					sendFile((chunk + 1), numberOfChunks);
				}
				else {
					//The file is completely uploaded
					sendFileData();
				}
			}
		};
		xhr.send(blob);
	};
	var blob = file.slice(start, stop);
	reader.readAsBinaryString(blob);
}

function sendFileData(){
	var data = 'key=' + key + '&name=' + file.name;
	xhr = new XMLHttpRequest();
	xhr.open("POST", 'inc/bigUpload.php?action=finish', true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	xhr.onreadystatechange = function() {
		var response = JSON.parse(xhr.response);
		if(response.errorStatus !== 0 || xhr.status != 200) {
			//Call the error method
			parent.printResponse(response.errorText, true);
			return;
		}
		if(xhr.readyState == 4) {
			//parent.progressUpdate(1, 1);
			parent.printResponse('File uploaded successfully.', false);
		}

	};
	xhr.send(data);
}
function printResponse(responseText,error){
	document.getElementById("uploadResponse").textContent = responseText;
	document.getElementById("timeRemaining").textContent = '';
	if(error === true) {
		document.getElementById("progressBarContainer").style.backgroundColor = 'rgb(218, 79, 73)';
	}
}
