-- Actualizar descripción de Terapia RAI
UPDATE `servicios` SET 
`descripcion` = '¿Qué es la terapia RAI?

RAI es Reconocer, Aceptar, Integrar e Impactar. El principio fundamental de esta terapia es el desarrollo de la sensibilidad sensorial, así como la auto observación. Aceptando nuestras reacciones frente a lo que sucede en el entorno para alcanzar un estado calmo que nos permita actuar generando acciones en pos del autocuidado y del mundo que nos rodea.

Beneficios de la terapia RAI
- Ayuda a recuperar el equilibrio cotidiano.
- Te permite regular el Sistema Nervioso.
- Permite el desarrollo de la autoconciencia sensorial.
- Te permite vivir en tu presente.
- Facilita un mejor descanso.

¿Cómo es una sesión de terapia RAI?
En las sesiones de terapia RAI, la terapeuta genera un espacio amoroso y compasivo a través del cual se inicia una conversación. Posteriormente guía a su paciente mediante ejercicios sensoriales de auto cuidado a comprender su realidad y el impacto que genera en los demás, pero desde una perspectiva compasiva y sin juicio. Este entrenamiento ayuda al sistema nervioso del paciente a construir nuevos caminos neuronales que se reflejan en su cotidiano en estados calmos y conscientes en uno mismo para impactar positivamente él y su entorno.

¿Cuánto dura una terapia RAI?
Se recomiendan ciclos terapéuticos de 12 sesiones de 1 hora cada sesión.
En este periodo el paciente puede generar conciencia de sí mismo y a su vez observar el impacto que genera en su entorno. También es un tiempo propicio para lograr dirigir los estados de alerta de su sistema nervioso, dando el espacio a la calma y al sentir para auto gobernar su capacidad de respuesta.',
`imagen` = '/public/images/terapia.jpeg'
WHERE `slug` = 'terapia-rai';

-- Actualizar servicio existente: Terapia de Lenguaje, Aprendizaje, Habla y Audición
UPDATE `servicios` SET 
`descripcion` = 'Servicios de Fonoaudiología

A cargo de Elizabeth Solis Chávez, Licenciada en Comunicación Humana y Fonoaudióloga con más de dos décadas de experiencia.

Atención especializada en Terapia de Lenguaje, Aprendizaje, Habla y Audición para niños, jóvenes y adultos.

Terapia de Lenguaje
Apoyo en el desarrollo y comprensión del lenguaje oral y escrito.

Aprendizaje
Acompañamiento en dificultades de lectura, escritura y atención.

Habla
Intervención en alteraciones de la pronunciación y fluidez verbal.

Audición
Evaluación y orientación en dificultades auditivas y procesamiento.',
`imagen` = '/public/images/terapia.jpeg'
WHERE `slug` = 'terapia-lenguaje-aprendizaje';

-- Actualizar servicio existente: Círculos de Crianza y Autocuidado
UPDATE `servicios` SET 
`descripcion` = 'Encuentros mensuales donde madres, padres y cuidadores pueden detenerse, respirar y volver a sí mismos, en un ambiente de confianza, respeto y escucha amorosa.

A través de la palabra, el silencio, dinámicas sensoriales y contenidos inspirados en la Pedagogía Waldorf, el enfoque Pikler y el bienestar natural, vamos profundizando en distintos aspectos de la crianza consciente.

Beneficios:
- Mirar con más claridad lo que estamos viviendo
- Comprender las necesidades de nuestros hijos e hijas
- Reconectar con nuestro propio ritmo y bienestar
- Sentir el sostén de una comunidad que acompaña

Para madres, padres y cuidadores que desean criar con más conciencia, presencia y calma.',
`imagen` = '/public/images/circulo_mensual.jpeg'
WHERE `slug` = 'circulos-autocuidado-crianza';
