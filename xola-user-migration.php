
<!DOCTYPE html>
<html>
<head>
  <!-- Standard Meta -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

  <!-- Site Properties -->
  <title>Migration XOLA</title>
  <link rel="stylesheet" type="text/css" href="dist/components/reset.css">
  <link rel="stylesheet" type="text/css" href="dist/components/site.css">

  <link rel="stylesheet" type="text/css" href="dist/components/container.css">
  <link rel="stylesheet" type="text/css" href="dist/components/grid.css">
  <link rel="stylesheet" type="text/css" href="dist/components/header.css">
  <link rel="stylesheet" type="text/css" href="dist/components/image.css">
  <link rel="stylesheet" type="text/css" href="dist/components/menu.css">

  <link rel="stylesheet" type="text/css" href="dist/components/divider.css">
  <link rel="stylesheet" type="text/css" href="dist/components/segment.css">
  <link rel="stylesheet" type="text/css" href="dist/components/form.css">
  <link rel="stylesheet" type="text/css" href="dist/components/input.css">
  <link rel="stylesheet" type="text/css" href="dist/components/button.css">
  <link rel="stylesheet" type="text/css" href="dist/components/list.css">
  <link rel="stylesheet" type="text/css" href="dist/components/message.css">
  <link rel="stylesheet" type="text/css" href="dist/components/icon.css">

  <script src="assets/library/jquery.min.js"></script>
  <script src="dist/components/form.js"></script>
  <script src="dist/components/transition.js"></script>
<!-- -->
  <style type="text/css">
    body {
      background-color: #DADADA;
    }
    body .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>
  
</head>
<body>

<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui teal image header">
      <!--<img src="http://www.xola.com/images/xola-logo-white-small.png" class="image">-->
      <div class="content">
        User Migration
      </div>
    </h2>
    <form class="ui medium form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="ui stacked segment">
        <div class="field">
          <div class="ui input">
            <input type="text" name="s_url" placeholder="Source URL with http(s)://" required>
          </div>
        </div>
        <div class="field">
          <div class="ui  input">
            <input type="text" name="d_url" placeholder="Destination URL with http(s)://" required>
          </div>
        </div>
        <div class="field">
          <div class="ui  input">
            <input type="text" name="seller" placeholder="Seller ID Only" required>
          </div>
        </div>
        <div class="field">
          <div class="ui input">
            <input type="text" name="s_user_name" placeholder="Admin User Name(Source Environment)" required>
          </div>
        </div>
        <div class="field">
          <div class="ui  input">
            <input type="password" name="s_password" placeholder="Admin Password(Source Environment)" required>
          </div>
        </div>
        <div class="field">
          <div class="ui input">
            <input type="text" name="d_user_name" placeholder="Admin User Name(Destination Environment)" required>
          </div>
        </div>
        <div class="field">
          <div class="ui  input">
            <input type="password" name="d_password" placeholder="Admin Password(Destination Environment)" required>
          </div>
        </div>
        <input type="submit" class="ui fluid large teal submit button" value="Start User Migration"></input>
      </div>

      <div class="ui error message"></div>

    </form>
  </div>
</div>
<?php

