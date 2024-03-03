<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/podcasts.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../../js/nav.js" defer></script>
    <script src="../../js/podcasts.js" defer></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <title>BrainWave | Podcasts</title>
</head>

<?php

require_once '../bases_de_datos/conecta.php';
require_once '../bases_de_datos/tablas.php';

?>

<body>

    <nav>
        <div class="nav_container">

            <div class="nav_inner">

                <div class="logo">
                    <a href="home.php">
                        <img src="../../img/logo.png" alt="">
                    </a>
                </div>
                <div class="lista">

                    <ul>
                        <a href="servicios.php">
                            <li>Servicios</li>
                        </a>
                        <a href="recursos.php">
                            <li style="color: #7E42FB;">Recursos</li>
                        </a>
                        <a href="nosotros.php">
                            <li>Nosotros</li>
                        </a>
                        <div class="div_contacto">
                            <a href="contacto.php">
                                <li>Contacto</li>
                            </a>
                        </div>

                    </ul>

                </div>

                <div class="perfil">
                    <a href="<?php
                                session_start();
                                if (isset($_SESSION["tipo"])) {
                                    echo "perfil.php";
                                } else {
                                    echo "login.php";
                                }
                                session_write_close();
                                ?>"><i class="fas fa-user-circle fa-2x" id="color_perfil"></i></a>
                </div>

            </div>

        </div>
    </nav>

    <main>

        <div class="titulo">
            <h1>Podcasts</h1>
        </div>

        <div class="section1">
            <div class="volver">
                <a href="recursos.php">
                    <svg xmlns="http://www.w3.org/2000/svg" height="30" width="30" viewBox="0 0 512 512">
                        <path fill="#ffffff" d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM231 127c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-71 71L376 232c13.3 0 24 10.7 24 24s-10.7 24-24 24l-182.1 0 71 71c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L119 273c-9.4-9.4-9.4-24.6 0-33.9L231 127z" />
                    </svg>
                    <p>Volver</p>
                </a>
            </div>
            <div class="error">
                <p class="error_text"></p>
                <i class="fa fa-exclamation-circle"></i>
            </div>
            <div class="inner_section">
                <div class="buscador">
                    <form>
                        <input type="text" name="buscador" id="buscador" placeholder="Buscar" value="<?php if (isset($_GET['buscador'])) echo $_GET['buscador']; ?>" oninput="buscarPodcasts()">
                    </form>
                </div>
                <div class="podcast_container">

                    <?php

                    $conexion = obtenerConexion();

                    // Verificar si se ha enviado una consulta de búsqueda
                    if (isset($_GET['buscador'])) {
                        $busqueda = $_GET['buscador'];

                        // Realiza la consulta para obtener podcasts con enlaces que coincidan con la búsqueda
                        $query_podcasts_enlaces = "SELECT podcasts.*, imagenes.ruta AS imagen_ruta 
                        FROM podcasts 
                        JOIN relacion_podcast_imagen ON podcasts.id_podcast = relacion_podcast_imagen.id_podcast 
                        JOIN imagenes ON relacion_podcast_imagen.id_imagen = imagenes.id_imagen
                        WHERE podcasts.titulo LIKE '%$busqueda%' OR podcasts.autor LIKE '%$busqueda%'";
                    } else {
                        // Consulta sin búsqueda, obtener todos los podcasts con enlaces
                        $query_podcasts_enlaces = "SELECT podcasts.*, imagenes.ruta AS imagen_ruta 
                        FROM podcasts 
                        JOIN relacion_podcast_imagen ON podcasts.id_podcast = relacion_podcast_imagen.id_podcast 
                        JOIN imagenes ON relacion_podcast_imagen.id_imagen = imagenes.id_imagen";
                    }

                    $result_podcasts_enlaces = mysqli_query($conexion, $query_podcasts_enlaces);

                    // Almacena los resultados en un array asociativo
                    $podcasts_enlaces = mysqli_fetch_all($result_podcasts_enlaces, MYSQLI_ASSOC);

                    // Cierra la conexión
                    cerrarConexion($conexion);

                    foreach ($podcasts_enlaces as $podcast_enlace) {
                        // Extract the YouTube video ID from the link
                        echo '<div class="podcast" style="background-image:url(' . $podcast_enlace['imagen_ruta'] . ')" data-link="' . $podcast_enlace['link'] . '">';
                        echo "<div class='inner_podcast'>";
                        echo "<div class='box'>";
                        echo '<i class="fas fa-play fa-2x"></i>';
                        echo '<h2>' . $podcast_enlace['titulo'] . '</h2>';
                        echo '<p>' . $podcast_enlace['autor'] . '</p>';
                        echo "</div>";
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>


                </div>
            </div>
        </div>
        <div id="myModal" class="modal">
            <i class="fas fa-times fa-2x" id="closeBtn"></i>
            <div class="modal-content" id="modalContent">
            </div>
        </div>

    </main>

    <footer>
        <div class="container_footer">
            <div class="inner_footer_container">
                <div class="social_media">
                    <h2>Social</h2>
                    <i class="fab fa-instagram fa-2x"></i>
                    <i class="fab fa-facebook fa-2x"></i>
                    <i class="fab fa-linkedin fa-2x"></i>
                </div>
                <div class="contact">
                    <h2>Contacto</h2>
                    <p><span class="fuerte">Telefono: </span><a href="tel://+34 632707689">+34 632 707 689</a></p>
                    <p><span class="fuerte">Email: </span><a href="mailto:aristex@hotmail.com">aristex@hotmail.com</a></p>
                    <p><span class="fuerte">Direccion: </span><a href="https://maps.app.goo.gl/JZpVZw3dKvSbVxxa9"></a>C. Tajo, s/n, 28670 Villaviciosa de Odón, Madrid</p>
                </div>
                <div class="links">
                    <h2>Links</h2>
                    <div class="inner_link">
                        <div class="link_piece">
                            <a href="">Servicios</a>
                        </div>
                        <div class="link_piece">
                            <a href="">Recursos</a>
                        </div>
                        <div class="link_piece">
                            <a href="">Nosotros</a>
                        </div>
                        <div class="link_piece">
                            <a href="">Contacto</a>
                        </div>
                        <div class="link_piece">
                            <a href="">Perfil</a>
                        </div>
                        <div class="link_piece">
                            <a href="">Workshops</a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="legal">
                    <div class="copy">
                        brainwave copyright - own elements
                    </div>
                    <div class="disclaimer">
                        <a href="">Disclaimer</a>
                        <a href="">Politica de privacidad</a>
                        <a href="">Terminos de Uso</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>


</body>

</html>