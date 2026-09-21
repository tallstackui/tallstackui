<?php

return [
    'environment' => [
        'environment' => 'Environnement',
        'branch' => 'Branche',
    ],

    'errors' => [
        'title' => 'Il y a :count erreurs de validation:',
    ],

    'select' => [
        'default' => 'Choisir une option',
        'search' => 'Cherchez quelque chose ici',
        'empty' => 'Aucun résultat trouvé',
        'selected' => ':count sélectionnés',
    ],

    'tag' => [
        'empty' => 'Aucun résultat trouvé',
    ],

    'autocomplete' => [
        'default' => 'Tapez pour rechercher...',
        'empty' => 'Aucun résultat trouvé',
    ],

    'toast' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confirmer',
            'cancel' => 'Annuler',
        ],
    ],

    'dialog' => [
        'button' => [
            'ok' => 'Ok',
            'confirm' => 'Confirmer',
            'cancel' => 'Annuler',
        ],
    ],

    'command-palette' => [
        'search' => 'Rechercher...',
        'empty' => 'Aucun résultat trouvé.',
        'navigate' => 'naviguer',
        'select' => 'sélectionner',
        'close' => 'fermer',
    ],

    'table' => [
        'empty' => 'Aucun résultat trouvé.',
        'quantity' => 'Quantité',
        'search' => 'Cherchez quelque chose ici',
    ],

    'clipboard' => [
        'button' => [
            'copy' => 'Copier',
            'copied' => 'Copié !',
        ],
    ],

    'qr-code' => [
        'copy' => 'Copier',
        'download' => 'Télécharger',
    ],

    'password' => [
        'rules' => [
            'title' => 'Format de Mot de Passe Attendu :',
            'formats' => [
                'min' => 'Au moins :min caractères',
                'numbers' => 'Au moins un chiffre',
                'symbols' => 'Au moins un symbole (:symbols)',
                'mixed' => 'Lettres majuscules et minuscules',
            ],
        ],
    ],

    'upload' => [
        'placeholder' => 'Choisissez un fichier',
        'size' => 'Taille',
        'upload' => 'Cliquez ici pour télécharger',
        'uploaded' => [
            'single' => ':count fichier envoyé',
            'multiple' => ':count fichiers envoyés',
        ],
        'error' => 'Quelque chose s\'est mal passé. Veuillez réessayer.',
        'static' => [
            'empty' => [
                'title' => 'Aucune image.',
                'description' => 'Vous n\'avez pas encore d\'image.',
            ],
        ],
        'invalid' => 'Une erreur de validation s\'est produite.',
    ],

    'upload_async' => [
        'title' => 'Déposez les fichiers ici',
        'description' => 'ou cliquez pour sélectionner',
        'send' => 'Envoyer',
        'clear' => 'Effacer',
        'ready' => [
            'single' => ':count fichier prêt · :size',
            'multiple' => ':count fichiers prêts · :size',
        ],
        'errors' => [
            'mime' => 'Type de fichier non autorisé.',
            'size' => 'Le fichier dépasse la limite de :max Mo.',
            'limit' => 'Vous pouvez envoyer au maximum :max fichiers.',
            'network' => 'Erreur réseau. Veuillez réessayer.',
            'server' => 'L\'envoi a échoué. Veuillez réessayer.',
            'integrity' => 'L\'envoi est arrivé incomplet. Veuillez réessayer.',
            'unauthorized' => 'Vous n\'êtes pas autorisé à envoyer ce fichier.',
            'generic' => 'Une erreur est survenue.',
        ],
    ],

    'date' => [
        'calendar' => [
            'months' => [
                'january' => 'Janvier',
                'february' => 'Février',
                'march' => 'Mars',
                'april' => 'Avril',
                'may' => 'Mai',
                'june' => 'Juin',
                'july' => 'Juillet',
                'august' => 'Août',
                'september' => 'Septembre',
                'october' => 'Octobre',
                'november' => 'Novembre',
                'december' => 'Décembre',
            ],
            'week' => [
                'sunday' => 'Dimanche',
                'monday' => 'Lundi',
                'tuesday' => 'Mardi',
                'wednesday' => 'Mercredi',
                'thursday' => 'Jeudi',
                'friday' => 'Vendredi',
                'saturday' => 'Samedi',
            ],
        ],
        'helpers' => [
            'yesterday' => 'Hier',
            'today' => 'Aujourd\'hui',
            'tomorrow' => 'Demain',
        ],
    ],

    'time' => [
        'helper' => 'Heure Actuelle',
    ],

    'step' => [
        'next' => 'Suivant',
        'previous' => 'Précédent',
        'finish' => 'Terminer',
    ],

    'key-value' => [
        'headers' => [
            'key' => 'CLÉ',
            'value' => 'VALEUR',
        ],
        'placeholders' => [
            'key' => 'Entrez une clé',
            'value' => 'Entrez une valeur',
        ],
        'add-row' => 'AJOUTER UNE LIGNE',
        'empty' => 'Aucune ligne ajoutée.',
    ],

    'currency' => [
        'symbol' => '€',
        'currency' => 'EUR',
    ],

    'list' => [
        'search' => 'Rechercher',
        'empty' => 'Aucun élément.',
    ],

    'editor' => [
        'placeholder' => 'Commencez à écrire...',
        'tooltip' => [
            'style' => 'Style de paragraphe',
            'blockquote' => 'Citation',
            'bold' => 'Gras',
            'italic' => 'Italique',
            'underline' => 'Souligné',
            'strikethrough' => 'Barré',
            'ordered_list' => 'Liste numérotée',
            'unordered_list' => 'Liste à puces',
            'indent' => 'Augmenter le retrait',
            'outdent' => 'Diminuer le retrait',
            'align' => 'Alignement',
            'code' => 'Code',
            'code_block' => 'Bloc de code',
            'clear_format' => 'Effacer la mise en forme',
            'link' => 'Insérer un lien',
            'image' => 'Insérer une image',
            'hr' => 'Ligne horizontale',
            'undo' => 'Annuler',
            'redo' => 'Rétablir',
            'fullscreen' => 'Plein écran',
        ],
        'style' => [
            'paragraph' => 'Paragraphe',
            'h1' => 'Titre 1',
            'h2' => 'Titre 2',
            'h3' => 'Titre 3',
        ],
        'align' => [
            'left' => 'Gauche',
            'center' => 'Centre',
            'right' => 'Droite',
            'justify' => 'Justifié',
        ],
        'image' => [
            'title' => 'Insérer une image',
            'upload' => 'Choisir un fichier',
            'upload_hint' => 'PNG, JPG, GIF ou WebP jusqu\'à :size',
            'or_url' => 'ou collez une URL',
            'url' => 'URL',
            'alt' => 'Texte alternatif',
            'alt_hint' => 'Décrivez l\'image pour les lecteurs d\'écran',
            'cancel' => 'Annuler',
            'insert' => 'Insérer',
            'errors' => [
                'url' => 'URL invalide ou inaccessible.',
                'mime' => 'Type de fichier non autorisé.',
                'size' => 'Le fichier dépasse la limite de :max Ko.',
                'failed' => 'Échec de l\'envoi. Veuillez réessayer.',
            ],
        ],
        'link' => [
            'title' => 'Insérer un lien',
            'text' => 'Texte',
            'url' => 'URL',
            'cancel' => 'Annuler',
            'insert' => 'Insérer',
        ],
        'counters' => [
            'words' => ':count mot|:count mots',
            'lines' => ':count ligne|:count lignes',
        ],
    ],

    'spinner' => [
        'thinking' => 'Réflexion...',
        'loading' => 'Chargement...',
    ],
];
