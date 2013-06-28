var fb_global_response;
function fb_feed(_1,_2,_3,_4,_5,_6,_7){
if(_7===undefined){
_7="";
}
FB.ui({method:"feed",name:_1,link:_5,redirect_uri:(typeof _6!=="undefined")?_6:_5,picture:_4,caption:_2,description:_3},function(_8){
if(_8&&_8.post_id){
fb_feed_callback(_8);
}else{
fb_feed_callback();
}
});
}
function fb_feed_callback(_9){
track("facebook","method.feed");
}
function fb_notification(_a,_b,_c,to,_e){
var _f={method:"apprequests",message:_a};
if(!_b){
_f["title"]=_b;
}else{
if(typeof appName!=="undefined"){
_f["title"]=appName;
}
}
if(typeof to!=="undefined"&&!to){
_f["to"]=to;
}
if(typeof _c!=="undefined"){
_f["data"]=_c;
}
if(typeof _e!=="undefined"&&!_e){
_f["max_recipients"]=_e;
}
FB.ui(_f,function(_10){
fb_notification_callback(_10);
});
}
function fb_notification_callback(_11){
track("facebook","method.notification");
}
function fb_send(_12,_13,_14,_15){
FB.ui({method:"send",link:_15,name:_12,description:_13,picture:_14},function(_16){
if(_16&&_16.post_id){
fb_send_callback(_16);
}else{
fb_send_callback();
}
});
}
function fb_send_callback(_17){
track("facebook","method.send");
}
function fb_check_login(_18,_19){
if(fb_unique_id&&fb_loggedin){
fb_login_callback(fb_global_response,_18,_19);
return true;
}else{
fb_login(false,_18,_19);
return false;
}
}
function fb_login(_1a,_1b,_1c){
FB.login(function(_1d){
fb_loggedin=true;
fb_login_callback(_1d,_1b,_1c,true);
},{scope:(_1a?_1a:fb_permissions)});
}
function fb_login_callback(_1e,_1f,_20,_21){
fb_response_parser(_1e);
if((_1e&&_1e.status==="connected")||(fb_unique_id&&fb_loggedin)){
fb_get_user_profile();
run_callback(_1f,_1e);
if(typeof _21==="undefined"){
track("facebook","auth.yes",function(){
});
}
}else{
if(_20){
run_callback(_20,_1e);
}else{
alert("Kat\u0131labilmek i\xe7in uygulamam\u0131za izin vermelisiniz.");
}
}
}
function fb_get_user_profile(){
if(fb_loggedin){
FB.api("/me",function(_22){
fb_user_profile=_22;
fb_unique_id=_22.id;
});
return fb_user_profile;
}
}
function fb_logout_callback(_23){
window.location.reload();
}
function fb_loginalready_callback(_24){
fb_response_parser(_24);
fb_loggedin=false;
}
function fb_response_parser(_25){
fb_loggedin=false;
if(typeof _25==="object"){
if(_25.status==="connected"){
fb_loggedin=true;
fb_global_response=_25;
fb_access_token=_25.authResponse.accessToken;
if(_25.authResponse.userID){
fb_unique_id=_25.authResponse.userID;
}
if(_25.authResponse.signedRequest){
fb_signed_request=_25.authResponse.signedRequest;
}
return true;
}
}
return false;
}
function fb_like_callback(_26,_27){
fb_page_liked=true;
if(typeof _27==="undefined"){
track("facebook","like");
}
}
function fb_unlike_callback(_28,_29){
fb_page_liked=false;
if(typeof _29==="undefined"){
track("facebook","unlike");
}
}
