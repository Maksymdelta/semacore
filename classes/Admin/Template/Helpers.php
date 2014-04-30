<?php

namespace Admin\Template;

class Helpers {
    static public function handlebars($args) {
        $attr = $args['@attrib'];
        $id   = $attr['id'];
        $html = (isset($args[0])) ? $args[0] : '';

        return "<script id=\"$id\" type=\"text/x-handlebars-template\">$html</script>";
    }
}

