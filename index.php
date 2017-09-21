<!doctype html>
<html>
<head>
	<title>Link Generator</title>
	<link rel="stylesheet" href="style.css?=2">
	<script src="js/main.js?=2"></script>
	<script>
	function Message(){
		var check = document.getElementById("file_names").value;
				if(check.localeCompare("markQ")==0)
				{
					alert("You have not upload any file");
				}
				else
				{
					var m = document.getElementById('ECircuit');
						m.style.display = "block";
				}

		//alert("Generating zip file, please wait.");
	}
	bigUpload = new bigUpload();

	//The id of the file input
	bigUpload.inputField = 'file';

	//The id of the progress bar
	//Width of this element will change based on progress
	//Content of this element will display a percentage
	//See bigUpload.progressUpdate() to change this code
	bigUpload.progressBarField = 'progressBarFilled';

	//The id of the time remaining field
	//Content of this element will display the estimated time remaining for the upload
	//See bigUpload.progressUpdate() to change this code
	bigUpload.timeRemainingField = 'timeRemaining';

	//The id of the text response field
	//Content of this element will display the response from the server on success or error
	bigUpload.responseField = 'uploadResponse';

	//Size of file chunks to upload (in bytes)
	//Default: 1MB
	bigUpload.chunkSize = 1000000;

	//Max file size allowed (in bytes)
	//Default: 2GB
	bigUpload.maxFileSize = 2147483648;

	function upload() {
		//bigUpload.resetKey();

		var files = document.getElementById('file').files;
		//while(bigUpload.index < files.length)
		//{
		bigUpload.index = 1;
		bigUpload.file = files[0];
		bigUpload.processFiles();
		//}
	}

	</script>
</head>


<body>
	<div id="bigUpload">
		<div id = "container" class="container" >
			<h1>Link Generator</h1>
			<h3>Please select files to generate zip download link</h3>
			<form method = "post">
				<input type="file" id="file" multiple/>
				<p>Enter zip file name</p>
				<p>Empty field defaults to random name generate by computer </p>
				<input type="text" style = "width:270px" name="zname">
				<input type="hidden" id ="file_names" name = "SetF" value ="markQ">
				<br>
				<input type="button" class="button" value="Start Upload" onclick="upload()" />
				<input type="submit" class="button" name = 'sm' value="Generate Zip" onclick="Message()"/>
			</form>
			<div id="progressBarContainer">
				<div id="progressBarFilled">
				</div>
				<div id="timeRemaining"></div>
			</div>
			<div id="uploadResponse"></div>
			<div style = "text-align:left;">
			<?php
			if(strcmp($_POST['sm'],'Generate Zip')==0)
			{
				$files = $_POST['SetF'];
				if(strcmp($files,'markQ')==0)
					echo "You did not upload any file";
				else
				{
					$files = explode("|",$files);
			    $tmps = $_POST['zname'];
					if(empty($tmps)) $zipname = date("Ymdhis");
					else $zipname = $tmps;
					$dir = "files";
					$zip = new ZipArchive;

					$ddir = "//mail/cdrive/webroot/filearchive/files/linkedfiles/$zipname.zip";
					if(file_exists($ddir)) unlink($ddir);
					$zip->open("$ddir",ZipArchive::CREATE);
					foreach($files as $s)
					{
						$zip->addFile("$dir/$s", $s);
						//echo "$s<br>";
					}
					$zip->close();
					echo "<h2>";
					echo "<br>Successfully created file $zipname.zip <br><br>";
					echo "Link: <a href= https://www.dickersonengineering.com/filearchive/files/linkedfiles/$zipname.zip>
					https://www.dickersonengineering.com/filearchive/files/linkedfiles/$zipname.zip</a>";

					echo "<br><br>Include files:</h2>";
					foreach($files as $s)
					{
						//$zip->addFile("$dir/$s", $s);
						echo "<h3>$s</h3>";
						unlink("$dir/$s");
					}
					echo "<p><font face='courier'><a href='http://192.168.1.14/intranet/jnsearch.html'>&lt;- Back to Job Searches</a></font></p>";
				}
			}
			?>
		</div>
		</div>


	</div>
</body>
</html>
<div id="ECircuit" class="modal">
  <div class="modal-content">
      <center>
        <h2> Generating zip file,Please be patient! </h2>
			</center>
		</div>
	</div>
