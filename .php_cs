 <?php

 $config = PhpCsFixer\Config::create();

 $finder = PhpCsFixer\Finder::create();

 $finder->in(['src', 'tests']);

 $config->setFinder($finder);

 $config->setRules([
      //'@PhpCsFixer',
      'self_accessor' => false,
      'void_return' => false,
      'ternary_to_null_coalescing' => true,
      'visibility_required' => ['property', 'method', 'const'],
      'heredoc_indentation' => true,
      'heredoc_to_nowdoc' => true,
      'is_null' => true,
      'modernize_types_casting' => true,
      'dir_constant' => true,
      'fopen_flag_order' => true,
      'fopen_flags' => true,
      'no_alias_functions' => true,
      'ereg_to_preg' => true,
      'implode_call' => true,
      'include' => true,
      'no_unset_on_property' => true,
      'no_unused_imports' => true,
      'no_useless_else' => true,
      'no_useless_return' => true,
      'compact_nullable_typehint' => true,
  ]);
 $config->setRiskyAllowed(true);

 return $config;
