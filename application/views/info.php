<a href="/parser/list">List</a>
<hr>
<ul>
<?php foreach ($site_data as $link=>$emails):?>
    <li><?php echo $link?></li>
    <ul>
    <?php foreach ($emails as $email):?>
        <li><?php echo $email?></li>
    <?php endforeach;?>
    </ul>
<?php endforeach;?>
</ul>
