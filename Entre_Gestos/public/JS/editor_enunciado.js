/**
 * editor_enunciado.js
 * Inicializa o TinyMCE no campo #enunciado com suporte completo a:
 *   - Upload de imagens do computador (JPG, PNG, GIF, WebP)
 *   - Upload de vídeos do computador (MP4, WebM, OGG)
 *   - Embed de YouTube / Vimeo via link
 *   - Links, listas e formatação padrão
 *
 * Usa XMLHttpRequest com withCredentials=true para garantir o envio
 * da cookie de sessão PHP em todas as requisições de upload.
 *
 * Requer: BASE_URL definida antes deste script.
 */

/* ─────────────────────────────────────────────────────────────────────────────
   Utilitário: upload via XHR com cookie de sessão
   Retorna uma Promise que resolve com a URL pública do arquivo.
───────────────────────────────────────────────────────────────────────────── */
function xhrUpload(file, endpoint, onProgress) {
    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', endpoint, true);
        xhr.withCredentials = true; // garante envio da PHPSESSID

        if (onProgress) {
            xhr.upload.addEventListener('progress', function (e) {
                if (e.lengthComputable) {
                    onProgress(Math.round((e.loaded / e.total) * 100));
                }
            });
        }

        xhr.addEventListener('load', function () {
            var txt = xhr.responseText;
            var json;

            try {
                json = JSON.parse(txt);
            } catch (e) {
                reject('Resposta inesperada do servidor: ' + txt.substring(0, 200));
                return;
            }

            if (xhr.status < 200 || xhr.status >= 300) {
                reject(json.error || ('Erro ' + xhr.status));
                return;
            }

            if (json && json.location) {
                resolve(json.location);
            } else {
                reject('O servidor não retornou a URL do arquivo.');
            }
        });

        xhr.addEventListener('error', function () {
            reject('Falha de rede. Verifique sua conexão.');
        });

        var fd = new FormData();
        fd.append('file', file);
        xhr.send(fd);
    });
}

