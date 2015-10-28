<?php

namespace AgreablePugpigPlugin\Hooks;
use AgreablePugpigPlugin\Controllers\EditionsAdminController;
use AgreablePugpigPlugin\Helper;

class LoaderHelper {

  public function init() {
    \add_filter('timber/loader/paths', array($this, 'add_paths'), 10);
  }

  function add_paths($paths){
    // Get views specified in herbert.
    $namespaces = Helper::get('views');
    foreach ($namespaces as $namespace => $views){
      foreach ((array) $views as $view){
        // Add to timber $paths array.
        array_unshift($paths, $view);
      }
    }
    return $paths;
  }
}

(new LoaderHelper)->init();


(new EditionsAdminController)->init();
