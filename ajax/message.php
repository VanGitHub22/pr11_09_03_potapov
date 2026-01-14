<?
    session_start();
	include("../settings/connect_datebase.php");

    function decryptAES($encryptedData, $key){
		$data = base64_decode($encryptedData);

		if($data === false || strlen($data) < 17){
			error_log("Invalid data or too short");

			return false;
		}

		$iv = substr($data, 0, 16);

		$encrypted = substr($data, 16);

		$keyHash = md5($key);
		$keyBytes = hex2bin($keyHash);

		$decrypted = openssl_decrypt(
			$encrypted,
			'aes-128-cbc',
			$keyBytes,
			OPENSSL_RAW_DATA,
			$iv
		);

		return $decrypted;
	}

    $message_encrypted = $_POST['Message'] ?? '';
    $IdPost_encrypted = $_POST['IdPost'] ?? '';

	$secretKey = 'qazxcwedcvfrtgbn';

	$Message = decryptAES($message_encrypted, $secretKey);
	$IdPost = decryptAES($IdPost_encrypted, $secretKey);

    $IdUser = $_SESSION['user'];

    $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");
?>