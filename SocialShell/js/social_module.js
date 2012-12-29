
// Will be overwritter in _fb.php or somewhere else
function track(platform, action, object_id){
    if(typeof _gaq === 'object'){
        var targetUrl = object_id ? object_id : false;
        ga_social_track(platform, action, targetUrl);
    } else {
        object_id = !object_id ? 0 : object_id;
        var post_data = {
            't': action,
            'id': object_id
        }

        $.ajax({
            type    : 'POST',
            data    : post_data,
            url     : controller + '/track/',
            //url     : '/' + controller + '/track/',
            success : function() {}
        });
    }
}

function ga_social_track(platform, action, targetUrl){
    _gaq.push(['_trackSocial', platform, action, targetUrl]);
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

    if(typeof w === 'undefined') w = 600;
    if(typeof h === 'undefined') h = 400;
    if(typeof name === 'undefined') 'popup_' + rand(10, 99);

    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);

    var popupx  = window.open(url, name, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
    if (window.focus)
    {
        popupx.focus();
    }

    return popupx;
}

function rand(min, max) {
    // Returns a random number
    //
    // version: 1109.2015
    // discuss at: http://phpjs.org/functions/rand
    // +   original by: Leslie Hoare
    // +   bugfixed by: Onno Marsman
    // %          note 1: See the commented out code below for a version which will work with our experimental (though probably unnecessary) srand() function)
    // *     example 1: rand(1, 1);
    // *     returns 1: 1    var argc = arguments.length;
    if(typeof min === 'undefined') min = 0;
    if(typeof max === 'undefined') max = 2147483647;
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function run_callback(callback){
    if(typeof callback === 'function'){
        return callback();
    }
    return false;
}