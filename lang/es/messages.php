<?php

return [
    'environment' => [
        'environment' => 'Entorno',
        'branch' => 'Rama',
    ],

    'errors' => [
        'title' => 'Hay :count errores de validación:',
    ],

    'select' => [
        'default' => 'Seleccione una opción',
        'search' => 'Busca algo aquí',
        'empty' => 'No se encontraron resultados',
        'selected' => ':count seleccionados',
    ],

    'tag' => [
        'empty' => 'No se encontraron resultados',
    ],

    'autocomplete' => [
        'default' => 'Escribe para buscar...',
        'empty' => 'No se encontraron resultados',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confirmar',
            'cancel' => 'Cancelar',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confirmar',
            'cancel' => 'Cancelar',
        ],
    ],

    'command-palette' => [
        'search' => 'Buscar...',
        'empty' => 'No se encontraron resultados.',
        'navigate' => 'navegar',
        'select' => 'seleccionar',
        'close' => 'cerrar',
    ],

    'table' => [
        'empty' => 'No se encontraron resultados.',
        'quantity' => 'Cantidad',
        'search' => 'Busca algo aquí',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Copiar',
            'copied' => '¡Copiado!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Copiar',
        'download' => 'Descargar',
    ],

    'password' => [
        'rules' => [
            'title' => 'Formato Esperado de Contraseña:',
            'formats' => [
                'min' => 'Al menos :min caracteres',
                'numbers' => 'Al menos un número',
                'symbols' => 'Al menos un símbolo (:symbols)',
                'mixed' => 'Letras mayúsculas y minúsculas',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Elija un archivo',
        'size' => 'Tamaño',
        'upload' => 'Haga clic aquí para cargar',
        'uploaded' => [
            'single' => ':count archivo enviado',
            'multiple' => ':count archivos enviados',
        ],
        'error' => 'Algo salió mal. Por favor, inténtelo de nuevo.',
        'static' => [
            'empty' => [
                'title' => 'Sin imágenes.',
                'description' => 'Todavía no tienes ninguna imagen.',
            ],
        ],
        'invalid' => 'Hubo un error de validación.',
    ],

    'upload_async' => [
        'title' => 'Suelte los archivos aquí',
        'description' => 'o haga clic para seleccionar',
        'send' => 'Enviar',
        'clear' => 'Limpiar',
        'ready' => [
            'single' => ':count archivo listo · :size',
            'multiple' => ':count archivos listos · :size',
        ],
        'errors' => [
            'mime' => 'Tipo de archivo no permitido.',
            'size' => 'El archivo supera el límite de :max MB.',
            'limit' => 'Puede cargar como máximo :max archivos.',
            'network' => 'Error de red. Por favor, inténtelo de nuevo.',
            'server' => 'Error al cargar. Por favor, inténtelo de nuevo.',
            'integrity' => 'La carga llegó incompleta. Por favor, inténtelo de nuevo.',
            'unauthorized' => 'No tiene permiso para cargar este archivo.',
            'generic' => 'Algo salió mal.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Enero',
                'february' => 'Febrero',
                'march' => 'Marzo',
                'april' => 'Abril',
                'may' => 'Mayo',
                'june' => 'Junio',
                'july' => 'Julio',
                'august' => 'Agosto',
                'september' => 'Septiembre',
                'october' => 'Octubre',
                'november' => 'Noviembre',
                'december' => 'Diciembre',
            ],
            'week' => [
                'sunday' => 'Domingo',
                'monday' => 'Lunes',
                'tuesday' => 'Martes',
                'wednesday' => 'Miércoles',
                'thursday' => 'Jueves',
                'friday' => 'Viernes',
                'saturday' => 'Sábado',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Ayer',
            'today' => 'Hoy',
            'tomorrow' => 'Mañana',
        ],
    ],

    'time' => [
        'helper' => 'Hora Actual',
    ],

    'step' => [
        'next' => 'Siguiente',
        'previous' => 'Anterior',
        'finish' => 'Finalizar',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'CLAVE',
            'value' => 'VALOR',
        ],
        'placeholders' => [
            'key' => 'Ingresa una clave',
            'value' => 'Ingresa un valor',
        ],
        'add-row' => 'AGREGAR FILA',
        'empty' => 'No se han agregado filas.',
    ],

    'currency' => [
        'symbol' => '€',
        'currency' => 'EUR',
    ],

    'list' => [
        'search' => 'Buscar',
        'empty' => 'Sin elementos.',
    ],

    'editor' => [
        'placeholder' => 'Empieza a escribir...',
        'tooltip' => [
            'style' => 'Estilo de párrafo',
            'blockquote' => 'Cita',
            'bold' => 'Negrita',
            'italic' => 'Cursiva',
            'underline' => 'Subrayado',
            'strikethrough' => 'Tachado',
            'ordered_list' => 'Lista numerada',
            'unordered_list' => 'Lista con viñetas',
            'indent' => 'Aumentar sangría',
            'outdent' => 'Reducir sangría',
            'align' => 'Alineación',
            'code' => 'Código',
            'code_block' => 'Bloque de código',
            'clear_format' => 'Borrar formato',
            'link' => 'Insertar enlace',
            'image' => 'Insertar imagen',
            'hr' => 'Línea horizontal',
            'undo' => 'Deshacer',
            'redo' => 'Rehacer',
            'fullscreen' => 'Pantalla completa',
        ],
        'style' => [
            'paragraph' => 'Párrafo',
            'h1' => 'Título 1',
            'h2' => 'Título 2',
            'h3' => 'Título 3',
        ],
        'align' => [
            'left' => 'Izquierda',
            'center' => 'Centro',
            'right' => 'Derecha',
            'justify' => 'Justificado',
        ],
        'image' => [
            'title' => 'Insertar imagen',
            'upload' => 'Seleccionar archivo',
            'upload_hint' => 'PNG, JPG, GIF o WebP hasta :size',
            'or_url' => 'o pega una URL',
            'url' => 'URL',
            'alt' => 'Texto alternativo',
            'alt_hint' => 'Describe la imagen para lectores de pantalla',
            'cancel' => 'Cancelar',
            'insert' => 'Insertar',
            'errors' => [
                'url' => 'URL inválida o inaccesible.',
                'mime' => 'Tipo de archivo no permitido.',
                'size' => 'El archivo supera el límite de :max KB.',
                'failed' => 'Error al subir. Inténtalo de nuevo.',
            ],
        ],
        'link' => [
            'title' => 'Insertar enlace',
            'text' => 'Texto',
            'url' => 'URL',
            'cancel' => 'Cancelar',
            'insert' => 'Insertar',
        ],
        'counters' => [
            'words' => ':count palabra|:count palabras',
            'lines' => ':count línea|:count líneas',
        ],
    ],

    'spinner' => [
        'thinking' => 'Pensando...',
        'loading' => 'Cargando...',
    ],
];
