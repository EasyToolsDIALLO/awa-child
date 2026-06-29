/* Media uploader — Page d'options accueil AwA */
jQuery(function ($) {
    /* Ouvrir le media uploader */
    $(document).on('click', '.awa-media-btn', function (e) {
        e.preventDefault();
        var btn = $(this);
        var fieldId = btn.data('field');
        var mediaType = btn.data('type') || 'image';
        var frame = wp.media({
            title: mediaType === 'video' ? 'Choisir une vidéo' : 'Choisir une image',
            button: { text: 'Utiliser ce fichier' },
            library: { type: mediaType === 'video' ? 'video' : 'image' },
            multiple: false
        });
        frame.on('select', function () {
            var attachment = frame.state().get('selection').first().toJSON();
            var url = attachment.url;
            $('#' + fieldId).val(url);
            var preview = $('#preview-' + fieldId);
            if (mediaType === 'video') {
                if (preview.is('video')) {
                    preview.attr('src', url);
                } else {
                    preview.replaceWith('<video src="' + url + '" class="awa-media-preview-video" muted id="preview-' + fieldId + '"></video>');
                }
            } else {
                if (preview.is('img')) {
                    preview.attr('src', url);
                } else {
                    preview.replaceWith('<img src="' + url + '" class="awa-media-preview" id="preview-' + fieldId + '">');
                }
            }
            /* Afficher le bouton supprimer s'il n'existe pas */
            if (!btn.next('.awa-media-clear').length) {
                btn.after('<button type="button" class="button awa-media-clear" data-field="' + fieldId + '">Supprimer</button>');
            }
        });
        frame.open();
    });

    /* Supprimer le média */
    $(document).on('click', '.awa-media-clear', function (e) {
        e.preventDefault();
        var fieldId = $(this).data('field');
        $('#' + fieldId).val('');
        var preview = $('#preview-' + fieldId);
        if (preview.is('video')) {
            preview.replaceWith('<span style="width:120px;height:70px;display:flex;align-items:center;justify-content:center;background:#f0f0f1;border-radius:4px;font-size:11px;color:#888;" id="preview-' + fieldId + '">Aucune vidéo</span>');
        } else {
            preview.replaceWith('<span style="width:80px;height:60px;display:flex;align-items:center;justify-content:center;background:#f0f0f1;border-radius:4px;font-size:11px;color:#888;" id="preview-' + fieldId + '">Aucune image</span>');
        }
        $(this).remove();
    });
});
