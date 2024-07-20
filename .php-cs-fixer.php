<?php
$finder = PhpCsFixer\Finder::create()
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->in('src/')
    ->in('tests/');

return (new PhpCsFixer\Config())->setRules([
    '@PSR2'                           => true,
    'cast_spaces'                     => ['space' => 'single'],
    'concat_space'                    => false,
    'array_syntax'                    => ['syntax' => 'short'],
    'no_trailing_comma_in_singleline' => [
        'elements' => ['arguments', 'array_destructuring', 'array', 'group_import'],
    ],
    'trim_array_spaces'               => true,
    'ordered_imports'                 => true,
    'phpdoc_separation'               => false,
    'phpdoc_annotation_without_dot'   => false,
    'phpdoc_summary'                  => false,
    'phpdoc_indent'                   => true,
    'phpdoc_order'                    => true,
    'phpdoc_types'                    => true,
    'phpdoc_trim'                     => true,
    'increment_style'                 => false,
    'blank_lines_before_namespace'    => ['min_line_breaks' => 1, 'max_line_breaks' => 1],
    'no_leading_import_slash'         => true,
    'blank_line_before_statement'     => ['statements' => ['return']],
    'binary_operator_spaces'          => [
        'operators' => [
            '='   => 'align_single_space',
            '=>'  => 'align_single_space',
            '??=' => 'align_single_space',
        ],
    ],
    'full_opening_tag'                => true,
])->setFinder($finder);
