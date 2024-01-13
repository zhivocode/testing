<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

require 'vendor/autoload.php';

$finder = Finder::create()
    ->in(
        [
            __DIR__ . '/pkg',
            __DIR__ . '/test',
        ]
    )
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRules(
        [
            '@PSR12'                                      => true,
            'array_syntax'                                => ['syntax' => 'short'],
            'array_indentation'                           => true,
            'binary_operator_spaces'                      => [
                'default'   => 'single_space',
                'operators' => [
                    '='  => 'align_single_space',
                    '+=' => 'align_single_space',
                    '.=' => 'align_single_space',
                    '=>' => 'align_single_space',
                ],
            ],
            'no_multiline_whitespace_around_double_arrow' => true,
            'multiline_whitespace_before_semicolons'      => [
                'strategy' => 'no_multi_line',
            ],
            'no_unused_imports'                           => true,
            'function_declaration'                        => [
                'closure_fn_spacing' => 'none',
            ],
            'ordered_imports'                             => ['sort_algorithm' => 'alpha'],
            'not_operator_with_successor_space'           => true,
            'trailing_comma_in_multiline'                 => true,
            'phpdoc_scalar'                               => true,
            'unary_operator_spaces'                       => true,
            'blank_line_before_statement'                 => [
                'statements' => ['break', 'continue', 'declare', 'return', 'throw', 'try'],
            ],
            'phpdoc_single_line_var_spacing'              => true,
            'phpdoc_var_without_name'                     => true,
            'class_attributes_separation'                 => [
                'elements' => [
                    'method' => 'one',
                ],
            ],
            'method_argument_space'                       => [
                'on_multiline'                     => 'ensure_fully_multiline',
                'keep_multiple_spaces_after_comma' => true,
            ],
            'single_trait_insert_per_statement'           => true,
        ]
    )
    ->setFinder($finder)
    ->setRiskyAllowed(false)
    ->setUsingCache(true);
