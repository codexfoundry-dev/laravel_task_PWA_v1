<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="manifest" href="/manifest.webmanifest" />
    @vite(['resources/js/app.tsx','resources/js/styles.css'])
    @inertiaHead
  </head>
  <body class="antialiased">
    @inertia
  </body>
</html>