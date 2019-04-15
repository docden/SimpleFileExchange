<?php

$fileMaxSize = 8000000;
$allowedExtList = array('jpg', 'jpeg', 'png', 'pdf');

if (isset($_POST['submit'])) {
    $file = $_FILES['file'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    $fileExt = explode('.',$fileName);
    $fileActualExt = strtolower(end($fileExt));

    if (in_array($fileActualExt, $allowedExtList)) {
        if ($fileError === 0) {
            if ($fileSize < $fileMaxSize) {
                $fileNameNew = uniqid('', true).".".$fileActualExt;
                $fileDestination = $fileNameNew;
                if (move_uploaded_file($fileTmpName, $fileDestination)) {
                  header("Location: index.php?uploadsuccess");
                }
                echo "Move file failed";
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "There was an error uploading your file!";
        }
    } else {
        echo "You cannot upload files of this type!";
    }
}

if(isset($_POST['delete_file']))
{
	$filename = $_POST['file_name'];
	unlink(__DIR__."/".$filename);
}

?>
<!DOCTYPE html>
<html>

<head>
    <title></title>
	<style>
		table {
  			font-family: arial, sans-serif;
  			border-collapse: collapse;
  			width: 100%;
		}

		td, th {
  			border: 1px solid #dddddd;
  			text-align: right;
  			padding: 8px;
		}

		tr:nth-child(even) {
  			background-color: #dddddd;
		}
	</style>
</head>

<body>

<h2"><a href="/">SimpleFileExchange!</a></h2>
<p></p>

<form action="index.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit" name="submit">UPLOAD</button>
</form>
<p></p>

<div id="wrapper">
<div id="file_div">
<?php
//TODO Sort by colomn
$folder = __DIR__;
if ($dir = opendir($folder))
{
	echo "<table>";
	echo "<tr>";
	    echo "<th>Name</th>";
	    echo "<th>Size</th>";
	    echo "<th>Action</th>";
	echo "</tr>";
	while (($file = readdir($dir)) !== false)
	{
    	if (!is_dir ($file))
    	{
        	echo "<tr>";
        	    echo "<th>"."<a href="."'".rawurlencode ($file)."' download".">".$file."</a>"."</th>";
        	    echo "<th>".filesize ($file)."</th>";
        	    echo "<th>";
        	        echo "<form method='post' action='index.php'>";
        	            echo "<input type='hidden' name='file_name' value='".$file."'>";
        	            echo "<input type='submit' name='delete_file' value='Delete File'>";
        	        echo "</form>";
        	    echo "</th>";
        	echo "</tr>";
    	}
	}
    echo "</table>";
    closedir($dir);
}
?>
</div>
</div>

</body>
</html>

