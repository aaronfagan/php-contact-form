<?php
// BEGIN CONFIGURATION ////////////////////////////////////////////////

define('EMAIL_TO', 'your-email@address.com');
define('EMAIL_SUBJECT', 'Test Form');
define('CAPTCHA_ENABLED', '1');  // 0 - Disabled, 1 - Enabled

// END CONFIGURATION ////////////////////////////////////////////////

define('CAPTCHA1', rand(1,9));
define('CAPTCHA2', rand(1,9));

if (empty($name))    $name    = '';
if (empty($email))   $email   = '';
if (empty($message)) $message = '';
if (empty($msg))     $msg     = '';

if ($_POST) {
	$name     = $_POST['name'];
	$email    = $_POST['email'];
	$message  = $_POST['message'];
	$captcha  = $_POST['captcha'];
	$captcha1 = $_POST['captcha1'];
	$captcha2 = $_POST['captcha2'];

  // If captcha disabled, set variable values to avoid error.
	if (CAPTCHA_ENABLED == '0') { $captcha1 = '1'; $captcha2 = '1'; $captcha = '2'; }

  // Error handling.
	if (empty($name) || empty($email) || empty($message)) {
		$msg = 'One or more fields were left empty!';
	}
	else if (!$email == '' && (!strstr($email, '@') || !strstr($email, '.'))) {
		$msg = 'Your email address is not formatted correctly!';
	}
	else if (($captcha1 + $captcha2) != $captcha) {
		$msg = 'Your anti-spam answer is incorrect! Please try again.';
	}

  // Build email headers.
	else {
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html;charset=UTF-8';
		$headers['Return-Path']  = 'your-email@address.com';
		$headers['From']         = $name.' <'.$email.'>';
		$headers['Reply-To']     = $name.' <'.$email.'>';

	  // Build email body.
		$body = '
<html>
	<body>
	<div style="max-width:800px;">
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td style="border-bottom: solid 1px #CCC; font-size:18px; font-weight:bold; padding:10px;" colspan="2">'.EMAIL_SUBJECT.'</td>
			</tr>
			<tr>
				<td valign="top" style="padding:10px; border-bottom: solid 1px #CCC;" valign="top"><b>Name:</b></td><td style="padding:10px; border-bottom: solid 1px #CCC;">'.$name.' ('.$email.')</td>
				</tr>
			<tr>
				<td valign="top" style="padding:10px; border-bottom: solid 1px #CCC;" valign="top"><b>Message:</b></td><td style="padding:10px; border-bottom: solid 1px #CCC;">'.$message.'</td>
			</tr>
		</table>
	</div>
	</body>
</html>
		';

	  // Send the email, reset text boxes on form, and show success message.
		mail(EMAIL_TO, EMAIL_SUBJECT, $body, $headers);
		$name = '';
		$email = '';
		$message = '';
		$msg = 'Message Sent!';
	}
}
?>

<!DOCTYPE HTML>
<html>
<head>
</head>
<body>

<?php echo $msg; ?>

<form method="post">

<p>Name:<br><input type="text" name="name" value="<?php echo $name; ?>"  /></p>

<p>Email:<br><input type="text" name="email" value="<?php echo $email; ?>" /></p>

<p>Message:<br><textarea name="message" rows="5" cols="40" /><?php echo $message; ?></textarea></p>

<?php if (CAPTCHA_ENABLED != '0') { ?>

<p><?php echo CAPTCHA1; ?> + <?php echo CAPTCHA2; ?> = ? <br><input type="text" name="captcha" /></p>

<input type="hidden" name="captcha1" value="<?php echo CAPTCHA1; ?>" />
<input type="hidden" name="captcha2" value="<?php echo CAPTCHA2; ?>" />
<?php } ?>

<p><input type="submit" value="Submit" /></p>

</form>

</body>
</html>
