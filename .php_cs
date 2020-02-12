<?php
$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->in('src/')
    ->in('tests/');

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2'                                 => true,
        'cast_spaces'                           => ['space' => 'single'],
        'concat_space'                          => false,
        'array_syntax'                          => ['syntax' => 'short'],
        'no_trailing_comma_in_singleline_array' => true,
        'trim_array_spaces'                     => true,
        'ordered_imports'                       => true,
        'phpdoc_separation'                     => false,
        'phpdoc_annotation_without_dot'         => false,
        'phpdoc_summary'                        => false,
        'phpdoc_indent'                         => true,
        'phpdoc_order'                          => true,
        'phpdoc_types'                          => true,
        'phpdoc_trim'                           => true,
        'pre_increment'                         => false,
        'no_blank_lines_before_namespace'       => true,
        'no_leading_import_slash'               => true,
        'blank_line_before_return'              => true,
        'binary_operator_spaces'                => [
            'operators' => [
                '='  => 'align_single_space',
                '=>' => 'align_single_space',
            ],
        ],
        'full_opening_tag'                      => true,
    ])
    ->setFinder($finder);
