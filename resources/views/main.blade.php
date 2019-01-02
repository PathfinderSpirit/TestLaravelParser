<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>HabrParser</title>
    <link href="{{asset('css/app.css')}}"rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="https://habr.com"><h1>Original Habr</h1> <span class="sr-only">(current)</span></a>
            </li>
        </ul>
      </div>
    </nav>

    <main role="main" id="main" class="container">
    <div class="row">
        <a class="btn btn-primary" href="/desc" role="button">Сначала свежие посты</a>
        <a class="btn btn-primary" href="/asc" role="button">Сначала старые посты</a>
    </div>
    @foreach($posts as $post)
       <article>
            <h1> {{ $post->title}}</h1> 
            <h3> {{ $post->created_at}}</h3>
            <p> {{$post->text}}</p>
        </article>
    @endforeach
    {{ $posts->links() }}
    </main>
</body>
</html>