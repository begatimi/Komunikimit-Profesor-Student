<?php
	require('header.php');
	require('PHPMailer/PHPMailerAutoload.php');
	
	if ( isset($_POST['btn-submit']) ) {
		//echo "<script>alert(\"test\");</script>";
		$sender = trim($_POST['sender']);
		
		$senderemail = trim($_POST['sender-email']);
		
		$subject = trim($_POST['subject']);
		
		$message = trim($_POST['message']);
		$message = str_replace("\n.", "\n..", $message); // perdorimi i str_replace per vendosjen e pikes pas newline-it ne email
		
		/*$headers = 'From: $sender' . "\r\n" .	'X-Mailer: PHP/' . phpversion();
		
		mail("Getoari_g@hotmail.com",$subject,$message,$headers);*/
		
		$mail = new PHPMailer;

		$mail->SMTPDebug = 0;                               
		$mail->isSMTP();                                      
		$mail->Host = "smtp.gmail.com";
		$mail->SMTPAuth = true;     
		
		//Email-i testues qe do te perdoret per dergim te email-ve    
		$mail->Username = "Getoari001@gmail.com";                 
		$mail->Password = "fiekfiek123";                        
		
		$mail->SMTPSecure = "tls";
		$mail->Port = 587;                                   
		
		$mail->From = $senderemail; // emaili i derguesit
		$mail->FromName = $sender; // emri i derguesit
		
		// Email-i i cili do ti pranoj email-at
		$mail->addAddress("getoari_g@hotmail.com", "PKPS Help Center");

		$mail->isHTML(true);

		$mail->Subject = $subject; // subjekti
		$mail->Body = "<i>$message</i>"; // mesazhi ne html

		if(!$mail->send()) {
			echo "<script>alert(\"Mailer Error: ".$mail->ErrorInfo."\");</script>";
		} else {
			echo "<script>alert(\"Mail has been sent!\");</script>";
		}
	}
?>
		<!-- page content -->	
		<div id="content" style="height: 600px">
			<address>
				<h1 id="border_img"><font face="verdana" size="6" color="black">Contact<span id="demo"></span></font></h1>
				
				<p>Bregu i Diellit, p.n.</p>
				<p>10000 Prishtin&euml;, Republika e Kosov&euml;s</p>
				<br/>
				<p><b>Tel.:</b> <i>+381(0)38 554 896 ext.102</i></p>
				<p><strong>Fax:</strong> <i>+381(0)38 542 525</i></p>
				<p><strong>E-mail:</strong> <u><a href="mailto:fiek@uni-pr.com">fiek@uni-pr.com</a></u></p>
				<p>
					<a href="http://fb.com" target="_blank"><img height="40px" width="40px" src="images/social/facebook.png" alt="facebook" /></a>
					<a href="http://twitter.com" target="_blank"><img height="40px" width="40px" src="images/social/twitter.png" alt="twitter" /></a>
					<a href="http://plus.google.com" target="_blank"><img height="40px" width="40px" src="images/social/google_plus.png" alt="google plus" /></a>
				</p>
			</address>
			<form method="post" id="contact_form" onsubmit="contact.php">
					<label>Name:</label>
					<input type="text" id="name" name="sender" placeholder="Enter name">
					<label>E-Mail:</label>
					<input type="text" id="email" name="sender-email" placeholder="Enter email">
					<label>Subject:</label>
					<input type="text" name="subject" id="subject" placeholder="Enter subject">
					<label>Message:</label>
					<textarea placeholder="Write your message here" name="message"></textarea>
				<input id="submit" type="submit" name="btn-submit"> 
			</form>
		</div>
<?php
	require('footer.php');
?>