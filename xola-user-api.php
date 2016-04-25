<?php
function admin_api_fetch()
{
    $d_exp_url = $_POST['d_exp_url'];
    $d_username = $_POST['d_user_name'];
    $d_admin_password = $_POST['d_admin_password'];
    $ch_d_id = curl_init();
    curl_setopt($ch_d_id, CURLOPT_URL, $d_exp_url . '/api/users/me');
    curl_setopt($ch_d_id, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_d_id, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch_d_id, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_d_id, CURLOPT_USERPWD, $d_username . ':' . $d_admin_password);
    curl_setopt($ch_d_id, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch_d_id, CURLOPT_SSL_VERIFYPEER, 0);
    $data_d = curl_exec($ch_d_id);
    $response_d = json_decode($data_d, TRUE);
    $err_d = curl_error($ch_d_id);
    curl_close($ch_d_id);
    if ($err_d) {
        echo "cURL Error #:" . $err_d;
    } else {
        $id = $response_d['id'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $d_exp_url . '/api/users/' . $id . '/apiKey');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $d_username . ':' . $d_admin_password);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data_api = curl_exec($ch);
        $response_api = json_decode($data_api, TRUE);
        curl_close($ch);
        return $response_api[0];
    }
}
$i = $_POST['d_exp_url'];
$j = $_POST['seller_username'];
$apiKey_user = admin_api_fetch();
$ch_id = curl_init();
curl_setopt($ch_id, CURLOPT_URL, $i . '/api/users?private=true&type=1&limit=100&search=' . $j);
curl_setopt($ch_id, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch_id, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch_id, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch_id, CURLOPT_HTTPHEADER, array("x-api-key:" . $apiKey_user));
$data_users = curl_exec($ch_id);
$response = json_decode($data_users, TRUE);
$err = curl_error($ch_id);
curl_close($ch_id);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $id = $response['data'][0]['id'];
    $ch_enable = curl_init();
    curl_setopt($ch_enable, CURLOPT_URL, $i . '/api/users/' . $id . '/enabled');
    curl_setopt($ch_enable, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch_enable, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch_enable, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch_enable, CURLOPT_HTTPHEADER, array("x-api-key:" . $apiKey_user));
    $data = curl_exec($ch_enable);
    $err = curl_error($ch_enable);
    curl_close($ch_enable);
    $enabled = TRUE;
    return $enabled;
}
?>