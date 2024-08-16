<?php

class Yz_Upload_Picker {

    public static function render(array $props): void {
        global $yz;

        $id       = $yz->tools->get_value($props, 'id');
        $name     = $yz->tools->get_value($props, 'name', $id);
        $class    = $yz->tools->get_value($props, 'class');
        $path     = $yz->tools->get_value($props, 'path');
        $label    = $yz->tools->get_value($props, 'label');
        $value    = $yz->tools->get_value($props, 'value');
        $preview  = $yz->tools->get_value($props, 'preview');
        $hidden   = $yz->tools->get_value($props, 'hidden');
        $required = $yz->tools->get_value($props, 'required');

        assert(isset($name), 'name is required');

        $classes = [
            'upload-picker'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $unique_id = uniqid();

        if (!$hidden) {
            $yz->html->flex_layout([
                'class' => 'upload-picker-trigger',
                'direction' => 'column',
                'data' => [
                    'input' => $unique_id
                ],
                'children' => function() use($yz, $label, $value, $preview) {
                    $yz->html->text($label, [ 'class' => 'upload-picker-label', 'variant' => 'label' ]);
                    $yz->html->flex_layout([
                        'class' => 'upload-picker-preview',
                        'direction' => 'column',
                        'alignment' => 'center',
                        'justification' => 'center',
                        'children' => function() use($yz, $value) {
                            if ($value) {
                                $yz->html->image($value);
                            } else {
                                $yz->html->flex_layout([
                                    'class' => 'upload-picker-placeholder',
                                    'children' => function() use($yz) {
                                        $yz->html->icon('park', [ 'appearance' => 'light' ]);
                                    }
                                ]);
                            }
                        }
                    ]);
                    $yz->html->flex_layout([
                        'class' => 'upload-picker-actions',
                        'children' => function() use($yz) {
                            $yz->html->button([
                                'class' => 'upload-picker-select',
                                'label' => 'Select File'
                            ]);
                            $yz->html->button([
                                'class' => 'upload-picker-remove',
                                'color' => 'danger',
                                'icon' => 'x-circle'
                            ]);
                        }
                    ]);
                }
            ]);
        }

        $yz->html->input([
            'id' => $id,
            'name' => $name,
            'class' => $classes,
            'hidden' => true,
            'required' => $required,
            'data' => [
                'unique-id' => $unique_id,
            ]
        ]);

        $yz->html->dialog([
            'modal' => true,
            'full_size' => true,
            'icon' => 'folders',
            'title' => 'Choose A File',
            'class' => 'upload-picker-dialog',
            'data' => [
                'upload-picker' => $name,
                'unique-id' => $unique_id
            ],
            'children' => function() use($path, $unique_id) {
                global $yz;

                $yz->html->flex_layout([
                    'direction' => 'column',
                    'class' => 'upload-picker-layout',
                    'children' => function () use ($path, $unique_id) {
                        global $yz;

                        $yz->html->flex_layout([
                            'gap' => 10,
                            'alignment' => 'center',
                            'class' => 'upload-picker-toolbar',
                            'children' => function () use ($path, $unique_id) {
                                global $yz;

                                $yz->html->button([
                                    'class' => 'upload-picker-back-button',
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
                                    'class' => 'upload-picker-new-folder',
                                    'icon' => 'folder-plus',
                                    'label' => 'New Folder'
                                ]);
                                yz()->html->input([
                                    'id'     => $unique_id . '_upload-picker-file-input',
                                    'class'  => 'upload-picker-file-input',
                                    'type'   => 'file',
                                    'hidden' => true,
                                ]);
                                yz()->html->element('label', [
                                    'class' => 'upload-picker-upload',
                                    'attr' => [
                                        'for' => $unique_id . '_upload-picker-file-input'
                                    ],
                                    'children' => function() {
                                        yz()->html->icon('upload', [ 'appearance' => 'duotone' ]);
                                        yz()->html->text('Upload File');
                                    }
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
                                yz()->html->flex_layout([
                                    'grow' => true,
                                    'padding' => 20,
                                    'overflow' => 'auto',
                                    'children' => function() {
                                        yz()->html->grid_layout([
                                            'gap' => 20,
                                            'class' => 'upload-picker-grid',
                                            'columns' => 4,
                                        ]);
                                    }
                                ]);
                                $yz->html->empty_state([
                                    'icon' => 'file-dashed',
                                    'title' => 'No File Selected',
                                    'description' => 'Select an uploaded file to see more details',
                                    'class' => 'upload-picker-details-empty'
                                ]);
                                $yz->html->flex_layout([
                                    'gap' => 20,
                                    'alignment' => 'center',
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
                                                        $yz->html->element('object', [
                                                            'attr' => [
                                                                'hidden' => true
                                                            ]
                                                        ]);
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

        $yz->html->dialog([
            'title' => 'New Folder',
            'icon' => 'folder-plus',
            'class' => 'upload-picker-new-folder-dialog',
            'width' => 400,
            'data' => [
                'upload-picker' => $name,
                'unique-id' => $unique_id
            ],
            'children' => function() {
                yz()->html->flex_layout([
                    'gap' => 20,
                    'direction' => 'column',
                    'children' => function() {
                        yz()->html->input([
                            'label' => 'Folder Name',
                            'name' => 'folder_name',
                            'required' => true
                        ]);
                        yz()->html->flex_layout([
                            'gap' => 10,
                            'justification' => 'end',
                            'children' => function() {
                                yz()->html->button([
                                    'label' => 'Cancel',
                                    'variant' => 'secondary',
                                    'type' => 'reset'
                                ]);
                                yz()->html->button([
                                    'label' => 'Create Folder',
                                    'variant' => 'primary',
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
            .yz.upload-picker-trigger {
                box-sizing: border-box;
                width: 162px;
                margin: 0;
                min-width: 0;
                position: relative;

                & .yz.upload-picker-label {
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                & .yz.upload-picker-preview {
                    cursor: pointer;
                    width: 160px;
                    height: 160px;
                    border: 1px solid #8c8f94;
                    border-bottom: none;
                    border-radius: 4px 4px 0 0;
                    background-color: #ffffff;
                    background-image:
                        repeating-linear-gradient(
                            45deg,
                            #f0f0f1 25%,
                            transparent 25%,
                            transparent 75%,
                            #f0f0f1 75%,
                            #f0f0f1
                        ),
                        repeating-linear-gradient(
                            45deg,
                            #f0f0f1 25%,
                            #ffffff 25%,
                            #ffffff 75%,
                            #f0f0f1 75%,
                            #f0f0f1
                        );
                    background-position: 0 0, 8px 8px;
                    background-size: 16px 16px;
                    overflow: hidden;

                    & img {
                        display: block;
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }

                    & svg {
                        display: block;
                        width: 96px;
                        height: 96px;
                        fill: #c3c4c7;
                    }
                }

                & .yz.upload-picker-actions {

                    & .yz.button {
                        border-radius: 0 0 4px 4px;
                    }

                    & .yz.upload-picker-select {
                        width: 100%;
                        border-right: 0;
                        border-bottom-right-radius: 0;
                        justify-content: center;
                    }

                    & .yz.upload-picker-remove {
                        border-bottom-left-radius: 0;
                    }
                }
            }

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
                flex-shrink: 1;
                height: 0;
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

            .yz.upload-picker-entry-name {
                text-align: center;
            }

            .yz.upload-picker-details-preview {
                /*height: 300px;*/

                &:has(object:not([hidden])) {
                    width: 100%;
                }

                object {
                    display: block;
                    width: 100%;
                    aspect-ratio: 1.33;

                    &[hidden] {
                        display: none;
                    }
                }
            }

            .yz.upload-picker-details-preview > .yz.card {
                display: block;
                height: 100%;
            }

            .yz.upload-picker-details-preview .yz.image {
                display: block;
                max-width: 100%;
                max-height: 300px;
                /*height: 100%;*/
                /*object-fit: cover;*/

                &[hidden] {
                    display: none;
                }
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
                align-self: start;
                flex-grow: 1;
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
                object-fit: cover;
            }

            .yz.upload-picker-file .yz.checkbox {
                position: absolute;
                top: 13px;
                left: 13px;
            }

            label.upload-picker-upload {
                display: inline-flex;
                align-items: center;
                gap: 5px;
                text-decoration: none;
                font-size: 13px;
                line-height: 2.15384615;
                min-height: 30px;
                margin: 0;
                padding: 0 10px;
                cursor: pointer;
                border-width: 1px;
                border-style: solid;
                -webkit-appearance: none;
                border-radius: 3px;
                white-space: nowrap;
                box-sizing: border-box;
                color: #2271b1;
                border-color: #2271b1;
                background: #f6f7f7;

                &:hover {
                    background: #f0f0f1;
                    border-color: #0a4b78;
                    color: #0a4b78;
                }

                svg {
                    width: 22px;
                    height: 22px;
                }
            }
        </style>
    <?php }

    public static function render_script(): void { ?>
        <script>
            yz.ready().observe(() => {
                yz('.upload-picker').forEach((uploadPicker) => {
                    const uniqueId = uploadPicker.data('uniqueId');
                    const uploadPickerTrigger = yz('.upload-picker-trigger[data-input="' + uniqueId + '"]');
                    const uploadPickerDialog = yz('.upload-picker-dialog[data-unique-id="' + uniqueId + '"]');
                    const uploadPickerClear = yz('.upload-picker-remove', uploadPickerTrigger)

                    uploadPickerTrigger.spy('click').observe(() => {
                        uploadPickerDialog.show({ modal: true });
                    });

                    uploadPickerClear.spy('click').observe((click) => {
                        uploadPicker.value('').trigger('change');
                        click.stopPropagation();
                    });

                    uploadPicker.spy('change').observe(() => {
                        console.log('change', uploadPicker.value());
                        if (uploadPicker.value()) {
                            yz.ajax.query('yz_get_upload_url', { id: uploadPicker.value() }).observe((response) => {
                                if (response.success) {
                                    const preview = yz('.upload-picker-preview', uploadPickerTrigger).item();
                                    const image = yz('img', preview).item() ?? document.createElement('img');
                                    const icon = yz('svg', preview).item();

                                    if (image.parentElement !== preview) {
                                        preview.appendChild(image);
                                    }

                                    icon.style.display = 'none';
                                    image.src = response.data.url;
                                }
                            });
                        } else {
                            const preview = yz('.upload-picker-preview', uploadPickerTrigger).item();
                            const image = yz('img', preview).item() ?? document.createElement('img');
                            const icon = yz('svg', preview).item();

                            if (image) {
                                image.remove();
                            }

                            icon.style.display = 'block';
                        }
                    });
                });
                yz('.upload-picker-dialog').forEach((dialog) => {
                    const picker          = yz('.upload-picker-viewer', dialog).item();
                    const backButton      = yz('.upload-picker-back-button', dialog).item();
                    const pickerPath      = yz('.upload-picker-toolbar-path', dialog).item();
                    const uploadGrid      = yz('.upload-picker-grid', picker).item();
                    const submitButton    = yz('.upload-picker-submit', dialog).item();

                    const folderTemplate  = yz('template[data-template="folder"]', picker).item();
                    const fileTemplate     = yz('template[data-template="file"]', picker).item();

                    const newFolderButton = yz('button.upload-picker-new-folder', dialog);
                    const newFolderDialog = yz('dialog.upload-picker-new-folder-dialog');
                    const newFolderInput  = newFolderDialog.select('input');
                    const newFolderSubmit = newFolderDialog.select('button[type="button"]');

                    const uploadButton = dialog.select('button.upload-picker-upload');
                    const uploadInput = dialog.select('input.upload-picker-file-input');

                    const pickerState = {
                        selectedFiles: []
                    };

                    function openNewFolderDialog() {
                        newFolderDialog.show({ modal: true });
                    }

                    function createNewFolder(path) {
                        yz.ajax.query('yz_create_upload_folder', { path }).observe((response) => {
                            if (response.success) {
                                scanUploadsFolder(path);
                                newFolderDialog.hide();
                            } else {
                                alert('Failed to create folder');
                            }
                        });
                    }

                    newFolderSubmit.spy('click').observe(() => {
                        const folderName = newFolderInput.value();

                        if (folderName) {
                            const path = picker.dataset.path;
                            const folderPath = (path ? `${ path }${ folderName }` : folderName) + '/';

                            createNewFolder(folderPath);
                        }
                    });

                    uploadInput.spy('change').observe((change) => {
                        const path = picker.dataset.path;
                        const files = uploadInput.prop('files');
                        const formData = new FormData();

                        formData.append('file', files[0]);
                        formData.append('path', path);

                        yz.ajax.submit('yz_upload_file', formData).observe((response) => {
                            console.log(response);
                            if (response.success) {
                                scanUploadsFolder(path);
                            } else {
                                alert('Failed to upload files');
                            }
                        });

                        // if (files.length > 0) {
                        //     const formData = new FormData();
                        //
                        //     for (let i = 0; i < files.length; i++) {
                        //         formData.append('file[]', files[i]);
                        //     }
                        //
                        //     yz.ajax.upload('yz_upload_files', formData).observe((response) => {
                        //         if (response.success) {
                        //             scanUploadsFolder();
                        //         } else {
                        //             alert('Failed to upload files');
                        //         }
                        //     });
                        // }
                    });

                    function renderFolder(folder) {
                        const folderInstance = yz.instance(folderTemplate);
                        const folderWrapper  = yz('.upload-picker-entry-wrapper', folderInstance).item();
                        const folderName     = yz('.upload-picker-entry-name', folderInstance).item();

                        folderName.textContent = folder;

                        yz.spy(folderWrapper, 'dblclick').observe(() => {
                            const path = picker.dataset.path;
                            const folderPath = (path ? `${ path }${ folder }` : folder) + '/';

                            scanUploadsFolder(folderPath);
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
                                wrapper.data('selected', String(false));
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

                    function updateDetailsSection(file = undefined) {
                        const emptyPlaceholder = yz('.upload-picker-details-empty', picker).item();
                        const detailsContainer = yz('.upload-picker-details', picker).item();
                        const previewImage     = yz('.upload-picker-details-preview .yz.image', picker).item();
                        const previewObject    = yz('.upload-picker-details-preview object', picker).item();
                        const detailsTitle     = yz('.upload-picker-details-title', picker).item();
                        const detailsMeta      = yz('.upload-picker-details-meta', picker).item();
                        const fileType          = yz('.upload-picker-details-file-type', picker).item();
                        const fileDimensions    = yz('.upload-picker-details-dimensions', picker).item();
                        const fileSize          = yz('.upload-picker-details-file-size', picker).item();

                        emptyPlaceholder.style.display = 'none';
                        detailsContainer.style.display = 'flex';

                        if (file) {

                            if (file.mime_type.startsWith('image/')) {
                                previewImage.src = file.url;
                                previewImage.hidden = false;
                                previewObject.hidden = true;
                            } else {
                                previewObject.type = file.mime_type;
                                previewObject.data = file.url;
                                previewObject.hidden = false;
                                previewImage.hidden = true;
                            }

                            detailsTitle.textContent = file.title;
                            detailsMeta.textContent  = `Uploaded ${ yz.date(file.upload_date, { month: 'long', day: 'numeric', year: 'numeric' }) }`;

                            fileType.textContent       = file.mime_type;
                            fileDimensions.textContent = `${ file.width } x ${ file.height }`;
                            fileSize.textContent       = `${ (file.file_size / 1024).toFixed(2) } KB`;
                        } else {
                            emptyPlaceholder.style.display = 'flex';
                            detailsContainer.style.display = 'none';
                        }
                    }

                    function scanUploadsFolder(path = picker.dataset.path) {
                        yz.ajax.query('yz_read_uploads_directory', { path }).observe((response) => {
                            if (response.success) {
                                if (path) picker.dataset.path = path;
                                pickerPath.textContent = 'wordpress://uploads/' + (path ?? '');
                                updateUploadGrid(response.data);
                            }
                        });
                    }

                    function resetPickerState() {
                        pickerState.selectedFiles = [];
                        delete picker.dataset.path;
                        updateDetailsSection();
                        scanUploadsFolder();
                    }

                    newFolderButton.spy('click').observe(() => {
                        openNewFolderDialog();
                    });

                    yz.spy(backButton, 'click').observe(() => {
                        const path = picker.dataset.path;
                        delete picker.dataset.path;

                        const pathParts = path.split('/');
                        pathParts.pop(); // path ends with /, so last part is empty
                        pathParts.pop();

                        const newPath = pathParts.length > 0 ? pathParts.join('/') + '/' : undefined;

                        scanUploadsFolder(newPath);
                    });

                    yz.spy(submitButton, 'click').observe(() => {
                        const input = yz(`input[name="${ dialog.data('uploadPicker') }"][data-unique-id="${ dialog.data('uniqueId') }"]`).item();

                        input.value       = pickerState.selectedFiles.map((file) => file.id).join(',');
                        input.dataset.src = pickerState.selectedFiles.map((file) => file.url).join(',');

                        yz.do(input, 'change');

                        resetPickerState();
                    });

                    scanUploadsFolder();
                });
            });
        </script>
    <?php }
}