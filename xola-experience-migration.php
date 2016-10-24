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
                Experience Migration
            </div>
        </h2>
        <h4>
            This will migrate a seller from destination to source environment assuming the seller exists in the
            destination environment. If a seller does not exist, the migration will abort.
        </h4>
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
                    <label>Source Environment URL</label>
                    <div class="ui input">
                        <input type="text" name="s_exp_url" placeholder="https://xola.com" required />
                    </div>
                </div>
                <div class="field">
                    <label>Admin Username</label>
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
                    <label>Destination Environment URL</label>
                    <div class="ui  input">
                        <input type="text" name="d_exp_url" placeholder="https://dev.xola.com" required />
                    </div>
                </div>

                <div class="field">
                    <label>New Seller Password</label>
                    <div class="ui  input">
                        <input type="password" name="d_password" placeholder="Seller account password" required />
                    </div>
                </div>

                <div class="field">
                    <label>Admin Username</label>
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['seller_username'], $_POST['s_exp_url'], $_POST['s_user_name'], $_POST['s_password'], $_POST['d_exp_url'], $_POST['d_user_name'], $_POST['d_password'], $_POST['d_admin_password'])) {
        include_once('xola-user-api.php');
        if ($enabled === TRUE) {
            echo "User is enabled, now fetching experiences.<br>";
            xola_exp_fetch_post();
        } else {
            echo "User Is not enabled.";
        }

    } else {
        echo '<div align="center">We are unable to proceed! Please Fill in the above details.</div>';
    }
}

function user_or_admin_api_fetch($exp_url, $user, $passwd)
{
    $ch_id = curl_init();
    curl_setopt($ch_id, CURLOPT_URL, $exp_url . '/api/users/me');
    curl_setopt($ch_id, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_id, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch_id, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_id, CURLOPT_USERPWD, $user . ':' . $passwd);
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
        curl_setopt($ch, CURLOPT_URL, $exp_url . '/api/users/' . $id . '/apiKey');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $user . ':' . $passwd);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data_api = curl_exec($ch);
        $response_api = json_decode($data_api, TRUE);
        curl_close($ch);
        return $response_api[0];
    }
}

function post_experience($experience, $environment_url, $api_key)
{
    unset($experience['seller']);
    unset($experience['photo']);
    unset($experience['medias']);
    $post_exp = json_encode($experience);

    echo "Importing " . $experience['name'] . "<br>";

    $curl_exp_post = curl_init();

    curl_setopt_array($curl_exp_post, array(
        CURLOPT_URL => $environment_url . '/api/experiences',
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
            "X-API-KEY: " . $api_key,
            "Accept: application/json"
        ),
    ));

    $response_exp_post = curl_exec($curl_exp_post);
    $err_exp_post = curl_error($curl_exp_post);
    curl_close($curl_exp_post);

    $decode_data = json_decode($response_exp_post, TRUE);

    if ($err_exp_post) {
        echo "cURL Error while posting experience #:" . $err_exp_post;
    } else {
        //$first = count($decode['data']);
        //print_r($_POST);
        if (!is_array($decode_data)) {
            echo "Invalid response received from destination server while posting experiences<br>";
            var_dump($decode_data);
            return;
        }

        if (!isset($decode_data['id'])) {
            echo "Experience ID is not present in response<br>";
            var_dump($decode_data);
            return;
        }

        if (!empty($experience['schedules'])) {
            $experience_id = $decode_data['id'];
            foreach ($experience['schedules'] as $schedule) {
                post_schedule($schedule, $environment_url, $experience_id, $api_key);
            }

        } else {
            return;
        }
    }
}

function post_schedule($post_schedule, $url, $exp_id, $api_key)
{
    $curl_exp_schedule = curl_init();

    curl_setopt_array($curl_exp_schedule, array(
        CURLOPT_URL => $url . '/api/experiences/' . $exp_id . '/schedules',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POSTFIELDS => json_encode($post_schedule),
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Cache-Control: no-cache",
            "X-API-KEY: " . $api_key,
            "Accept: application/json"
        ),
    ));

    $response_exp_schedule = curl_exec($curl_exp_schedule);
    $err_exp_schedule = curl_error($curl_exp_schedule);
    curl_close($curl_exp_schedule);

    //$decode = json_decode($response_exp_post, TRUE);
    if ($err_exp_schedule) {
        echo "cURL Error while posting schedules #:" . $err_exp_schedule;
    } else {
        //$first = count($decode['data']);
        //print_r($_POST);
    }

}

function xola_exp_fetch_post()
{
    $s_exp_url = $_POST['s_exp_url'];
    $d_exp_url = $_POST['d_exp_url'];
    $seller_username = $_POST['seller_username'];
    $d_password = $_POST['d_password'];
    $s_user_name = $_POST['s_user_name'];
    $s_password = $_POST['s_password'];

    $api_key = user_or_admin_api_fetch($d_exp_url, $seller_username, $d_password);

    $api_key_s = user_or_admin_api_fetch($s_exp_url, $s_user_name, $s_password);

    $curl_exp_fetch = curl_init();
    $total_experiences = 0;
    curl_setopt_array($curl_exp_fetch, array(
        CURLOPT_URL => $s_exp_url . '/api/experiences?seller=' . urlencode($seller_username) . '&admin=true&limit=100',
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
            "X-API-KEY: " . $api_key_s
        ),
    ));
    $response_exp_fetch = curl_exec($curl_exp_fetch);
    $err_exp_fetch = curl_error($curl_exp_fetch);
    $decode = json_decode($response_exp_fetch, TRUE);
    curl_close($curl_exp_fetch);

    if ($err_exp_fetch) {
        echo "cURL Error #:" . $err_exp_fetch;
        return;
    } else {
        if (!is_array($decode)) {
            echo "Invalid response received from source server while fetching experiences<br>";
            var_dump($decode);
            return;
        }

        if (!empty($decode['data'])) {
            foreach ($decode['data'] as $data) {
                post_experience($data, $d_exp_url, $api_key);
                $total_experiences++;
            }
            echo "Finished importing $total_experiences experiences from first api call<br>";
        } else {
            echo "There are no experiences to import.";
            return;
        }
    }

    if (isset($decode['paging']['next'])) {
        $page_url = $decode['paging']['next'];
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
                "X-API-KEY: " . $api_key_s
            ),
        ));
        $response_exp_next_fetch = curl_exec($curl_exp_next_fetch);
        $err_exp_next_fetch = curl_error($curl_exp_next_fetch);
        $decode_next = json_decode($response_exp_next_fetch, TRUE);
        curl_close($curl_exp_next_fetch);

        if ($err_exp_next_fetch) {
            echo "cURL Error while processing paginated data#:" . $err_exp_next_fetch;
        } else {
            if (!is_array($decode_next)) {
                echo "Invalid response from source server when fetching paginated data<br>";
                var_dump($decode_next);
                return;
            }

            if (!empty($decode_next['data'])) {
                foreach ($decode_next['data'] as $data_next) {
                    post_experience($data_next, $d_exp_url, $api_key);
                    $total_experiences++;
                }
            }
        }
    }

    echo '<div align="center">' . $total_experiences . ' Experiences Migrated</div>';
}

?>
</body>
</html>
