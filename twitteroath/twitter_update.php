if(isset($user_info->error)){
    // Something's wrong, go back to square 1
    header('Location: twitter_login.php');
} else {
    // Let's find the user by its ID
    $query = mysql_query("SELECT * FROM users WHERE oauth_provider = 'twitter' AND oauth_uid = ". $user_info->id);
    $result = mysql_fetch_array($query);
 
    // If not, let's add it to the database
    if(empty($result)){
        $query = mysql_query("INSERT INTO users (oauth_provider, oauth_uid, username, oauth_token, oauth_secret) VALUES ('twitter', {$user_info->id}, '{$user_info->screen_name}', '{$access_token['oauth_token']}', '{$access_token['oauth_token_secret']}')");
        $query = mysql_query("SELECT * FROM users WHERE id = " . mysql_insert_id());
        $result = mysql_fetch_array($query);
    } else {
        // Update the tokens
        $query = mysql_query("UPDATE users SET oauth_token = '{$access_token['oauth_token']}', oauth_secret = '{$access_token['oauth_token_secret']}' WHERE oauth_provider = 'twitter' AND oauth_uid = {$user_info->id}");
    }
 
    $_SESSION['id'] = $result['id'];
    $_SESSION['username'] = $result['username'];
    $_SESSION['oauth_uid'] = $result['oauth_uid'];
    $_SESSION['oauth_provider'] = $result['oauth_provider'];
    $_SESSION['oauth_token'] = $result['oauth_token'];
    $_SESSION['oauth_secret'] = $result['oauth_secret'];
 
    header('Location: twitter_update.php');
}