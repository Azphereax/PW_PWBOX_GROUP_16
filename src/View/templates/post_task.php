
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add task</title>
</head>
<body>
    <form action="/task" method="POST" id="f1">
        <input type="text" name="title" id="t1" placeholder="title" maxlength=19 required>
        <input type="text" name="content" id="c1" placeholder="content" required>
        <input type="submit" value="send">
    </form>
</body>
</html>
