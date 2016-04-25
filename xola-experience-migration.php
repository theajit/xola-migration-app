<!DOCTYPE html>
<html>
<head>
    <!-- Standard Meta -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <!-- Site Properties -->
    <title>Experience Migration XOLA</title>
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

    <script src="dist/components/form.js"></script>
    <script src="dist/components/transition.js"></script>

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
                User Experience Migration
            </div>
        </h2>
        <h4>This helps to enable the user at destination and migrate experiences from source to destination environment</h4>
        <form class="ui medium form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="ui stacked segment">
            <h4 class="ui dividing header">Source Information</h4>
            	<div class="field">
            	<label>Seller E-mail</label>
                    <div class="ui  input">
                        <input type="text" name="seller_username" placeholder="seller@website.xyz" required />
                    </div>
                </div>
                <div class="field">
                <label>Source URL</label>
                    <div class="ui input">
                        <input type="text" name="s_exp_url" placeholder="https://xola.com" required />
                    </div>
                </div>
                <div class="field">
                <label>Admin User Name</label>
                    <div class="ui input">
                        <input type="text" name="s_user_name" placeholder="Admin User Name "
                               required>
                    </div>
                </div>
                <div class="field">
                <label>Admin Password</label>
                    <div class="ui  input">
                        <input type="password" name="s_password" placeholder="Admin Password"
                               required />
                    </div>
                </div>
                 <h4 class="ui dividing header">Destination Information</h4>
                <div class="field">
                <label>Destination URL</label>
                    <div class="ui  input">
                        <input type="text" name="d_exp_url" placeholder="https://dev.xola.com" required />
                    </div>
                </div>
                
                <div class="field">
                <label>Seller Password</label>
                    <div class="ui  input">
                        <input type="password" name="d_password" placeholder="Password Generated while creating User"
                               required />
                    </div>
                </div>
                
                <div class="field">
                <label>Admin User Name</label>
                    <div class="ui input">
                        <input type="text" name="d_user_name" placeholder="Admin User Name"
                               required />
                    </div>
                </div>
                <div class="field">
                <label>Admin Password</label>
                    <div class="ui  input">
                        <input type="password" name="d_admin_password"
                               placeholder="Admin Password" required />
                    </div>
                </div>

                <input type="submit" class="ui fluid large teal submit button"
                       value="Start Experience Migration" />
            </div>

            <div class="ui error message"></div>

        </form>
    </div>
</div>
<?php
ini_set('max_execution_time', 600);

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    include_once('xola-user-api.php');
    user_api_fetch();
    xola_exp_fetch_post();
}

function user_api_fetch()
{

    $d_exp_url = $_POST['d_exp_url'];
    $seller_username = $_POST['seller_username'];
    $d_password = $_POST['d_password'];

    $ch_id = curl_init();
    curl_setopt($ch_id, CURLOPT_URL, $d_exp_url . '/api/users/me');
    curl_setopt($ch_id, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_id, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch_id, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_id, CURLOPT_USERPWD, $seller_username . ':' . $d_password);
    curl_setopt($ch_id, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch_id, CURLOPT_SSL_VERIFYPEER, 0);

    $data = curl_exec($ch_id);

    $response = json_decode($data, TRUE);

    $err = curl_error($ch_id);
    curl_close($ch_id);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $id = $response['id'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $d_exp_url . '/api/users/' . $id . '/apiKey');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $seller_username . ':' . $d_password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $data_api = curl_exec($ch);
        $response_api = json_decode($data_api, TRUE);
        curl_close($ch);
        return $response_api[0];
    }
}

function admin_source_api_fetch()
{

    $s_admin_url = $_POST['s_exp_url'];
    $s_user_name = $_POST['s_user_name'];
    $s_password = $_POST['s_password'];

    $ch_s_id = curl_init();
    curl_setopt($ch_s_id, CURLOPT_URL, $s_admin_url . '/api/users/me');
    curl_setopt($ch_s_id, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_s_id, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch_s_id, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_s_id, CURLOPT_USERPWD, $s_user_name . ':' . $s_password);
    curl_setopt($ch_s_id, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch_s_id, CURLOPT_SSL_VERIFYPEER, 0);

    $data_s = curl_exec($ch_s_id);

    $response_s = json_decode($data_s, TRUE);

    $err_s = curl_error($ch_s_id);
    curl_close($ch_s_id);

    if ($err_s) {
        echo "cURL Error #:" . $err_s;
    } else {
        $id = $response_s['id'];
        $ch_s = curl_init();
        curl_setopt($ch_s, CURLOPT_URL, $s_admin_url . '/api/users/' . $id . '/apiKey');
        curl_setopt($ch_s, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch_s, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch_s, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch_s, CURLOPT_USERPWD, $s_user_name . ':' . $s_password);
        curl_setopt($ch_s, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch_s, CURLOPT_SSL_VERIFYPEER, 0);

        $data_s_api = curl_exec($ch_s);
        $response_s_api = json_decode($data_s_api, TRUE);
        curl_close($ch_s);
        return $response_s_api[0];
    }
}

