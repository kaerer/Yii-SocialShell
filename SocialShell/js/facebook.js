var fb_global_response;

function fb_feed(link_text, caption, description, image, link, textarea){

    if(textarea === undefined) textarea = '';

    FB.ui(
    {
        method: 'feed',
        name: link_text,
        link: link,
        picture: image,
        caption: caption,
        description: description,
        message: textarea
    },
    function(response) {
        if (response && response.post_id) {
            fb_feed_callback(response);
        } else {
            fb_feed_callback();
        }
    });
}

// Overwrite me !
function fb_feed_callback(response){
    track('facebook', 'post.send');
}

function fb_notification(text, title, redirect_uri, data){
    var params = {
        method: 'apprequests',
        message: text
    };
    if(typeof title !== 'undefined'){
        params['title'] = title;
    } else if(typeof appName !== 'undefined'){
        params['title'] = appName;
    }
    if(typeof redirect_uri !== 'undefined'){
        params['redirect_uri'] = redirect_uri;
    }
    if(typeof data !== 'undefined'){
        params['data'] = data;
    }
    FB.ui(params, function(response){fb_notification_callback(response);});
}

// Overwrite me !
function fb_notification_callback(response){
    track('facebook', 'notification.send');
}

function fb_check_login(callback_success, callback_error){
    if(fb_unique_id && fb_loggedin) {
        fb_login_callback(fb_global_response, callback_success, callback_error);
        return true;
    } else {
        fb_login(false, callback_success, callback_error);
        return false;
    }
}

function fb_login(permissions, callback_success, callback_error){
    FB.login(function(response) {
        fb_response_parser(response);
        fb_login_callback(response, callback_success, callback_error);
    }, {
        scope:(permissions ? permissions : fb_permissions)
    //        display: loggedin ? 'iframe' : 'page' //page, popup, iframe, or touch
    });
//    }, {scope:fb_permissions});
}

// Overwrite me !
function fb_login_callback(response, callback_success, callback_error){
    if(response && response.status === 'connected') {
//        alert('İzinler alındı');
        track('facebook', 'auth.yes');
        run_callback(callback_success);
    } else {
        alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
        track('facebook', 'auth.no');
        run_callback(callback_error);
    }
}

// Overwrite me !
function fb_logout_callback(response){
    window.location.reload();
}

// Overwrite me !
function fb_loginalready_callback(response){
    fb_response_parser(response);
}

function fb_response_parser(response){
    fb_loggedin = false;
    if (typeof response === 'object') {
        if (response.status === 'connected') {
            fb_loggedin       = true;
            fb_global_response = response;
            fb_access_token   = response.authResponse.accessToken;
            fb_unique_id      = response.authResponse.userID;
            fb_signed_request = response.authResponse.signedRequest;
        }
    }
}

// Overwrite me !
function fb_like_callback(response){
    fb_page_liked = true;
    track('facebook', 'like');
//    console.log('like' + page_liked);
}

// Overwrite me !
function fb_unlike_callback(response){
    fb_page_liked = false;
    track('facebook', 'unlike');
//    console.log('unlike' + page_liked);
}