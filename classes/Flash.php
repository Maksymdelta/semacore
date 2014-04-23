<?php





class Flash {

    /**
     * set a flash message
     * @param string flashmessage
     * @param string message status: alert, warning, or success
     * @return void
     */
    public static function setflash($flashmessage,$status = 'warning')
    {
//        $_SESSION['flash']['message'] = $flashmessage;
//        $_SESSION['flash']['status'] = $status;
        new Session();
        F3::set('SESSION.flash.message',$flashmessage);
        F3::set('SESSION.flash.status',$status);
    }

    /**
     * display a flash message
     * param void
     */
    public static function showflash()
    {
        new Session();
        $flash = NULL;
//        if (isset($_SESSION['flash'])) {
//            $flash['message'] = isset($_SESSION['flash']['message']) ? $_SESSION['flash']['message'] : NULL;
//            $flash['status'] = isset($_SESSION['flash']['status']) ? $_SESSION['flash']['status'] : NULL;
//        }

        if (F3::get('SESSION.flash')) {
            $flash['message'] = F3::get('SESSION.flash.message') ? F3::get('SESSION.flash.message') : NULL;
            $flash['status'] = F3::get('SESSION.flash.status') ? F3::get('SESSION.flash.status') : NULL;
        }

        F3::clear('SESSION.flash');
//        unset($_SESSION['flash']);
        return $flash;
    }

    public static function flashVariable($variableName,$value)
    {
        new Session();
        F3::set('SESSION.flashvar.'.$variableName,$value);
    }

    public static function unflashVariables()
    {
        new Session();
        if (F3::get('SESSION.flashvar')) {
            $flashVar=F3::get('SESSION.flashvar');
            F3::clear('SESSION.flashvar');
            return $flashVar;
        }

    }

    static function flashPost()
    {
        foreach(F3::get('POST') as $key=>$post)
        {
            self::flashVariable($key,$post);
        }
    }


} 