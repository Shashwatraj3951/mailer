<?php

if (isset($_POST['button']) && isset($_FILES['attachment'])) {

	$to = $_POST['r_email']; // Receiver's Email
	$sender_name = $_POST['s_name']; // Sender's Name
	$sender_email = $_POST['s_email']; // Sender's Email
	$reply_to_email = $_POST["s_email"]; // Adding reply on sender's email

	$subject = $_POST['subject']; // Subject of email
	$message = $_POST['message']; // Content of email

	// Analyzing attached file
	$tmp_name = $_FILES['attachment']['tmp_name']; // Temp name of file
	$name     = $_FILES['attachment']['name']; // Name of file
	$size     = $_FILES['attachment']['size']; // Size of file
	$type     = $_FILES['attachment']['type']; // Type of file
	$error    = $_FILES['attachment']['error']; // Error in file

	// Reading attached file
	$handle = fopen($tmp_name, "r"); // Opening file in read only mode (r)
	$content = fread($handle, $size); // Adding content of file
	fclose($handle); // Closing file

	$encoded_content = chunk_split(base64_encode($content)); // Encoding file
	$boundary = md5("random"); // Adding boundary for file

	// Adding headers for email
	$headers = "MIME-Version: 1.0\r\n";
	$headers = "From: " . $sender_name . "<" . $sender_email . ">";
	$headers .= "Reply-To: " . $reply_to_email . "\r\n";
	$headers .= "Content-Type: multipart/mixed;";
	$headers .= "boundary = $boundary\r\n";

	// Body for plain text
	$body = "--$boundary\r\n";
	$body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
	$body .= "Content-Transfer-Encoding: base64\r\n\r\n";
	$body .= chunk_split(base64_encode($message));

	// Body for attached file
	$body .= "--$boundary\r\n";
	$body .= "Content-Type: $type; name=" . $name . "\r\n";
	$body .= "Content-Disposition: attachment; filename=" . $name . "\r\n";
	$body .= "Content-Transfer-Encoding: base64\r\n";
	$body .= "X-Attachment-Id: " . rand(1000, 99999) . "\r\n\r\n";
	$body .= $encoded_content; // Adding encoded attached file

	// Sending all as email
	$sentMailResult = mail($to, $subject, $body, $headers);

	// Work to perform if email is sent or not
	if ($sentMailResult) {
		echo "
<html>
<head>
	<title>Success : )</title>
</head>
	
<body>
<script>
	alert(\"Mailed Successfully!\");
	window.location.replace(\"index.html\");
</script>
</body>
</html>  ";
	} else {
		echo "
	
<html>
<head>
	<title>Failed : (</title>
</head>
	
<body>
<script>
	alert(\"Failed to Send Mail ! Try Again...\");
	window.location.replace(\"index.html\");
</script>
</body>
</html>  ";
	}
}
