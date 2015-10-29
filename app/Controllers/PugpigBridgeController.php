<?php namespace AgreablePugpigPlugin\Controllers;

use AgreablePugpigPlugin\Helper;
use AgreablePugpigPlugin\Controllers\LinkGeneratorController;

class PugpigBridgeController {

  function __construct() {
    $this->linkGenerator = new LinkGeneratorController;
  }

  public function packager_url($post_id) {
    $common_package_vars = array(
      'action' => 'generatepackagefiles',
      'p' => $this->linkGenerator->edition_manifest_url($post_id),
      'c' => $this->linkGenerator->edition_atom_url($post_id),
      'conc' => 3,
      'pbp' => '/',
      'tf' => PUGPIG_MANIFESTPATH . 'temp/packages/',
      'pf' => PUGPIG_MANIFESTPATH . 'packages/',
      'urlbase' => 'app/uploads/pugpig-api/packages/'
    );
    $package_url = Helper::pluginDirectory() . "common/pugpig_packager_run.php?";
    return $package_url . http_build_query($common_package_vars);
  }
}
