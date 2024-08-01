<?php

namespace App\Livewire;

use Livewire\Component;
use Auth;

class LastPost extends Component
{
    public function render()
    {
        // Se obtiene la última noticia a la que el usuario autenticado tiene acceso.
        // Esto se hace utilizando la relación "news" definida en el modelo User, la cual 
        // devuelve una query basada en el role_id del usuario. La lógica dentro de la 
        // función "news" maneja los siguientes casos:
        // - Si el role_id es 1 o 2 (roles con acceso completo), se devuelve una query sin 
        //   restricciones, es decir, todos los posts.
        // - Si el role_id es diferente de 1 o 2, se filtran los posts según los niveles de 
        //   visibilidad definidos en el modelo PostVisibilityLevel, devolviendo solo aquellos 
        //   posts que coinciden con el nivel de visibilidad correspondiente al role_id del usuario.
        // 
        // Luego, se utiliza el método "latest" en la query resultante para ordenar los posts 
        // por la columna "created_at" en orden descendente, y se obtiene el primer resultado 
        // con el método "first", que representa la última noticia.
        //
        // Este enfoque es eficiente y flexible porque:
        // - Centraliza la lógica de obtención de noticias con acceso controlado en la función 
        //   "news" del modelo User.
        // - Permite la aplicación de métodos adicionales en la query de manera dinámica, 
        //   como "latest" y "first", sin necesidad de duplicar la lógica de filtrado en múltiples lugares.
        // - Facilita la reutilización de la lógica de permisos de acceso en diferentes contextos 
        //   y escenarios de uso, manteniendo el código limpio y mantenible.

        $data['post'] = Auth::user()->news()->latest('created_at')->first();

        return view('livewire.last-post', $data);
    }
}