/* ─────────────────────────────────────────────────────────────────────────────
   Utilitário: extrai o embed URL de um link do YouTube ou Vimeo
───────────────────────────────────────────────────────────────────────────── */
function parsearEmbed(url) {
    url = url.trim();

    // YouTube: watch?v=ID | youtu.be/ID | /embed/ID
    var ytMatch = url.match(
        /(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([A-Za-z0-9_\-]{11})/
    );
    if (ytMatch) {
        return 'https://www.youtube.com/embed/' + ytMatch[1] + '?rel=0';
    }

    // Vimeo: vimeo.com/ID
    var vmMatch = url.match(/vimeo\.com\/(\d+)/);
    if (vmMatch) {
        return 'https://player.vimeo.com/video/' + vmMatch[1];
    }

    return null;
}

/* ─────────────────────────────────────────────────────────────────────────────
   Inicialização do TinyMCE
───────────────────────────────────────────────────────────────────────────── */
tinymce.init({
    selector: '#enunciado',
    height: 320,
    menubar: false,
    plugins: 'image link lists code',
    toolbar:
        'undo redo | bold italic underline | bullist numlist | ' +
        'alignleft aligncenter alignright | ' +
        'link image | uploadVideo embedVideo | code',
    branding: false,
    language: 'pt_BR',

    /* ── Upload de imagens: handler XHR com sessão ────────────────────── */
    images_upload_url: BASE_URL + '/util/upload_image.php',

    images_upload_handler: function (blobInfo, progress) {
        var file = new File([blobInfo.blob()], blobInfo.filename(), {
            type: blobInfo.blob().type
        });
        return xhrUpload(file, BASE_URL + '/util/upload_image.php', progress)
            .catch(function (err) {
                return Promise.reject({ message: err, remove: true });
            });
    },

    /* ── File picker ao clicar no botão Image da toolbar ─────────────── */
    file_picker_types: 'image',

    file_picker_callback: function (callback, value, meta) {
        if (meta.filetype !== 'image') return;

        var input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/jpeg,image/png,image/gif,image/webp';

        input.addEventListener('change', function () {
            var file = this.files[0];
            if (!file) return;

            xhrUpload(file, BASE_URL + '/util/upload_image.php')
                .then(function (url) {
                    callback(url, { title: file.name, alt: file.name });
                })
                .catch(function (err) {
                    alert('Erro ao fazer upload da imagem:\n' + err);
                });
        });

        input.click();
    },

    /* ── Botões customizados ──────────────────────────────────────────── */
    setup: function (editor) {

        /* ── Botão: Upload de vídeo local ─────────────────────────────── */
        editor.ui.registry.addButton('uploadVideo', {
            icon:    'embed',
            tooltip: 'Enviar vídeo do computador (MP4, WebM, OGG — máx. 50 MB)',

            onAction: function () {
                var input = document.createElement('input');
                input.type   = 'file';
                input.accept = 'video/mp4,video/webm,video/ogg';

                input.addEventListener('change', function () {
                    var file = this.files[0];
                    if (!file) return;

                    if (file.size > 50 * 1024 * 1024) {
                        alert('Vídeo muito grande. Limite: 50 MB.');
                        return;
                    }

                    // Placeholder visual enquanto envia
                    var pid = 'vp-' + Date.now();
                    editor.insertContent(
                        '<p id="' + pid + '" style="color:#888;font-style:italic;">' +
                        '⏳ Enviando vídeo "' + file.name + '"…</p>'
                    );

                    xhrUpload(file, BASE_URL + '/util/upload_video.php')
                        .then(function (url) {
                            var ph = editor.dom.get(pid);
                            if (ph) editor.dom.remove(ph);
                            editor.insertContent(
                                '<p><video controls autoplay muted>' +
                                '<source src="' + url + '" type="' + file.type + '">' +
                                'Seu navegador não suporta vídeo HTML5.' +
                                '</video></p>'
                            );
                        })
                        .catch(function (err) {
                            var ph = editor.dom.get(pid);
                            if (ph) editor.dom.remove(ph);
                            alert('Erro ao enviar vídeo:\n' + err);
                        });
                });

                input.click();
            }
        });

        /* ── Botão: Embed YouTube / Vimeo ─────────────────────────────── */
        editor.ui.registry.addButton('embedVideo', {
            icon:    'link',
            tooltip: 'Incorporar vídeo do YouTube ou Vimeo via link',

            onAction: function () {
                editor.windowManager.open({
                    title: '🎬 Incorporar vídeo (YouTube / Vimeo)',
                    body: {
                        type: 'panel',
                        items: [
                            {
                                type:        'input',
                                name:        'url',
                                label:       'Cole o link do YouTube ou Vimeo',
                                placeholder: 'https://www.youtube.com/watch?v=...'
                            },
                            {
                                type:        'input',
                                name:        'largura',
                                label:       'Largura (padrão: 560)',
                                placeholder: '560'
                            },
                            {
                                type:        'input',
                                name:        'altura',
                                label:       'Altura (padrão: 315)',
                                placeholder: '315'
                            }
                        ]
                    },
                    buttons: [
                        { type: 'cancel', text: 'Cancelar' },
                        { type: 'submit', text: 'Incorporar', buttonType: 'primary' }
                    ],
                    onSubmit: function (api) {
                        var dados     = api.getData();
                        var embedUrl  = parsearEmbed(dados.url);

                        if (!embedUrl) {
                            alert('Link inválido. Use um link válido de YouTube ou Vimeo.');
                            return;
                        }

                        var w = parseInt(dados.largura, 10) || 560;
                        var h = parseInt(dados.altura,  10) || 315;

                        editor.insertContent(
                            '<p><iframe width="' + w + '" height="' + h + '" ' +
                            'src="' + embedUrl + '" ' +
                            'frameborder="0" ' +
                            'allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" ' +
                            'allowfullscreen ' +
                            'style="max-width:100%;border-radius:8px;"></iframe></p>'
                        );

                        api.close();
                    }
                });
            }
        });

        /* ── Sincroniza o textarea antes do submit do form ─────────────── */
        editor.on('init', function () {
            var form = editor.getElement().closest('form');
            if (form) {
                form.addEventListener('submit', function () {
                    editor.save();
                });
            }
        });
    }
});
