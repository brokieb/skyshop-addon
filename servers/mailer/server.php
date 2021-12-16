    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function write_log( $error,$logtype="error" ) {
    // get date and time for the log entry
    $t = time();
    $date = date('d/n/Y H:i:s', $t);
    
    // prepare log string
    $log = "#{$date} : {$error}\n<br>";
    
    // decide what type of log to write to
    $type = $logtype;    
    switch($logtype) {
        case("error"):
            $type = "error";
            break;
        case("info"):
            $type = "info";
            break;
        case("ok"):
            $type = "ok";
            break;
    }
    $log = trim( $log );    
    $filename = dirname(__FILE__)."/logs/log    .txt";  
    file_put_contents($filename, $log, FILE_APPEND | LOCK_EX );    
    echo $logtype." => ".$log."<br>\n";
    }

   
    include(dirname(__FILE__).'/../conn.php');
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
$sql2 = "SELECT * FROM mail_template WHERE id_template='1'";
$query2 = mysqli_query($conn,$sql2);
$row2 = mysqli_fetch_array($query2,MYSQLI_ASSOC);
$sql = "SELECT * FROM mail_users";
$query = mysqli_query($conn,$sql);
while($row = mysqli_fetch_array($query)){
    $mail = new PHPMailer(true);
    if($row['mail_status']!='-1'){
        $mb = imap_open("{".$row['HostIMAP'].":993/imap/ssl/novalidate-cert}INBOX".$row['catalog'],$row['email'],$row['Password']);
        print_r(imap_errors());
        $result = imap_search($mb, 'UNSEEN UNFLAGGED SUBJECT "Pytanie" ');
    
    
    // echo "<pre>";
    // // print_r(imap_list($mb, "{".$row['HostIMAP'].":993/imap/ssl/novalidate-cert}", "*"));
    // echo "</pre>";
    if (is_array($result) || is_object($result)){
    foreach($result as $x){
        $header = imap_headerinfo( $mb,$x);
        if(strpos($header->Subject,"Re:")===false && strpos($header->Subject,"Odp:")===false){
        write_log("Temat wiadomości obsługiwany : ".$header->Subject,"ok");
        $Body = imap_fetchbody( $mb, $x, 1 );
        // echo $ans[0]->from;
        try {
            //Server settings                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = $row['HostSMTP'];                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = $row['email'];                     // SMTP username
            $mail->Password   = $row['Password'];                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            //Recipients
            $mail->addReplyTo($row['email'], $row['username']);
           $mail->setFrom($row['email'], $row['username']);
           $mail->addAddress($header->reply_toaddress);     // Add a recipient
           // Content
           $mail->isHTML(true);                                  // Set email format to HTML
           $mail->Subject = 'Re: '.$header->Subject;
           $mail->Body = $row2['template'];
           $mail->AltBody    = 'Wkrótce zajmiemy się twoim pytaniem';
            switch($row['mail_status']){
                case '0':
                    write_log("Wysyłanie wiadomości wyłączone status : {$row['mail_status']}","info");
                    break;

                case '1':
                    $status = imap_setflag_full($mb, $x, "\\Flagged"); //po wysłaniu maila ustaw status na zobaczone
                    $status = imap_clearflag_full($mb, $x, "\\Seen");
                    write_log("Aktualne ustawienia nie pozwala na wysłanie Emaila do klienta","info");
                    break;
                case '2':
                    $mail->send();
                    $status = imap_setflag_full($mb, $x, "\\Flagged"); //po wysłaniu maila ustaw status na zobaczone
                    $status = imap_clearflag_full($mb, $x, "\\Seen");
                    break;
            }
           

        } catch (Exception $e) {
           write_log("Nie mogę wysłać wiadomości do klienta, błąd serwera : {$mail->ErrorInfo}");
        }
    }else{
        write_log("Temat wiadomości NIEOBSŁUGIWANY : ".$header->Subject,'info');
        $status = imap_setflag_full($mb, $x, "\\Flagged");
    }
}
    }else{
        write_log("Brak wiadomości dla ".$row['email'],'info');
    }
}else{
    write_log("Poczta odłączona, nie łącze się","info");
}
}
    ?>