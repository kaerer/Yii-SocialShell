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

function fb_notification(text, title, to, data, redirect_uri){
    var params = {
        method: 'apprequests',
        message: text
    };
    if(typeof title !== 'undefined'){
        params['title'] = title;
    } else if(typeof appName !== 'undefined'){
        params['title'] = appName;
    }
    if(typeof to !== 'undefined'){
        params['to'] = to;
    }
    if(typeof redirect_uri !== 'undefined'){
        params['redirect_uri'] = redirect_uri;
    }
    if(typeof data !== 'undefined'){
        params['data'] = data;
    }

    console.log(params);
    FB.ui(params, function(response){
        fb_notification_callback(response);
    });
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
        fb_login_callback(response, callback_success, callback_error, true);
    }, {
        scope:(permissions ? permissions : fb_permissions)
    //, display: loggedin ? 'iframe' : 'page' //page, popup, iframe, or touch
    });
}

// Overwrite me !
function fb_login_callback(response, callback_success, callback_error, disable_track){
    if(response && response.status === 'connected') {
        //        alert('İzinler alındı');
        run_callback(callback_success);
        if(typeof disable_track === 'undefined') track('facebook', 'auth.yes', function(){});
    } else {
        if(callback_error){
            run_callback(callback_error);
        } else {
            alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
        }
    //        track('facebook', 'auth.no', function(){});
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
            if(response.authResponse.userID) fb_unique_id      = response.authResponse.userID;
            if(response.authResponse.signedRequest) fb_signed_request = response.authResponse.signedRequest;
        }
    }
}

// Overwrite me !
function fb_like_callback(response, disable_track){
    fb_page_liked = true;
    if(typeof disable_track === 'undefined') track('facebook', 'like');
}

// Overwrite me !
function fb_unlike_callback(response, disable_track){
    fb_page_liked = false;
    if(typeof disable_track === 'undefined') track('facebook', 'unlike');
}