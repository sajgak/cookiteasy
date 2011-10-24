<?$this->view('parts/top')?>
<? foreach($errors as $error):?>
<div style="border:1px solid #990000;padding-left:20px;margin:0 0 10px 0;">
        <h4>An Error was encountered</h4>

        <p><b>Severity:</b> <?=$error['severity']?></p>
        <p><b>Message:</b>  <?=$error['message']?></p>
        <p><b>Filename:</b> <?=$error['file']?></p>
        <p><b>Line Number:</b> <?=$error['line']?></p>
        <p><b>Date:</b> <?=date('d.m.Y G:i:s', $error['date'])?></p>
        <p><b>Backtrase:</b> <pre><?=$error['trace']?></pre></p>

</div>
<?endforeach;?>
<?$this->view('parts/footer')?>