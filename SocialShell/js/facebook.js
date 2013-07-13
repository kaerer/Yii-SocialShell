var fb_global_response;

function fb_feed(link_text, caption, description, image, link, redirect_url, textarea) {

    if (textarea === undefined)
        textarea = ' ';

    FB.ui({
        method: 'feed',
        name: link_text,
        link: link,
        redirect_uri: (typeof redirect_url !== 'undefined') ? redirect_url : link,
        picture: image,
        source: image,
        caption: caption,
        description: description
//                message: textarea
    }, function (response) {
        if (response && response.post_id) {
            fb_feed_callback(response);
        } else {
            fb_feed_callback();
        }
    });
}

// Overwrite me !
function fb_feed_callback(response) {
    track('facebook', 'method.feed');
}

function fb_share_picture(text, image_url, album_id) {

    FB.api('/' + album_id ? album_id : 'me' + '/photos', 'post', {
            message: text,
            access_token: fb_access_token,
            url: image_url
        }, function (response) {
            if (response && response.post_id) {
                fb_share_picture_callback(response);
            } else {
                fb_share_picture_callback();
            }
        });
}

// Overwrite me !
function fb_share_picture_callback(response) {
    track('facebook', 'method.share_picture');
}

function fb_notification(text, title, data, to, max_recipients, exclude_ids) {
    var params = {
        method: 'apprequests',
        message: text
    };
    if (!title) {
        params['title'] = title;
    } else if (typeof appName !== 'undefined') {
        params['title'] = appName;
    }
    if (typeof to !== 'undefined' && !to) {
        params['to'] = to;
    }
    if (typeof exclude_ids === 'object' && !exclude_ids && exclude_ids.length > 0) {
        params['exclude_ids'] = exclude_ids;
    }
//    if (typeof redirect_uri !== 'undefined') {
//        params['redirect_uri'] = redirect_uri;
//    }
    if (typeof data !== 'undefined') {
        params['data'] = data;
    }

    if (typeof max_recipients !== 'undefined' && !max_recipients) {
        params['max_recipients'] = max_recipients;
    }

    FB.ui(params, function (response) {
        fb_notification_callback(response);
    });
}

// Overwrite me !
function fb_notification_callback(response) {
    track('facebook', 'method.notification');
}

function fb_send(link_text, description, image, link) {
    FB.ui({
            method: 'send',
            link: link,
            name: link_text,
            description: description,
            picture: image
        },
        function (response) {
            if (response && response.post_id) {
                fb_send_callback(response);
            } else {
                fb_send_callback();
            }
        });
}

// Overwrite me !
function fb_send_callback(response) {
    track('facebook', 'method.send');
}

function fb_check_login(callback_success, callback_error) {
    if (fb_unique_id && fb_loggedin) {
        fb_login_callback(fb_global_response, callback_success, callback_error);
        return true;
    } else {
        return fb_login(false, callback_success, callback_error);
    }
}

function fb_login(permissions, callback_success, callback_error) {
    //"display" must be one of "popup", "dialog", "iframe", "touch", "async", "hidden", or "none"
    FB.login(function (response) {
        fb_login_callback(response, callback_success, callback_error, true);
    }, {
        scope: (permissions ? permissions : fb_permissions)
//        , display: fb_loggedin ? 'dialog' : 'popup'
    });

    // dont trust, need to return after callback returns
//    return fb_loggedin;
}

// Overwrite me !
function fb_login_callback(response, callback_success, callback_error, disable_track) {
    fb_response_parser(response);
    if ((response && response.status === 'connected') || (fb_unique_id && fb_loggedin)) {
        fb_loggedin = true;
        fb_get_user_profile();
        run_callback(callback_success, response);
        if (!disable_track || !fb_disable_track)
            track('facebook', 'auth.yes', function () {
            });
    } else {
        fb_loggedin = false;
        if (callback_error) {
            run_callback(callback_error, response);
        } else {
            alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
        }
        //        track('facebook', 'auth.no', function(){});
    }
    return fb_loggedin;
}

function fb_get_user_profile() {
    if (fb_loggedin) {
        FB.api('/me', function (response) {
            fb_user_profile = response;
            fb_unique_id = response.id;
        });

        return fb_user_profile;
    }

}

function fb_get_user_profile_picture(unique_id, size)
{
    size = size || 'large';
    return '//graph.facebook.com/' + unique_id + '/picture?type=' + size;
}

// Overwrite me !
function fb_logout_callback(response) {
    window.location.reload();
}

// Overwrite me !
function fb_loginalready_callback(response) {
    fb_response_parser(response);
    fb_loggedin = false;
}

function fb_response_parser(response) {
    fb_loggedin = false;
    if (typeof response === 'object') {
        if (response.status === 'connected') {
            fb_loggedin = true;
            fb_global_response = response;
            fb_access_token = response.authResponse.accessToken;
            if (response.authResponse.userID)
                fb_unique_id = response.authResponse.userID;
            if (response.authResponse.signedRequest)
                fb_signed_request = response.authResponse.signedRequest;
            return true;
        }
    }
    return false;
}

// Overwrite me !
function fb_like_callback(response, disable_track) {
    fb_page_liked = true;
    if (typeof disable_track === 'undefined')
        track('facebook', 'like');
}

// Overwrite me !
function fb_unlike_callback(response, disable_track) {
    fb_page_liked = false;
    if (typeof disable_track === 'undefined')
        track('facebook', 'unlike');
}