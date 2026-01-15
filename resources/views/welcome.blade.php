<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>PowerFit — Tu gimnasio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap: utilidades y grid -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css">
     <!-- Hoja de estilos propia -->
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
  </head>
  <body>
    <!-- HEADER: logo, menú principal y acceso al área privada -->
    <header class=" py-5">
        <div id="logo">
          <img class="logo" src="{{ asset('img/logo.png')}}" alt="Logo de PowerFit" tabindex="0">
          <h1 class="h2 mb-2" tabindex="0">PowerFit</h1>
        </div>
        <nav id="nav">
          <a href="#inicio" tabindex="0" title="Inicio de la página">Inicio</a>
          <a href="#nosotros" tabindex="0" title="define al gimnasio">Nosotros</a>
          <a href="#actividades" tabindex="0" title="define al gimnasio">Actividades</a>
          <a href="#horarios" tabindex="0" title="horario de las clase">Horarios</a>
          <a href="#galeria" tabindex="0" title="galería de imagen del gimnasio">Galería</a>
          <a href="#contacto" tabindex="0" title="datos de contacto con el gimnasio">Contacto</a>
        </nav> 
        <a id="privado" class="btn boton" href="{{ route('login') }}" tabindex="0">Area privada</a>    
    </header>    
        <!-- MAIN: contenido principal de la página -->
    <main tabindex="0">
        <!-- Secciones principales -->
        <!-- Inicio -->
        <section id="inicio" tabindex="0"> 
            <div tabindex="0">
                <h2 tabindex="0">TRANSFORMA TU<br><span class="letras_gradient">CUERPO</span>,<br>
                TRANSFORMA TU<span class="letras_gradient"> VIDA</span></h2>
                <p tabindex="0">Únete a Powerfit Gym y descubre el mejor entrenamiento con<br>
                equipos de última generación y entrenadores certificados</p>                
            </div>  
        </section>
        <!-- Nosotros -->
        <section id="nosotros" tabindex="0">               
            <div tabindex="0" class="sobre_nosotros">
                <div class="linea-gradiente"></div>
                <p class="rojo">Nosotros</p>
                <h2 tabindex="0">MÁS QUE UN <span class="letras_gradient">GIMNASIO</span></h2>
                <p tabindex="0">¡Conoce el bienestar a través del deporte en nuestro centro deportivo!</p>

                <p tabindex="0">En la búsqueda continua de un estilo de vida saludable y armónico,
                    la actividad física desempeña un papel fundamental.
                </p>
                <p tabindex="0"> La práctica regular del deporte no sólo ayuda a mantener un peso saludable,
                    sino que además fomenta el bienestar mental y físico. En nuestro centro deportivo,
                    estamos orgullosos de formar parte de tu viaje hacia una vida saludable y activa.
                </p>
                <div class="tarjetas">
                    <div>
                        <div class="tarjeta">
                        <img src="{{ asset('img/objetivo.png') }}" alt="Icono de pesas" tabindex="0">
                        <h4 tabindex="0">Objetivos Reales</h4>
                        <p tabindex="0">Planes personalizados para alcazar tus metas</p>
                        </div>
                        <div class="tarjeta">
                            <img src="{{ asset('img/comunidad.png') }}" alt="Icono de pesas" tabindex="0">
                            <h4 tabindex="0">Comunidad</h4>
                            <p tabindex="0">Una familia finess que te motiva</p>
                        </div>
                    </div>
                    <div>
                        <div class="tarjeta">
                            <img src="{{ asset('img/certificado.png') }}" alt="Icono de pesas" tabindex="0">
                            <h4 tabindex="0">Cerftificados</h4>
                            <p tabindex="0">Entrenadores altamente cualificados</p>
                        </div>
                        <div class="tarjeta">
                            <img src="{{ asset('img/energia.png') }}" alt="Icono de pesas" tabindex="0">
                            <h4 tabindex="0">Energía Total</h4>
                            <p tabindex="0">Equipos de última generación</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="img_gym">
                <img src="{{ asset('img/gimnasio1.jpg') }}" alt="Imagen del gimnasio" tabindex="0">
            </div>
        </section>    
        <!-- Horarios -->  
        <section id="horarios" tabindex="0">      
            <div class="icono_calendario">
                <div class="linea-gradiente"></div>
                <img src="{{ asset('img/calendario.png') }}" alt="Icono de horario" tabindex="0">
                <div class="linea-gradiente"></div>
            </div>      
            <div class="text_horario">                
                <h2 tabindex="0">HORARIOS DE <span class="letras_gradient">CLASES</span></h2>
                <p tabindex="0">Consulta nuestros horarios de clases y encuentra el momento perfecto<br>para
                    unirte a nuestras actividades y alcanzar tus objetivos de bienestar.</p>
            </div>              
            <div class="tabla">
                <table tabindex="0">
                    <tr>
                        <th>

                        </th>
                        <th>
                            Lunes
                        </th>
                        <th>
                            Martes
                        </th>
                        <th>
                            Miércoles
                        </th>
                        <th>
                            Jueves
                        </th>
                        <th>
                            Viernes
                        </th>
                        <th>
                            Sábado
                        </th>
                    </tr>
                    <tr>
                        <td>8:00</td>
                        <td colspan="5"><span class="borde">Pilates</span></td>
                        <!--<td></td>
                        <td></td>
                        <td></td>
                        <td></td>-->
                        <td rowspan="2"><span class="borde">Natación</span></td>
                    </tr>
                    <tr>
                        <td>9:00</td>
                        <td rowspan="2"><span class="borde">Crossfit</span></td>
                        <td rowspan="2"><span class="borde">TRX</span></td>
                        <td rowspan="2"><span class="borde">Crossfit</span></td>
                        <td rowspan="2"><span class="borde">TRX</span></td>
                        <td rowspan="2"><span class="borde">Crossfit</span></td>
                        <!--<td></td>-->
                    </tr>
                    <tr>
                        <td>10:00</td>
                        <!--<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>-->
                        <td rowspan="2"><span class="borde">TRX</span></td>
                    </tr>
                    <tr>
                        <td>11:00</td>
                        <td colspan="5"><span class="borde">Yoga</span></td>
                        <!--<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>-->
                    </tr>
                    <tr>
                        <td>12:00</td>
                        <td rowspan="2"><span class="borde">TRX</span></td>
                        <td rowspan="2"><span class="borde">Crossfit</span></td>
                        <td rowspan="2"><span class="borde">TRX</span></td>
                        <td rowspan="2"><span class="borde">Crossfit</span></td>
                        <td  rowspan="2"><span class="borde">TRX</span></td>
                        <td rowspan="2"><span class="borde">Crossfit</span></td>
                    </tr>
                    <tr>
                        <td>13:00</td>
                        <!--<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>-->
                    </tr>
                    <tr>
                        <td>14:00</td>
                        <td><span class="borde">Zumba</span></td>
                        <td><span class="borde">Spinning</span></td>
                        <td><span class="borde">Zumba</span></td>
                        <td><span class="borde">Spinning</span></td>
                        <td><span class="borde">Zumba</span></td>
                        <td><span class="borde">Spinning</span></td>
                    </tr>
                    <tr>
                        <td>15:00</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>16:00</td>
                        <td rowspan="3" colspan="5"><span class="borde">Natación</span></td>
                        <!--<td></td>
                        <td></td>
                        <td></td>
                        <td></td>-->
                        <td></td>

                    </tr>
                    <tr>
                        <td>17:00</td>
                        <!--<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>-->
                        <td><span class="borde">Pilates</span></td>
                    </tr>
                    <tr>
                        <td>18:00</td>
                        <!--<td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>-->
                        <td><span class="borde">TRX</span></td>
                    </tr>
                    <tr>
                        <td>19:00</td>
                        <td><span class="borde">Pilates</span></td>
                        <td><span class="borde">Yoga</span></td>
                        <td><span class="borde">Pilates</span></td>
                        <td><span class="borde">Yoga</span></td>
                        <td><span class="borde">Pilates</span></td>
                        <td><span class="borde">Yoga</span></td>
                    </tr>
                    <tr>
                        <td>20:00</td>
                        <td><span class="borde">TRX</span></td>
                        <td><span class="borde">Crossfit</span></td>
                        <td><span class="borde">TRX</span></td>
                        <td><span class="borde">Crossfit</span></td>
                        <td><span class="borde">TRX</span></td>
                        <td><span class="borde">Crossfit</span></td>
                    </tr>
                </table>
            </div>
            <div class="horario">
                <img src="{{ asset('img/reloj.png') }}" alt="Icono de reloj" tabindex="0"><br>
                <p tabindex="0"><strong>Horario del gimnasio</strong><br><span class="rojo"> Lunes-Viernes:</span>   8:00 AM - 22:00 PM<br>
                    <span class="rojo">Sábados-Domingos:</span>   8:00 AM - 20:00 PM</p>
            </div>
            <div>
                <br><p tabindex="0"><strong>Nota:</strong> Los horarios de las clases pueden estar sujetos a cambios. Por favor,
                    consulta con nuestro personal para obtener la información más actualizada.</p>
            </div>
        </section>
        <!-- Actividades -->
          <section id="actividades" tabindex="0">
            <h2 tabindex="0" class="rojo">Actividades</h2>
            <ul tabindex="0">
                <li tabindex="0"><strong>Pilates:</strong> Es un método de ejercicio centrado en mejorar la fuerza, la flexibilidad y
                    la conciencia corporal.</li>
                <li tabindex="0"><strong>Crossfit:</strong> Es un programa de entrenamiento físico que combina diferentes
                    disciplinas.</li>
                <li tabindex="0"><strong>Natación:</strong> Es una forma completa de ejercicio que ofrece una amplia gama de
                    beneficios para la salud.</li>
                <li tabindex="0"><strong>TRX:</strong> Es un sistema de entrenamiento funcional que utiliza correas de suspensión
                    para realizar ejercicios que involucran el peso corporal. </li>
                <li tabindex="0"><strong>Yoga:</strong> Es una práctica milenaria que combina posturas físicas, técnicas de
                    respiración y meditación para mejorar la salud física, mental y espiritual. </li>
                <li tabindex="0">
                <strong>Zumba:</strong> Es una actividad cardiovascular que combina pasos de baile con música latina y comercial,
                convirtiendo el ejercicio en una sesión dinámica y divertida que mejora la resistencia, la coordinación y el estado de ánimo.
                </li>
                <li tabindex="0">
                <strong>Sala de posing:</strong> Espacio pensado para practicantes de culturismo y fitness escénico, equipado con espejos e iluminación adecuados
                para trabajar las poses obligatorias, la simetría corporal y la presencia en el escenario.
                </li>
                <li tabindex="0">
                <strong>Sala de máquinas:</strong> Zona equipada con máquinas guiadas y bancos de musculación que permiten entrenar de forma segura cada grupo muscular,
                ideal tanto para principiantes como para usuarios avanzados que buscan fuerza e hipertrofia.
                </li>
                <li tabindex="0">
                <strong>Spinning:</strong> Entrenamiento cardiovascular en bicicleta estática al ritmo de la música,
                simulando subidas y cambios de ritmo para mejorar la resistencia, la capacidad pulmonar y ayudar a quemar calorías con bajo impacto articular.
                </li>
            </ul>            
            <div class="linea-gradiente"></div>
        </section>
        <!-- Galería -->
        <section id="galeria" tabindex="0">            
            <div class="icono_calendario">
                <div class="linea-gradiente"></div>
                <img src="{{ asset('img/imagen.png') }}" alt="Icono de horario" tabindex="0">
                <div class="linea-gradiente"></div>
            </div>
            <h2 tabindex="0">NUESTRAS  <span class="letras_gradient">INSTALACIONES</span></h2>

            <div class="galeria">
                <figure>
                    <img src="{{ asset('img/picture1.jpg') }}" alt="imagen de clase de pilates del gimnasio" tabindex="0">
                    <figcaption>
                        Con el pilates encuentras equilibrio y control en cada movimiento para fortalecer cuerpo y
                        mente.
                    </figcaption>
                </figure>
                <figure>
                    <img src="{{ asset('img/picture2.jpg') }}" alt="imagen de clase de zumba del gimnasio" tabindex="0">
                    <figcaption>
                        El zumba es bailar, sudar y diviértirse mientras entrenas.
                    </figcaption>
                </figure>
                <figure>
                    <img src="{{ asset('img/picture3.jpg') }}" alt="imagen de clase de natación del gimnasio" tabindex="0">
                    <figcaption>
                        La natación es una actividad refrescante que no solo fortalece el cuerpo, sino también la mente.
                    </figcaption>
                </figure>

                <figure tabindex="0">
                    <img src="{{ asset('img/picture4.jpg') }}" alt="imagen de clase de TRX del gimnasio" tabindex="0">
                    <figcaption tabindex="0">
                        El TRX es un entrenamiento versátil que utiliza la resistencia del propio peso para desarrollar
                        fuerza y estabilidad.
                    </figcaption>
                </figure>
                <figure tabindex="0">
                    <img src="{{ asset('img/picture5.jpg') }}" alt="imagen de clase de CrossFit del gimnasio" tabindex="0">
                    <figcaption tabindex="0">
                        CrossFit es más que un entrenamiento, es una comunidad de superación.
                    </figcaption>
                </figure>
                <figure tabindex="0">
                    <img src="{{ asset('img/picture6.jpg') }}" alt="imagen de clase de yoga del gimnasio" tabindex="0">
                    <figcaption tabindex="0">
                        El yoga es un viaje hacia la armonía física y mental.
                    </figcaption>
                </figure>
                <figure tabindex="0">
                    <img src="{{ asset('img/picture7.jpg') }}" alt="imagen de sala de máquinas del gimnasio" tabindex="0">
                    <figcaption tabindex="0">
                        La sala de máquinas del gimnasio es el espacio donde se esculpe la determinación y se alcanzan
                        metas.
                    </figcaption>
                </figure>
                <figure tabindex="0">
                    <img src="{{ asset('img/picture8.jpg') }}" alt="imagen de entrenamiento libre del gimnasio" tabindex="0">
                    <figcaption tabindex="0">
                        Entrenamiento libre, tu espacio y tu rutina.
                    </figcaption>
                </figure>
                <figure tabindex="0">
                    <img src="{{ asset('img/picture9.jpg') }}" alt="imagen de sala de posing del gimnasio" tabindex="0">
                    <figcaption tabindex="0">
                        Sala de posing, muestra tu mejor versión.
                    </figcaption>
                </figure>
            </div>
        </section>
        <div class="linea-gradiente"></div>
    </main>
    <!-- FOOTER: datos de contacto y redes sociales -->
    <footer id="contacto" tabindex="0">    
        
            <!--Redes-->
        <div>            
            <div class="logo_footer">
                <img class="logo" src="{{ asset('img/logo.png')}}" alt="Logo de PowerFit" tabindex="0">
                <h1 class="h2 mb-2" tabindex="0">PowerFit</h1>
            </div>
            <p>Síguenos en nuestras redes sociales para estar al día con las últimas novedades,<br>
                promociones y consejos de bienestar.</p>
            <div class="redes">
                <a href="https://www.facebook.com" target="_blank"><img src="{{ asset('img/facebook.png') }}" alt="Ir a nuestra página de Facebook" tabindex="0"/></a>
                <a href="https://www.instagram.com/" target="_blank"><img src="{{ asset('img/instagram.png') }}" alt="Ir a nuestra página de Instagram" tabindex="0"/></a>
                <a href="https://www.tiktok.com/" target="_blank"><img src="{{ asset('img/tiktok.png') }}" alt="Ir a nuestro Tiktok" tabindex="0"/></a>
                <a href="https://www.x.com/home?lang=es" target="_blank"><img src="{{ asset('img/x.png') }}" alt="Ir a nuestro x" tabindex="0"/></a>        
            </div>
        </div>       
        
        <div>
        <!--Datos-->
            <p class="contacto">Contacto:</p>
            <address>
                <p>José Antonio González Catilla</p>
                <p>Proyecto Final| DAW | 2025-26</p>
                <p>Teléfono: 666 666 666</p>
                <p>Dirección: Calle Alhambra, Nº15. Aguadulce (Almería)</p>
                <p class="mb-0" tabindex="0">&copy; 2026 PowerFit. Todos los derechos reservados.</p>
            </address>
        </div>
    </footer>
  </body>
</html>
