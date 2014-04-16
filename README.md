Prueba técnica Programador Senior PHP
=====================================

La prueba consiste en tres tareas:

* Implementación de features nuevas => más recetas, más social, más de todo ...

* Refactorización de legacy code => porque los platos no se lavan solos (no tenemos lavaplatos :()

* Short questions

Valoramos:

* Testing. ¿Es necesario añadir nada más ;)?

* Clean code. Un buen diseño, simplicidad, código bonito, que se pueda leer. Tip: Cuando funcione, dále otra vuelta.

* PSR's. Usamos PSR2, hasta aquí puedo leer.

Tip: Usamos herramientas como PHP Code Sniffer y PHP Mess Detector

Implementación de features nuevas
=================================
En el directorio 'features' se describen algunos casos de uso a implementar.
Están escritos en formato Gherkin y son ejecutables mediante Behat.
Tip: Nos gusta Behat ;)

Eres libre de implementar la funcionalidad como desees. Lúcete ;)

Refactorización de legacy code
==============================
En nuestro código hay cosas bonitas y también hay cosas feas, por eso te pedimos que ahora te pongas el mono de limpieza:

* Refactorizar la función 'whatTheFuckDoesThisFunction' hasta que el código explique claramente para qué sirve y qué hace.
Aunque hemos cambiado el nombre a la función, podemos asegurarte que el nombre original no era mucho mejor.
Tip: si pones comentarios en el código es que todavía lo puedes mejorar

* Blog controller. Hemos puesto código de un action de un controlador, simplemente nos gustaría que lo limpiases.
Hay entidades sin definir, dependencias inexistentes, funciones no implementadas y no corre.
Tip: Si extraes entidades nuevas puedes hacer unit testing de ellas.
Haz lo que puedas :)

Questions
=========
Tip: try to keep it short

* What is an index in a sql database?

* If you have to create an API, which data format would you choose and why?

* If you are to save passwords, how do you do? Why?

* Describe the architecture you would use to create a system where as much as possible of the code is decoupled from the framework.

* What is a no sql database?

* What type of code constructs makes unit testing difficult or impossible?

* Explain dependency injection.

* What is a mock? How do you use it?

