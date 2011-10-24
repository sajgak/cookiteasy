<html>
    <head>
        <title>Admin Panel</title>
        <script>
        var root = "<?=$this->config('main')->script_name?>";
        var static_domain = "<?=$this->config('main')->static_domain?>";
        </script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
        <script src="<?=$this->config('main')->static_domain?>/backend/js/site.js"></script>
    </head>
    <body>
        <div id="top_menu">
            <a href="<?=$this->config('main')->site_url?>/<?=$this->config('main')->script_name?>/errors">Errors</a> | 
            <a href="<?=$this->config('main')->site_url?>/<?=$this->config('main')->script_name?>/main/logout">Logout</a>
        </div>