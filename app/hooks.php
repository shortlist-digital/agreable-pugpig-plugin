<?php namespace AgreablePugpigPlugin\Hooks;
// Admin Mods
use AgreablePugpigPlugin\Controllers\EditionsAdminController;
// Helpers
use AgreablePugpigPlugin\Services\LoaderHelper;
// Hooks
use AgreablePugpigPlugin\Hooks\EditionHooks;
use AgreablePugpigPlugin\Hooks\PostHooks;
use AgreablePugpigPlugin\Hooks\BundleHooks;
// Post Types
use AgreablePugpigPlugin\CustomPostTypes\EditionPostType;
use AgreablePugpigPlugin\CustomPostTypes\BundlePostType;

// Define Constants
$upload_dir = wp_upload_dir();
$base_dir = $upload_dir['basedir']."/pugpig-api/";
$base_url = $upload_dir['baseurl']."/pugpig-api/";
$package_dir = $base_dir."packages";
$bundle_dir = $base_dir."bundles";
$bundle_url = $base_url."bundles";
define(PUGPIG_PACKAGE_DIR, $package_dir);
define(PUGPIG_BUNDLE_DIR, $bundle_dir);
define(PUGPIG_BUNDLE_URL, $bundle_url);

// Register Post Types
(new EditionPostType)->register();
(new BundlePostType)->register();
// Hooks
(new EditionHooks)->init();
(new PostHooks)->init();
(new BundleHooks)->init();

// Register twig files
(new LoaderHelper)->init();
// Admin modifications
(new EditionsAdminController)->init();
