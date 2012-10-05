function in_check_login(){
}

var in_login_popup;
function in_login(){
    in_login_popup = open_popup(in_login_url, 600, 400, in_login_popup);
}

// Overwrite me !
function in_login_callback(callback_obj){
    in_login_popup.close();
    if(callback_obj.error !== false){
        if(callback_obj.error === 'user_denied') alert('Uygulamamıza katılabilmek için izin vermelisiniz.');
        else alert(callback_obj.error);
    } else if(callback_obj.code !== false){
        alert('Hoşgeldiniz');
    }
}