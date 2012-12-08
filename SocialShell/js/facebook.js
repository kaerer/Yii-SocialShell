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
    track('wall_share');
}

function fb_notification(text){
    FB.ui({
        method: 'apprequests',
        message: text
    },
    function(response){
        if (response) {
            fb_notification_callback(response);
        } else {
            fb_notification_callback();
        }
    });
}

// Overwrite me !
function fb_notification_callback(response){
    track('friend_notification');
}

function fb_check_login(){
    if(fb_unique_id && fb_loggedin) {
        fb_login_callback(fb_global_response);
        return true;
    } else {
        fb_login(false);
        return false;
    }
}

function fb_login(permissions){
    FB.login(function(response) {
        fb_response_parser(response);
        fb_login_callback(response);
    }, {
        scope:(permissions ? permissions : fb_permissions)
//        display: loggedin ? 'iframe' : 'page' //page, popup, iframe, or touch
    });
//    }, {scope:fb_permissions});
}

// Overwrite me !
function fb_login_callback(response){
    if(response && response.status === 'connected') {
        alert('İzinler alındı');
    } else {
        alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
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
    fb_global_response = response;
    fb_loggedin = false;
    if (typeof response.authResponse !== 'undefined') {
        fb_access_token   = response.authResponse.accessToken;
        if (response.status === 'connected') {
            fb_loggedin       = true;
            fb_unique_id      = response.authResponse.userID;
            fb_signed_request = response.authResponse.signedRequest;
        }
    }
}

// Overwrite me !
function fb_like_callback(response){
    fb_page_liked = true;
//    console.log('like' + page_liked);
}

// Overwrite me !
function fb_unliked_callback(response){
    fb_page_liked = false;
//    console.log('unlike' + page_liked);
}