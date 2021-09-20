tinymce.PluginManager.add('imageSelector', function (editor, url) {
    editor.ui.registry.addButton('imageSelector', {
        tooltip: 'imageSelector',
        icon: 'image',
        title: "图片管理",
        onAction: function () {
            editor.settings.imageSelectorCallback(function (r) {
                console.log('inserting image to editor: ' + r);
                editor.execCommand('mceInsertContent', false, '<img alt="Smiley face" height="42" width="42" src="' + r + '"/>');
            });
        }
    });
});