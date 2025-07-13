<html>
<body>
  <h2>Dziękujemy za wysłanie plików!</h2>
  <p>{{ $customMessage ?? 'Twoje pliki zostały przesłane do konkursu.' }}</p>
  <ul>
    @foreach($files as $file)
      <li>{{ $file->original_name }}</li>
    @endforeach
  </ul>
  <p>Pozdrawiamy,<br>Zespół Konkursu</p>
</body>
</html> 