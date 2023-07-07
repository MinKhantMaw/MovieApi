<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Movie Details Download</title>
</head>

<body>
    <h1>Movie Details</h1>
    <br>
    <p>Movie Title : {{ $movie->title }}</p>
    <p>Movie Summary : {{ $movie->summary }}</p>
    <p>Auhtor : {{ $movie->author }}</p>
    <p>Genres : {{ $movie->genres }}</p>
    <p>Rating : {{ $movie->imdb_rating }}</p>
    <p>Tags : {{ $movie->tags }}</p>
</body>

</html>
