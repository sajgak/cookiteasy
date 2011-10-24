<?php

function redirect($location){
    header('Location: '. get_instance()->config('main')->site_url . '/' . get_instance()->config('main')->script_name . '/' . $location);
}
