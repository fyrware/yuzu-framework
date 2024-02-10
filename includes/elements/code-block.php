<?php

class Yz_Code_Block {

    public static function render(array $props): void {
        global $yz;

        $id       = $yz->tools->get_value($props, 'id');
        $class    = $yz->tools->get_value($props, 'class');
        $code     = $yz->tools->get_value($props, 'code');
        $language = $yz->tools->get_value($props, 'language', 'php');

        $classes = [
            'code-block'
        ];

        if ($class) {
            $classes[] = $class;
        }

        $yz->html->element('pre', [
            'id'    => $id,
            'class' => $classes,
            'data'  => [
                'language' => $language
            ],
            'children' => function() use($yz, $language, $code) {
                $class = 'language-' . $language;

                $yz->html->element('code', [
                    'class'    => $class,
                    'children' => function() use($code) {
                        echo $code;
                    }
                ]);
            }
        ]);
    }

    public static function render_style(): void { ?>
        <style data-yz-element="code-block">
            .yz.code-block {
                margin: 0;
            }

            .yz.code-block .hljs {
                border-radius: 4px;
                border: 1px solid var(--yz-section-border-color);
            }

            .yz.code-block .hljs-ln-numbers {
                user-select: none;
                text-align: center;
                color: #ccc;
                /*border-right: 1px solid #CCC;*/
                vertical-align: top;
                padding-right: 5px !important;
            }

            .yz.code-block .hljs-ln-code {
                padding-left: 10px !important;
            }
        </style>
    <?php }

    public static function render_script(): void { ?>
        <script>
            yz.ready().observe(() => {
                hljs.highlightAll();

                yz('.code-block').forEach((container) => {

                    yz('.hljs', container).forEach((block) => {
                        hljs.lineNumbersBlock(block, { singleLine: true });
                    });
                });
            });
        </script>
    <?php }
}