<?php


class baseController {
    function __construct()
    {
        new Session();
        if($flash=Flash::showflash())F3::set('flash', $flash);
        if($flashvars=Flash::unflashVariables())F3::set('flashvars', $flashvars);
    }
} 