<form action="/parser/parse" method="post">
    <input placeholder="Site" name="site">
    <input placeholder="Max Level" name="max_level">
    <input placeholder="Max Emails" name="max_emails">
    <input type="submit" value="parse">
</form>

<table>
    <thead>
    <tr>
        <td>site</td>
        <td>count</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($parse_data as $item): ?>
        <tr>
            <td><a href="/parser/info/?site=<?php echo ($item['_id'])?>"><?php echo $item['_id'] ?></a></td>
            <td><?php echo $item['count'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