//$apiKey = user_api_fetch();

//echo $apiKey;
function xola_exp_fetch_post()
{

    $s_exp_url = $_POST['s_exp_url'];
    $d_exp_url = $_POST['d_exp_url'];
    $seller_username = $_POST['seller_username'];
    //user_enable();
    $api_key = user_api_fetch();
    $api_key_s = admin_source_api_fetch();
    $curl_exp_fetch = curl_init();

    curl_setopt_array($curl_exp_fetch, array(
        CURLOPT_URL => $s_exp_url . '/api/experiences?seller=' . $seller_username . '&admin=true&limit=100',
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
            "x-api-key: " . $api_key_s
        ),
    ));

    $response_exp_fetch = curl_exec($curl_exp_fetch);

    $err_exp_fetch = curl_error($curl_exp_fetch);

    $decode = json_decode($response_exp_fetch, TRUE);

    curl_close($curl_exp_fetch);

    if ($err_exp_fetch) {
        echo "cURL Error #:" . $err_exp_fetch;
    } else {
        //var_dump($decode);

        foreach ($decode['data'] as $data) {
            //$data = $decode['data'][0];
            //var_dump($data);
            unset($data['seller']);
            unset($data['photo']);
            unset($data['medias']);
            //var_dump($data);

            $post_exp = json_encode($data);

            //var_dump($data);
            $curl_exp_post = curl_init();

            curl_setopt_array($curl_exp_post, array(
                CURLOPT_URL => $d_exp_url . '/api/experiences',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_POSTFIELDS => $post_exp,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Cache-Control: no-cache",
                    "x-api-key: " . $api_key,
                    "Accept: application/json"

                ),
            ));

            $response_exp_post = curl_exec($curl_exp_post);
            $err_exp_post = curl_error($curl_exp_post);
            //$decode = json_decode($response,TRUE);

            curl_close($curl_exp_post);
            if ($err_exp_post) {
                echo "cURL Error #:" . $err_exp_post;
            } else {
                $first = count($decode['data']);
                //print_r($_POST);
            }

        }

        foreach ($decode['paging'] as $data_paging) {
            //var_dump($data_paging);
            $page_url = $data_paging;
            $curl_exp_next_fetch = curl_init();

            curl_setopt_array($curl_exp_next_fetch, array(
                CURLOPT_URL => $s_exp_url . $page_url,
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
                    "x-api-key: " . $api_key_s
                ),
            ));

            $response_exp_next_fetch = curl_exec($curl_exp_next_fetch);

            $err_exp_next_fetch = curl_error($curl_exp_next_fetch);

            $decode_next = json_decode($response_exp_next_fetch, TRUE);

            curl_close($curl_exp_next_fetch);

            if ($err_exp_next_fetch) {
                echo "cURL Error #:" . $err_exp_next_fetch;
            } else {
                foreach ($decode_next['data'] as $data_next) {
                    # code...
                    unset($data_next['seller']);
                    unset($data_next['photo']);
                    unset($data_next['medias']);
                    //var_dump($data);

                    $post_exp_next = json_encode($data_next);

                    $curl_exp_post_next = curl_init();

                    curl_setopt_array($curl_exp_post_next, array(
                        CURLOPT_URL => $d_exp_url . '/api/experiences',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 900,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_SSL_VERIFYHOST => 0,
                        CURLOPT_SSL_VERIFYPEER => 0,
                        CURLOPT_POSTFIELDS => $post_exp_next,
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json",
                            "Cache-Control: no-cache",
                            "x-api-key: " . $api_key,
                            "Accept: application/json"
                        ),
                    ));

                    $response_exp_post_next = curl_exec($curl_exp_post_next);
                    $err_exp_post_next = curl_error($curl_exp_post_next);
                    //$decode = json_decode($response,TRUE);

                    curl_close($curl_exp_post_next);
                    if ($err_exp_post_next) {
                        echo "cURL Error #:" . $err_exp_post_next;
                    } else {
                        $next = count($decode_next['data']);
                        //print_r($_POST);
                    }
                }
            }
        }
    }
    echo ($first + $next) . ' Experiences Migrated';
}

?>


</body>
</html>
