var in_login_popup;
var tmp_callback = {
    'success': false,
    'error': false
};

function in_check_login(callback_success, callback_error){
    tmp_callback.success = callback_success;
    tmp_callback.error = callback_error;
    in_login();
}

function in_login(){
    if (typeof in_login_popup !== 'undefined' && !in_login_popup.closed) {
        in_login_popup.focus();
    } else {
        in_login_popup = open_popup(in_login_url, 600, 400, 'in_login_popup');
    }
}

//callback_obj = array(
//    'active' => false,
//    'data' => false,
//    'code' => false,
//    'error' => false,
//    'raw' => $callback_params,
//);

// Overwrite me !
function in_login_callback(callback_obj){
    in_login_popup.close();
    if(callback_obj.error !== false){
        if(tmp_callback.error){
            run_callback(tmp_callback.error);
        } else {
            if(callback_obj.error === 'user_denied') {
                alert('Uygulamamıza katılabilmek için izin vermelisiniz.');
            }
            else {
                alert(callback_obj.error);
            }
        }
    } else if(callback_obj.code !== false){
        if(tmp_callback.success) {
            run_callback(tmp_callback.success);
        } else {
            alert('Hoşgeldiniz');
        }
    }
}