<?php

return [
    'environment' => [
        'environment' => 'Ambiente',
        'branch' => 'Branch',
    ],

    'errors' => [
        'title' => 'Há :count erros de validação:',
    ],

    'select' => [
        'default' => 'Selecione uma opção',
        'search' => 'Procure algo aqui',
        'empty' => 'Nenhum resultado encontrado',
        'selected' => ':count selecionados',
    ],

    'tag' => [
        'empty' => 'Nenhum resultado encontrado',
    ],

    'autocomplete' => [
        'default' => 'Digite para pesquisar...',
        'empty' => 'Nenhum resultado encontrado',
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
        'search' => 'Pesquisar...',
        'empty' => 'Nenhum resultado encontrado.',
        'navigate' => 'navegar',
        'select' => 'selecionar',
        'close' => 'fechar',
    ],

    'table' => [
        'empty' => 'Nenhum resultado encontrado.',
        'quantity' => 'Quantidade',
        'search' => 'Procure algo aqui',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Copiar',
            'copied' => 'Copiado!',
        ],
    ],

    'qr-code' => [
        'copy' => 'Copiar',
        'download' => 'Baixar',
    ],

    'password' => [
        'rules' => [
            'title' => 'Formato Esperado de Senha:',
            'formats' => [
                'min' => 'Pelo menos :min caracteres',
                'numbers' => 'Pelo menos um número',
                'symbols' => 'Pelo menos um símbolo (:symbols)',
                'mixed' => 'Letras maiúsculas e minúsculas',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Escolha um arquivo',
        'size' => 'Tamanho',
        'upload' => 'Clique aqui para enviar',
        'uploaded' => [
            'single' => ':count arquivo enviado',
            'multiple' => ':count arquivos enviados',
        ],
        'error' => 'Algo deu errado. Por favor, tente novamente.',
        'static' => [
            'empty' => [
                'title' => 'Sem imagens.',
                'description' => 'Você ainda não possui nenhuma imagem.',
            ],
        ],
        'invalid' => 'Houve algum erro de validação.',
    ],

    'upload_async' => [
        'title' => 'Solte os arquivos aqui',
        'description' => 'ou clique para selecionar',
        'send' => 'Enviar',
        'clear' => 'Limpar',
        'ready' => [
            'single' => ':count arquivo pronto · :size',
            'multiple' => ':count arquivos prontos · :size',
        ],
        'errors' => [
            'mime' => 'Tipo de arquivo não permitido.',
            'size' => 'O arquivo excede o limite de :max MB.',
            'limit' => 'Você pode enviar no máximo :max arquivos.',
            'network' => 'Erro de rede. Tente novamente.',
            'server' => 'Falha no envio. Tente novamente.',
            'integrity' => 'O envio chegou incompleto. Tente novamente.',
            'unauthorized' => 'Você não tem permissão para enviar este arquivo.',
            'generic' => 'Algo deu errado.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Janeiro',
                'february' => 'Fevereiro',
                'march' => 'Março',
                'april' => 'Abril',
                'may' => 'Maio',
                'june' => 'Junho',
                'july' => 'Julho',
                'august' => 'Agosto',
                'september' => 'Setembro',
                'october' => 'Outubro',
                'november' => 'Novembro',
                'december' => 'Dezembro',
            ],
            'week' => [
                'sunday' => 'Domingo',
                'monday' => 'Segunda-feira',
                'tuesday' => 'Terça-feira',
                'wednesday' => 'Quarta-feira',
                'thursday' => 'Quinta-feira',
                'friday' => 'Sexta-feira',
                'saturday' => 'Sábado',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Ontem',
            'today' => 'Hoje',
            'tomorrow' => 'Amanhã',
        ],
    ],

    'time' => [
        'helper' => 'Hora Atual',
    ],

    'step' => [
        'next' => 'Seguinte',
        'previous' => 'Anterior',
        'finish' => 'Finalizar',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'CHAVE',
            'value' => 'VALOR',
        ],
        'placeholders' => [
            'key' => 'Insira uma chave',
            'value' => 'Insira um valor',
        ],
        'add-row' => 'ADICIONAR LINHA',
        'empty' => 'Nenhuma linha adicionada.',
    ],

    'currency' => [
        'symbol' => 'R$',
        'currency' => 'BRL',
    ],

    'list' => [
        'search' => 'Pesquisar',
        'empty' => 'Nenhum item.',
    ],

    'editor' => [
        'placeholder' => 'Comece a escrever...',
        'tooltip' => [
            'style' => 'Estilo do parágrafo',
            'blockquote' => 'Citação',
            'bold' => 'Negrito',
            'italic' => 'Itálico',
            'underline' => 'Sublinhado',
            'strikethrough' => 'Riscado',
            'ordered_list' => 'Lista numerada',
            'unordered_list' => 'Lista com marcadores',
            'indent' => 'Aumentar recuo',
            'outdent' => 'Diminuir recuo',
            'align' => 'Alinhamento',
            'code' => 'Código',
            'code_block' => 'Bloco de código',
            'clear_format' => 'Limpar formatação',
            'link' => 'Inserir link',
            'image' => 'Inserir imagem',
            'hr' => 'Linha horizontal',
            'undo' => 'Desfazer',
            'redo' => 'Refazer',
            'fullscreen' => 'Tela cheia',
        ],
        'style' => [
            'paragraph' => 'Parágrafo',
            'h1' => 'Título 1',
            'h2' => 'Título 2',
            'h3' => 'Título 3',
        ],
        'align' => [
            'left' => 'Esquerda',
            'center' => 'Centro',
            'right' => 'Direita',
            'justify' => 'Justificado',
        ],
        'image' => [
            'title' => 'Inserir imagem',
            'upload' => 'Selecionar arquivo',
            'upload_hint' => 'PNG, JPG, GIF ou WebP até :size',
            'or_url' => 'ou cole uma URL',
            'url' => 'URL',
            'alt' => 'Texto alternativo',
            'alt_hint' => 'Descreva a imagem para leitores de tela',
            'cancel' => 'Cancelar',
            'insert' => 'Inserir',
            'errors' => [
                'url' => 'URL inválida ou inacessível.',
                'mime' => 'Tipo de arquivo não permitido.',
                'size' => 'O arquivo excede o limite de :max KB.',
                'failed' => 'Falha no envio. Tente novamente.',
            ],
        ],
        'link' => [
            'title' => 'Inserir link',
            'text' => 'Texto',
            'url' => 'URL',
            'cancel' => 'Cancelar',
            'insert' => 'Inserir',
        ],
        'counters' => [
            'words' => ':count palavra|:count palavras',
            'lines' => ':count linha|:count linhas',
        ],
    ],

    'spinner' => [
        'thinking' => 'Pensando...',
        'loading' => 'Carregando...',
    ],
];