if(($_SERVER['REQUEST_METHOD']=='POST'))
{ 

    xola_user_fetch_post();
}
function admin_source_api_fetch(){

     $s_admin_url = $_POST['s_url'];
     $s_user_name = $_POST['s_user_name'];
     $s_password = $_POST['s_password'];
     

    $ch_s_id = curl_init(); 
    curl_setopt($ch_s_id, CURLOPT_URL, $s_admin_url.'/api/users/me'); 
    curl_setopt($ch_s_id, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch_s_id,CURLOPT_CUSTOMREQUEST,"GET");
    curl_setopt($ch_s_id, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
    curl_setopt($ch_s_id,CURLOPT_USERPWD, $s_user_name.':'.$s_password);
    curl_setopt($ch_s_id,CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch_s_id,CURLOPT_SSL_VERIFYPEER, 0);

    $data_s = curl_exec($ch_s_id); 

    $response_s = json_decode($data_s,TRUE);

    $err_s = curl_error($ch_s_id);
    curl_close($ch_s_id); 

    if ($err_s) {
      echo "cURL Error #:" . $err_s;
    } else {
     $id= $response_s['id'];
     $ch_s = curl_init(); 
     curl_setopt($ch_s, CURLOPT_URL, $s_admin_url.'/api/users/'.$id.'/apiKey'); 
     curl_setopt($ch_s, CURLOPT_RETURNTRANSFER, 1); 
     curl_setopt($ch_s,CURLOPT_CUSTOMREQUEST,"GET");
     curl_setopt($ch_s, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
     curl_setopt($ch_s,CURLOPT_USERPWD, $s_user_name.':'.$s_password);
     curl_setopt($ch_s,CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch_s,CURLOPT_SSL_VERIFYPEER, 0);

     $data_s_api = curl_exec($ch_s); 
     $response_s_api = json_decode($data_s_api,TRUE);
     curl_close($ch_s); 
     return $response_s_api[0];
   }
 }

 function admin_destination_api_fetch(){

    
     $d_admin_url = $_POST['d_url'];
     $d_user_name = $_POST['d_user_name'];
     $d_password = $_POST['d_password'];

    $ch_d_id = curl_init(); 
    curl_setopt($ch_d_id, CURLOPT_URL, $d_admin_url.'/api/users/me'); 
    curl_setopt($ch_d_id, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch_d_id,CURLOPT_CUSTOMREQUEST,"GET");
    curl_setopt($ch_d_id, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
    curl_setopt($ch_d_id,CURLOPT_USERPWD, $d_user_name.':'.$d_password);
     curl_setopt($ch_d_id,CURLOPT_SSL_VERIFYHOST,0);
     curl_setopt($ch_d_id,CURLOPT_SSL_VERIFYPEER,0);

    $data_d = curl_exec($ch_d_id); 

    $response_d = json_decode($data_d,TRUE);

    $err_d = curl_error($ch_d_id);
    curl_close($ch_d_id); 

    if ($err_d) {
      echo "cURL Error #:" . $err_d;
    } else {
     $id= $response_d['id'];
     $ch_d = curl_init(); 
     curl_setopt($ch_d, CURLOPT_URL, $d_admin_url.'/api/users/'.$id.'/apiKey'); 
     curl_setopt($ch_d, CURLOPT_RETURNTRANSFER, 1); 
     curl_setopt($ch_d,CURLOPT_CUSTOMREQUEST,"GET");
     curl_setopt($ch_d, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
     curl_setopt($ch_d,CURLOPT_USERPWD, $d_user_name.':'.$d_password);
     curl_setopt($ch_d,CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch_d,CURLOPT_SSL_VERIFYPEER, 0);

     $data_d_api = curl_exec($ch_d); 
     $response_d_api = json_decode($data_d_api,TRUE);
     curl_close($ch_d); 
     return $response_d_api[0];
   }
 }
function xola_user_fetch_post(){
$s_url = $_POST['s_url'];
$d_url = $_POST['d_url'];
$seller_id = $_POST['seller'];

$apiKey_s = admin_source_api_fetch();
$apiKey_d = admin_destination_api_fetch();

$curl_user_fetch = curl_init();

curl_setopt_array($curl_user_fetch, array(
  CURLOPT_URL => $s_url.'/api/seller/'.$seller_id.'?admin=true',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "x-api-key: ".$apiKey_s
  ),
));

$response_user_fetch = curl_exec($curl_user_fetch);
$err_user_fetch = curl_error($curl_user_fetch);

$decode = json_decode($response_user_fetch,TRUE);

curl_close($curl_user_fetch);

if ($err_user_fetch) {
  echo "cURL Error #:" . $err_user_fetch;
} else {
  
  $name = $decode['name'];
  $email = $decode['email'];
function random_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

$password = random_password(8);

$post = [
    'name' => $name,
    'email' => $email,
    'password'   => $password,
    'confirm_password' => $password,
    'invitation_code' => 'IAMXOLA',
    'agreement'=>'true',
];

$curl_user_post = curl_init();

curl_setopt_array($curl_user_post, array(
  CURLOPT_URL => $d_url.'/account/register',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_SSL_VERIFYHOST => 0,
  CURLOPT_SSL_VERIFYPEER => 0,
  CURLOPT_POSTFIELDS => ($post),
  CURLOPT_HTTPHEADER => array(
    "x-api-key : ".$apiKey_d,
    "cache-control: no-cache",
    "postman-token: c8137ecd-bea3-18a4-0b93-6eb675009a21"
  ),
));

$response_user_post = curl_exec($curl_user_post);
$err_user_post = curl_error($curl_user_post);
//$decode = json_decode($response,TRUE);

curl_close($curl_user_post);

if ($err_user_post) {
  echo "cURL Error #:" . $err_user_post;
} else {
echo "User Is Created. The Password is :" .$password,PHP_EOL;


}   
}

}


?>



</body>

</html>
