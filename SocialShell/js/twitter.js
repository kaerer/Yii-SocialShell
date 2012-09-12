function tw_login(url){

    open_popup(url, 800, 600, 'twitter_login')

    tw_login_callback();
    return false;
}

function tw_share(text){
    var url	= 'http://twitter.com/?status=' + encodeURIComponent(text);

    open_popup(url, 600, 300, 'twitter')

    tw_share_callback();
    return false;
}

function tw_retweet(tweet_id){
    var url	= 'https://twitter.com/intent/retweet?tweet_id=' + tweet_id;

    open_popup(url, 550, 400, 'twitter')

    tw_retweet_callback(tweet_id);
    return false;
}

function tw_favorite(tweet_id){
    var url	= 'https://twitter.com/intent/favorite?tweet_id=' + tweet_id;

    open_popup(url, 550, 420, 'twitter')

    tw_favorite_callback(tweet_id);
    return false;
}

function tw_reply(tweet_id, text){
    if(!text) text = '#sosyalliderlergundemi';
    var url	= 'https://twitter.com/intent/tweet?in_reply_to=' + tweet_id + (text ? '&text=' + encodeURIComponent(text): '');

    open_popup(url, 550, 400, 'twitter')

    tw_reply_callback(tweet_id);
    return false;
}

// Overwrite me !
function tw_login_callback(){
    track('tw_login');
}

// Overwrite me !
function tw_share_callback(){
    track('tw_share');
}

// Overwrite me !
function tw_retweet_callback(tweet_id){
    track('tw_retweet', tweet_id);
}

// Overwrite me !
function tw_favorite_callback(tweet_id){
    track('tw_favorite', tweet_id);
}

// Overwrite me !
function tw_reply_callback(tweet_id){
    track('tw_reply', tweet_id);
}