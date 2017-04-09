<?php
$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2'                    => true,
        'blank_line_before_return' => true,
        'binary_operator_spaces'   => [
            'align_double_arrow' => true,
            'align_equals'       => true,
        ],
        'array_syntax'                    => ['syntax' => 'short'],
        'method_separation'               => true,
        'no_blank_lines_before_namespace' => true,
        'no_leading_import_slash'         => true,
        'no_leading_namespace_whitespace' => true,
        'no_mixed_echo_print'             => ['use' => 'echo'],
        'no_unused_imports'               => true,
        'ordered_imports'                 => true,
        'phpdoc_order'                    => true,
        'trim_array_spaces'               => true,
    ])->setFinder($finder);
