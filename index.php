<?php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servidor Local - Profe Osvaldo Cantone</title>
  <style>
    :root {
      --bg: #f5f7fa;
      --card-bg: #ffffff;
      --accent: #0077cc;
      --accent-hover: #005fa3;
      --text: #222;
      --shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    body.dark {
      --bg: #1c1f26;
      --card-bg: #2a2e38;
      --accent: #3399ff;
      --accent-hover: #66b3ff;
      --text: #f0f0f0;
      --shadow: 0 2px 10px rgba(0,0,0,0.6);
    }

    * {
      box-sizing: border-box;
      font-family: "Segoe UI", Roboto, sans-serif;
    }

    body {
      margin: 0;
      background: var(--bg);
      color: var(--text);
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      transition: background 0.3s, color 0.3s;
    }

    header {
      background: var(--accent);
      color: white;
      text-align: center;
      padding: 1.5rem 1rem 1rem 1rem;
      position: relative;
    }

    header img {
      max-width: 120px;
      margin-bottom: 0.5rem;
      filter: brightness(100%);
    }

    h1 {
      margin: 0.2em 0;
      font-size: 1.8rem;
    }

    #theme-toggle {
      position: absolute;
      top: 15px;
      right: 15px;
      background: rgba(255,255,255,0.2);
      color: white;
      border: none;
      border-radius: 20px;
      padding: 6px 12px;
      cursor: pointer;
      font-size: 0.9rem;
      transition: background 0.3s;
    }

    #theme-toggle:hover {
      background: rgba(255,255,255,0.35);
    }

    main {
      flex: 1;
      display: grid;
      gap: 1.5rem;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      padding: 2rem;
      max-width: 1000px;
      margin: auto;
      transition: all 0.3s;
    }

    .card {
      background: var(--card-bg);
      border-radius: 12px;
      box-shadow: var(--shadow);
      padding: 1.5rem;
      text-align: center;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .card h2 {
      margin-top: 0.6em;
      color: var(--accent);
    }

    a.button {
      display: inline-block;
      background: var(--accent);
      color: white;
      padding: 0.5rem 1.2rem;
      border-radius: 6px;
      text-decoration: none;
      margin-top: 0.8rem;
      transition: background 0.2s;
    }

    a.button:hover {
      background: var(--accent-hover);
    }

    .icon {
      width: 50px;
      height: 50px;
      opacity: 0.85;
      transition: opacity 0.2s;
    }
    .icon2 {
      width: 200px;
      height: 50px;
      opacity: 0.85;
      transition: opacity 0.2s;
    }

    .card:hover .icon {
      opacity: 1;
    }
    .card:hover .icon2 {
      opacity: 1;
    }

    footer {
      text-align: center;
      padding: 1rem;
      background: #eaeef3;
      font-size: 0.9rem;
      transition: background 0.3s;
    }

    body.dark footer {
      background: #2a2e38;
    }
  </style>
</head>
<body>
  <header>
    <button id="theme-toggle">🌙 Modo oscuro</button>
      <h1>Servidor Local - Profe Osvaldo Cantone</h1>
    <p>Acceso a materiales y laboratorios</p>
  </header>

  <main>
    <!-- Fila 1: CCNA -->
    <div class="card">
      <img class="icon2" src="images/UNM.jpg" alt="Redes 1B UNM">
      <h2>Redes 1B UNM</h2>
      <p>Redes 1B en UNM</p>
      <a class="button" href="http://redes1b.unm.local">Abrir</a>
    </div>

    <div class="card">
      <img class="icon2" src="images/utntup.jpg" alt="Programación 3 UTN TUP">
      <h2>Programaci&oacute;n 3 en UTN TUP</h2>
      <p>Mobile: NodeJS - React Native </p>
      <a class="button" href="http://prog3.utn.local">Abrir</a>
    </div>

  </main>

  <footer>
    &copy; 2025 Servidor local de Osvaldo Cantone – Todos los derechos reservados.
  </footer>

  <script>
    const toggleBtn = document.getElementById('theme-toggle');
    const body = document.body;

    // Leer preferencia previa (si existe)
    if (localStorage.getItem('theme') === 'dark') {
      body.classList.add('dark');
      toggleBtn.textContent = '☀️ Modo claro';
    }

    toggleBtn.addEventListener('click', () => {
      body.classList.toggle('dark');
      const darkMode = body.classList.contains('dark');
      toggleBtn.textContent = darkMode ? '☀️ Modo claro' : '🌙 Modo oscuro';
      localStorage.setItem('theme', darkMode ? 'dark' : 'light');
    });
  </script>
</body>
</html>