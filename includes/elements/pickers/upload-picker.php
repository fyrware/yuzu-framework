<?php

class Yz_Upload_Picker {

    public static function render(array $props): void {
        global $yz;

        $id    = $yz->tools->key_or_default($props, 'id');
        $name  = $yz->tools->key_or_default($props, 'name', $id);
        $class = $yz->tools->key_or_default($props, 'class');
        $path  = $yz->tools->key_or_default($props, 'path');

        assert(isset($name), 'name is required');

        $classes = [
            'upload-picker'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $unique_id = uniqid();

        $yz->html->input([
            'id' => $id,
            'name' => $name,
            'class' => $classes,
            'hidden' => true,
            'data' => [
                'unique_id' => $unique_id,
            ]
        ]);

        $yz->html->dialog([
            'modal' => true,
            'full_size' => true,
            'icon' => 'folders',
            'title' => 'Choose A File',
            'class' => 'upload-picker-dialog',
            'data' => [
                'upload_picker' => $name,
                'unique_id' => $unique_id
            ],
            'children' => function() use($path) {
                global $yz;

                $yz->html->flex_layout([
                    'direction' => 'column',
                    'class' => 'upload-picker-layout',
                    'children' => function () use ($path) {
                        global $yz;

                        $yz->html->flex_layout([
                            'gap' => 10,
                            'alignment' => 'center',
                            'class' => 'upload-picker-toolbar',
                            'children' => function () use ($path) {
                                global $yz;

                                $yz->html->button([
                                    'icon' => 'arrow-fat-left',
                                ]);
                                $yz->html->element('div', [
                                    'class' => 'upload-picker-toolbar-path',
                                    'children' => function () use ($path) {
                                        global $yz;

                                        $yz->html->text('file://uploads/' . ($path ?? ''));
                                    }
                                ]);
                                $yz->html->button([
                                    'icon' => 'folder-plus',
                                    'label' => 'New Folder'
                                ]);
                                $yz->html->button([
                                    'icon' => 'upload',
                                    'label' => 'Upload File'
                                ]);
                            }
                        ]);

                        $yz->html->flex_layout([
                            'class' => 'upload-picker-viewer',
                            'data' => [ 'path' => $path ],
                            'children' => function() use($yz) {
                                $yz->html->element('template', [
                                    'data' => [ 'template' => 'folder' ],
                                    'children' => function() use($yz) {
                                        $yz->html->card([
                                            'padding' => 20,
                                            'class' => 'upload-picker-entry-wrapper',
                                            'children' => function() use($yz) {
                                                $yz->html->flex_layout([
                                                    'gap' => 10,
                                                    'class' => 'upload-picker-entry upload-picker-folder',
                                                    'direction' => 'column',
                                                    'alignment' => 'center',
                                                    'children' => function() use($yz) {
                                                        $yz->html->icon('folder-open', [ 'appearance' => 'duotone' ]);
                                                        $yz->html->flex_layout([
                                                            'alignment' => 'center',
                                                            'direction' => 'column',
                                                            'children' => function() use($yz) {
                                                                $yz->html->text('', [ 'class' => 'upload-picker-entry-name', 'variant' => 'strong' ]);
                                                                $yz->html->text('FOLDER', [ 'class' => 'upload-picker-entry-meta' ]);
                                                            }
                                                        ]);
                                                    }
                                                ]);
                                            }
                                        ]);
                                    }
                                ]);
                                $yz->html->element('template', [
                                    'data' => [ 'template' => 'file' ],
                                    'children' => function() use($yz) {
                                        $yz->html->card([
                                            'class'    => 'upload-picker-entry-wrapper',
                                            'children' => function() use($yz) {
                                                $yz->html->flex_layout([
                                                    'gap'       => 10,
                                                    'class'     => 'upload-picker-entry upload-picker-file',
                                                    'direction' => 'column',
                                                    'alignment' => 'center',
                                                    'children'  => function() use($yz) {
                                                        $yz->html->card([
                                                            'padding' => 5,
                                                            'children' => function() use($yz) {
                                                                $yz->html->image('', [ 'class' => 'upload-picker-entry-image' ]);
                                                            }
                                                        ]);
                                                        $yz->html->flex_layout([
                                                            'alignment' => 'center',
                                                            'direction' => 'column',
                                                            'children'  => function() use($yz) {
                                                                $yz->html->text('', [ 'class' => 'upload-picker-entry-name', 'variant' => 'strong' ]);
                                                                $yz->html->text('', [ 'class' => 'upload-picker-entry-meta' ]);
                                                            }
                                                        ]);
                                                    }
                                                ]);
                                            }
                                        ]);
                                    }
                                ]);
                                $yz->html->grid_layout([
                                    'gap' => 20,
                                    'class' => 'upload-picker-grid',
                                    'columns' => 4,
                                ]);
                                $yz->html->empty_state([
                                    'icon' => 'file-dashed',
                                    'title' => 'No File Selected',
                                    'description' => 'Select an uploaded file to see more details',
                                    'class' => 'upload-picker-details-empty'
                                ]);
                                $yz->html->flex_layout([
                                    'gap' => 20,
                                    'direction' => 'column',
                                    'class' => 'upload-picker-details',
                                    'style' => [
                                        'display' => 'none'
                                    ],
                                    'children' => function() use($yz) {
                                        $yz->html->card([
                                            'padding' => 10,
                                            'class' => 'upload-picker-details-preview',
                                            'children' => function() use($yz) {
                                                $yz->html->card([
                                                    'padding' => '0px',
                                                    'children' => function() use($yz) {
                                                        $yz->html->image('');
                                                    }
                                                ]);
                                            }
                                        ]);
                                        $yz->html->flex_layout([
                                            'gap' => 5,
                                            'alignment' => 'start',
                                            'direction' => 'column',
                                            'children' => function() use($yz) {
                                                $yz->html->title('Image Name', [ 'level' => 3, 'class' => 'upload-picker-details-title' ]);
                                                $yz->html->text('Ext - 0x0', [ 'class' => 'upload-picker-details-meta' ]);
                                            }
                                        ]);
                                        $yz->html->grid_layout([
                                            'columns' => 3,
                                            'gap' => 20,
                                            'children' => function() use($yz) {
                                                $yz->html->flex_layout([
                                                    'direction' => 'column',
                                                    'children' => function() use($yz) {
                                                        $yz->html->text('File Type', [ 'variant' => 'strong' ]);
                                                        $yz->html->text('image/png', [ 'class' => 'upload-picker-details-file-type' ]);
                                                    }
                                                ]);
                                                $yz->html->flex_layout([
                                                    'direction' => 'column',
                                                    'children' => function() use($yz) {
                                                        $yz->html->text('Dimensions', [ 'variant' => 'strong' ]);
                                                        $yz->html->text('0x0', [ 'class' => 'upload-picker-details-dimensions' ]);
                                                    }
                                                ]);
                                                $yz->html->flex_layout([
                                                    'direction' => 'column',
                                                    'children' => function() use($yz) {
                                                        $yz->html->text('File Size', [ 'variant' => 'strong' ]);
                                                        $yz->html->text('0.0 MB', [ 'class' => 'upload-picker-details-file-size' ]);
                                                    }
                                                ]);
                                            }
                                        ]);
                                    }
                                ]);
                            }
                        ]);

                        $yz->html->flex_layout([
                            'gap' => 10,
                            'alignment' => 'center',
                            'justification' => 'space-between',
                            'class' => 'upload-picker-footer',
                            'children' => function() use($yz) {
                                $yz->html->text('1 file selected');
                                $yz->html->flex_layout([
                                    'gap' => 10,
                                    'children' => function() use($yz) {
                                        $yz->html->button([
                                            'type' => 'reset',
                                            'label' => 'Cancel'
                                        ]);
                                        $yz->html->button([
                                            'class' => 'upload-picker-submit',
                                            'type' => 'submit',
                                            'variant' => 'primary',
                                            'icon' => 'paperclip',
                                            'label' => 'Use File'
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style>
            .yz.upload-picker-layout {
                margin: -20px;
                height: calc(100% + 40px);
            }

            .yz.upload-picker-toolbar {
                flex-shrink: 0;
                height: 48px;
                padding: 0 20px;
                border-bottom: 1px solid var(--yz-section-border-color);
            }

            .yz.upload-picker-toolbar-path {
                user-select: none;
                flex-grow: 1;
                display: flex;
                align-items: center;
                height: 30px;
                padding: 0 10px;
                background: rgb(0, 0, 0, 7.5%);
                border-radius: 4px;
                font-size: 12px;
                /*font-weight: 700;*/
            }

            .yz.upload-picker-footer {
                flex-shrink: 0;
                height: 48px;
                padding: 0 20px;
                border-top: 1px solid var(--yz-section-border-color);
            }

            .yz.upload-picker-viewer {
                flex-grow: 1;
            }

            .yz.upload-picker-details-empty {
                flex-shrink: 0;
                width: 30%;
                border-left: 1px solid var(--yz-section-border-color);
            }

            .yz.upload-picker-details {
                flex-shrink: 0;
                width: 30%;
                padding: 20px;
                box-sizing: border-box;
                border-left: 1px solid var(--yz-section-border-color);
            }

            .yz.upload-picker-details-preview {
                height: 300px;
            }

            .yz.upload-picker-details-preview > .yz.card {
                height: 100%;
            }

            .yz.upload-picker-details-preview .yz.image {
                width: 100%;
                height: 100%;
                object-fit: contain;
            }

            .yz.upload-picker-details-title {
                margin: 0;
                padding: 0;
            }

            .yz.upload-picker-details-meta {
                /*text-transform: uppercase;*/
                font-size: 12px;
            }

            .yz.upload-picker-grid {
                flex-grow: 1;
                height: fit-content;
                padding: 20px;
            }

            .yz.upload-picker-entry-wrapper {
                user-select: none;
                cursor: pointer;
                position: relative;
                padding: 10px !important;
            }

            .yz.upload-picker-entry-wrapper:hover {
                border: 1px solid var(--yz-input-border-color);
                transform: scale(1.025);
            }

            .yz.upload-picker-entry-wrapper[data-selected="true"] {
                border: 1px solid var(--yz-highlight-color);
                box-shadow: 0 0 0 1px var(--yz-highlight-color);
            }

            .upload-picker-entry-meta {
                font-size: 10px;
                text-transform: uppercase;
            }

            .yz.upload-picker-folder .yz.icon {
                width: 120px;
                padding: 11px;
                opacity: 0.5;
                box-sizing: content-box;
            }

            .yz.upload-picker-file .yz.card {
                width: 100%;
                align-items: center;
                padding: 0 !important;
            }

            .yz.upload-picker-file .yz.image {
                width: 100%;
                height: 140px;
                object-fit: contain;
            }

            .yz.upload-picker-file .yz.checkbox {
                position: absolute;
                top: 13px;
                left: 13px;
            }
        </style>
    <?php }

    public static function render_script(): void { ?>
        <script>
            yz.ready().observe(() => {
                yz('.upload-picker-dialog').forEach((dialog) => {
                    const picker         = yz('.upload-picker-viewer', dialog).item();
                    const pickerPath     = yz('.upload-picker-toolbar-path', dialog).item();
                    const uploadGrid     = yz('.upload-picker-grid', picker).item();
                    const submitButton   = yz('.upload-picker-submit', dialog).item();
                    const folderTemplate = yz('template[data-template="folder"]', picker).item();
                    const fileTemplate    = yz('template[data-template="file"]', picker).item();

                    const pickerState = {
                        selectedFiles: []
                    };

                    function renderFolder(folder) {
                        const folderInstance = yz.instance(folderTemplate);
                        const folderWrapper  = yz('.upload-picker-entry-wrapper', folderInstance).item();
                        const folderName     = yz('.upload-picker-entry-name', folderInstance).item();

                        folderName.textContent = folder;

                        yz.spy(folderWrapper, 'dblclick').observe(() => {
                            const path = picker.dataset.path;
                            const folderPath = (path ? `${ path }${ folder }` : folder) + '/';

                            yz.query('yz_read_uploads_directory', { path: folderPath }).observe((response) => {
                                if (response.success) {
                                    picker.dataset.path = folderPath;
                                    pickerPath.textContent = 'file://uploads/' + folderPath;
                                    updateUploadGrid(response.data);
                                }
                            });
                        });

                        uploadGrid.append(folderInstance);
                    }

                    function renderFile(file) {
                        const fileInstance = yz.instance(fileTemplate);
                        const fileWrapper  = yz('.upload-picker-entry-wrapper', fileInstance).item();
                        const fileImage    = yz('.upload-picker-entry-image', fileInstance).item();
                        const fileName     = yz('.upload-picker-entry-name', fileInstance).item();
                        const fileMeta     = yz('.upload-picker-entry-meta', fileInstance).item();

                        fileImage.src        = file.thumbnail_url;
                        fileName.textContent = file.title;
                        fileMeta.textContent = `${ file.extension } - ${ file.width } x ${ file.height }`;

                        yz.spy(fileWrapper, 'click').observe(() => {
                            yz('.upload-picker-entry-wrapper[data-selected="true"]', picker).forEach((wrapper) => {
                                wrapper.dataset.selected = String(false);
                            });

                            pickerState.selectedFiles = [ file ];
                            fileWrapper.dataset.selected = String(true);

                            updateDetailsSection(file);
                        });

                        uploadGrid.appendChild(fileInstance);
                    }

                    function clearUploadGrid() {
                        while (uploadGrid.firstChild) {
                            uploadGrid.removeChild(uploadGrid.firstChild);
                        }
                    }

                    function updateUploadGrid(contents) {
                        clearUploadGrid();

                        contents.folders.forEach(renderFolder);
                        contents.files.forEach(renderFile);
                    }

                    function updateDetailsSection(file) {
                        const emptyPlaceholder = yz('.upload-picker-details-empty', picker).item();
                        const detailsContainer = yz('.upload-picker-details', picker).item();
                        const previewImage     = yz('.upload-picker-details-preview .yz.image', picker).item();
                        const detailsTitle     = yz('.upload-picker-details-title', picker).item();
                        const detailsMeta      = yz('.upload-picker-details-meta', picker).item();
                        const fileType          = yz('.upload-picker-details-file-type', picker).item();
                        const fileDimensions    = yz('.upload-picker-details-dimensions', picker).item();
                        const fileSize          = yz('.upload-picker-details-file-size', picker).item();

                        emptyPlaceholder.style.display = 'none';
                        detailsContainer.style.display = 'flex';

                        previewImage.src = file.url;

                        detailsTitle.textContent = file.title;
                        detailsMeta.textContent  = `Uploaded ${ yz.date(file.upload_date, { month: 'long', day: 'numeric', year: 'numeric' }) }`;

                        fileType.textContent       = file.mime_type;
                        fileDimensions.textContent = `${ file.width } x ${ file.height }`;
                        fileSize.textContent       = `${ (file.file_size / 1024).toFixed(2) } KB`;
                    }

                    yz.query('yz_read_uploads_directory', { path: picker.dataset.path }).observe((response) => {
                        if (response.success) {
                            updateUploadGrid(response.data);
                        }
                    });

                    yz.spy(submitButton, 'click').observe((click) => { // BUG here: input name is same for forms which use arrays... change image in one changes for all inputs
                        const input = yz(`input[name="${ dialog.dataset.uploadPicker }"][data-unique-id="${ dialog.dataset.uniqueId }"]`).item();

                        input.value       = pickerState.selectedFiles.map((file) => file.id).join(',');
                        input.dataset.src = pickerState.selectedFiles.map((file) => file.url).join(',');

                        yz.do(input, 'change');
                    });
                });
            });
        </script>
    <?php }
}