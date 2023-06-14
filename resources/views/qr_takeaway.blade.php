<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kassabon</title>
</head>
<body>
<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(700)->generate($menu_data)) !!} ">
</body>

<style>

    @page { margin: .5rem; }
    body { margin: .5rem; width: 100%; display: block; }

    * {
        font-size: 12px;
    }
</style>
</html>
