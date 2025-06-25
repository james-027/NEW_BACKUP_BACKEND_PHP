<?php
namespace configuration;

class origin
{

    public function getOrigin($path,$icon)
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "https";
        $host = $_SERVER['HTTP_HOST'];
        $full_domain = $protocol."://" .$host."/digital_workspace/api/byte/get_icon.php?path=".$path."&file=".$icon;
        return $full_domain;
    }

}
