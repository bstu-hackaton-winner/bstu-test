<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подключение к опросу</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/css/online_quiz.css" />
    <script src="https://kit.fontawesome.com/54224cf497.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6 form-container">
            <form class="form-horizontal" method="POST" action="offline.php">
                <span class="heading">Подключение к опросу</span>
                <div class="form-group">
                    <input type="number" name="quiz_id" class="form-control" id="inputEmail" placeholder="Код опроса">
                    <i class="fa fa-key"></i>
                </div>
                <div class="form-group send">
                    <button type="submit" class="btn button btn-default">Подключиться</button>
                    <a class="action-link" href="/index.php">Вход для организаторов</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>