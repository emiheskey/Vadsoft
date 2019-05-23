<?php 
include('functions.php');

if (file_exists($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php")) {
    include($_SERVER['DOCUMENT_ROOT']."/config/MailSettings.php");
} else {
    $path = substr(__DIR__, 0, -9);
    include($path."/config/MailSettings.php");
}

// Register Users
if($_POST) 
{ 
    $phone      = $_POST['phone'];
    $email      = $_POST['email'];
    $name       = $_POST['name'];
    $ad_name    = $_POST['ad_name'];
    $add        = $_POST['add']; 
    $cat        = $_POST['cat'];
    $location   = $_POST['location'];
    $mode       = $_GET['mode'];

    switch ($mode) {
        case 'edit':
            # code...
            // pick the organisation id
            $org_id = $_POST['org_id'];
            // run 
            $_SESSION['flash'] = UpdateOrg($connection,$name,$ad_name,$email,$phone,$add,$location,$cat,$datetime,$org_id);

            header("Refresh:0; url=../organization?mode=edit");

            break;

        case 'new':
            # code
            // generate license key
            $license_key = date("Y").generate_license_key();
            // generate expiry date

            $org_id = InsertOrg($connection,$name,$ad_name,$email,$phone,$add,$location,$cat,$datetime,$license_key);
            if ($org_id >0) 
            {
                $org_details = base64_encode($name."**".$org_id);
                // email/sms license key
                $message = "Hello,\n\nThank you for your interest in VADSoft. Our goal is to help you improve the productivity level of your staff and organisation as a whole.\n\nTo gain full access to the application, please kindly visit this link http://app.vadsoft.com.ng/setuporganization?org_details=$org_details to setup your account. Your license key is $license_key\n\nFor questions or enquires, send us an email at info@vadsoft.com.ng\n\nThanks\nVADSoft";
                $subject = "VADSoft Registrtaion";
                SendMail($email, "", $subject, $message);
                
                // send_sms($phoneno, $message);
                // redirect page to logout
                header("Refresh:1; url=../logout.php");
                // echo message  
                echo "Registration Was Successful. License key is $license_key $org_details";
            }
            else
            {
                echo "Registration was Unsuccessful";
            }
            break;
        
        default:
            # code...
            break;
    }
}
else
{
    echo "Unsuccessful";
}


//this function tries,yes tries to correctly encrypt the business id to numbers and also decrypts them
function do_encrypt_dcrypt($key, $type)
{
     if($type == 1){ //encrypt
        return encrypt($key,'1134ASGF-RTE41sd1yu3456');
     }elseif($type == 2){ //dcrypt
        return decrypt($key,'1134ASGF-RTE41sd1yu3456');
     }
}

//function to generate codes
function generate_license_key()
{
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"; 
    srand((double)microtime()*1000000); 
    $i = 0; 
    $pass = '' ; 
    while ($i <= 12)
    { 
        $num = rand() % 33; 
        $tmp = substr($chars, $num, 1); 
        $pass = $pass . $tmp; 
        $i++; 
    } 
    return $pass; 
}


function encrypt($sData, $secretKey)
{
    $sResult = '';
    for($i=0;$i<strlen($sData);$i++)
    {
        $sChar    = substr($sData, $i, 1);
        $sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1);
        $sChar    = chr(ord($sChar) + ord($sKeyChar));
        $sResult .= $sChar;
    }
    return encode_base64($sResult);
} 

function decrypt($sData, $secretKey)
{
    $sResult = '';
    $sData   = decode_base64($sData);
    for($i=0;$i<strlen($sData);$i++)
    {
        $sChar    = substr($sData, $i, 1);
        $sKeyChar = substr($secretKey, ($i % strlen($secretKey)) - 1, 1);
        $sChar    = chr(ord($sChar) - ord($sKeyChar));
        $sResult .= $sChar;
    }
    return $sResult;
}

function encode_base64($sData)
{
    $sBase64 = base64_encode($sData);
    return str_replace('=', '', strtr($sBase64, '+/', '-_'));
}

function decode_base64($sData)
{
    $sBase64 = strtr($sData, '-_', '+/');
    return base64_decode($sBase64.'==');
}

function send_sms($phoneno, $message)
{
    
    /* ---- Sending a reply SMS ---- */
    
    // Setting the recipients of the reply. If not set, the reply is sent
    // back to the sender of the origial SMS message
    // header('X-SMS-To: +97771234567 +15550987654');
    
    
    // Setting the content type and character encoding
    //header('Content-Type: text/plain; charset=utf-8');
    // Comment the next line out if you do not want to send a reply
    //echo $reply_message;
    

    //session_start();
    //#onclick send sms
    //include("file:///C|/xampp/htdocs/Ed/setia/public/opendb.php");
    //if(isset($_POST['btnSend'])){
    //$serviceid=$_SESSION['serviceid'];
    $smsreceivers = $phoneno; //numbers seperated by comma;
    $smsg = $message;
    //$to=$subject;

    # call sms function
    $owneremail =   "dkdimgba@yahoo.com";
    $subacct    =   "DIMGBA";
    $subacctpwd =   "dimgba";
    $sendto     =   urlencode($smsreceivers); /* destination number */
    $sender     =   "VadSoft"; /* sender id */
    $message    =   urlencode($smsg); /* message to be sent */
    /* create the required URL */

    $cmd = "http://www.smslive247.com/http/index.aspx?cmd=sendmsg&sessionid=e465a7f2-fdc2-4f86-aa39-a0bf8b417907&message=".$message."&sender=".$sender."&sendto=".$sendto."&msgtype=0";
    # call the URL 
        // create a new cURL resource
    $ch = curl_init();
    // Set API credentials
    // $username = "dailytrust";
    // $password = "shortcode";
    // Build the API command string
    // $cmd = "http://shortcodenigeria.com/secure2/api/send.php?uname=dailytrust&pword=shortcode&id=".$messageId."&message=".$message;
    // set URL and other appropriate options
    // Send API command and clean up
    curl_setopt($ch, CURLOPT_URL, "$cmd");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// grab URL and pass it to the browser
    // Print response
    $response = curl_exec($ch);
    echo "$response\n";
    // close cURL resource, and free up system resources
    curl_close($ch);
}
