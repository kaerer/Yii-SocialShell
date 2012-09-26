<?php

/**
 * Description of Facebook Shell Plugin
 *
 * @author Erce Erözbek <erce.erozbek@gmail.com>
 *
 * @property Facebook $obj Instagram SDK Object
 * @property string $access_token Instagram SDK access token
 *
 * @property array $user_info Instagram user data
 */
class InstagramShell extends AbstractShell {

    const VERSION = 0.0;

    private $obj;
    private $access_token;

    private $user_info;

}

