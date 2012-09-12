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

function check_login(){
    if(fid && loggedin) {
        login_callback(response_global);
        return true;
    } else {
        fb_login(false);
        return false;
    }
}

function fb_login(permissions){
    FB.login(function(response) {
        //        console.log(response);
        if (response.authResponse) {
            accessToken = response.authResponse.accessToken;
            if (response.status == 'connected') {
                loggedin = true;
                fid = response.authResponse.userID;
                access_token = response.authResponse.accessToken;
                signed_request = response.authResponse.signedRequest;
            } else {
                loggedin = false;
            }
        } else {
            // user is not logged in
            loggedin = false;
        }
        login_callback(response);
    }, {
        scope:(permissions ? permissions : req_perms),
        display: 'iframe' //popup, iframe, page
    });
//    }, {scope:req_perms});
}

// Overwrite me !
function login_callback(response){
    if(response.status == 'connected') {
        alert('İzinler alındı');
    } else {
        alert('Katılabilmek için uygulamamıza izin vermelisiniz.');
    }
}

// Overwrite me !
function liked(response){
    page_liked = true;
    console.log('like' + page_liked);
}

// Overwrite me !
function unliked(response){
    page_liked = false;
//    console.log('unlike' + page_liked);
}

function loading(aim, timeout){
    if(timeout === null) timeout = 500;
    switch(aim){
        case true:
            $('#loading').css('display', 'block');
            break;
        case false:
            setTimeout(function() {
                $('#loading').css('display', 'none');
            }, timeout);
            break;
    }
}

// Will be overwritter in _fb.php or somewhere else
function track(type, id){
    id = !id ? 0 : id;
    var post_data = {
        't': type,
        'id': id
    }

    $.ajax({
        type    : 'POST',
        data    : post_data,
        url     : controller + '/track/',
        //url     : '/' + controller + '/track/',
        success : function() {  }
    });
}

function show_jdialog(text, dl_title){
    if(dl_title == null) dl_title = appName;
    $('#popup_error').html(text);
    $('#popup_error').dialog({
        width: 260,
        height: 140,
        modal: true,
        title: dl_title
    });
}

function ajax_error(text){
    text = text === null ? '' : text;
    show_jdialog('İnternet bağlantınızla ilgili bir sorun oluştu, lütfen daha sonra tekrar deneyin.<br><br>', 'Sorun oluştu');
}

function print_r(theObj, return_data){
    var html;
    if(theObj.constructor == Array ||
        theObj.constructor == Object){
        html += '<ul>';
        for(var p in theObj){
            if(theObj[p].constructor == Array || theObj[p].constructor == Object){
                /*
                document.write("<li>["+p+"] => "+typeof(theObj)+"</li>");
                document.write("<ul>")
                print_r(theObj[p]);
                document.write("</ul>")
                 */
                html += "<li>["+p+"] => "+typeof(theObj[p])+"";
                html += "<br>["+p+"] => "+print_r(theObj[p], true);
                html += "</li>";
            } else {
                html += "<li>["+p+"] => "+theObj[p]+"</li>";
            }
        }
        html += "</ul>";
    }
    $('#popup_debug').html(html);
    $('#popup_debug').dialog({
        width: 260,
        height: 140,
        modal: true,
        title: 'Debug'
    });


}

function open_popup(url, w, h, name){

    var name = name ? name : 'popup_' + rand(10, 99);

    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);

    var popupx  = window.open(url,name,'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
    if (window.focus)
    {
        popupx.focus();
    }

}

function rand (min, max) {
    // Returns a random number
    //
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/rand    // +   original by: Leslie Hoare
    // +   bugfixed by: Onno Marsman
    // %          note 1: See the commented out code below for a version which will work with our experimental (though probably unnecessary) srand() function)
    // *     example 1: rand(1, 1);
    // *     returns 1: 1    var argc = arguments.length;
    if (argc === 0) {
        min = 0;
        max = 2147483647;
    } else if (argc === 1) {
        throw new Error('Warning: rand() expects exactly 2 parameters, 1 given');
    }
    return Math.floor(Math.random() * (max - min + 1)) + min;
}