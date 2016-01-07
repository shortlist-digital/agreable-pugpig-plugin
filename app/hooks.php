<?php namespace AgreablePugpigPlugin\Hooks;
// Admin Mods
use AgreablePugpigPlugin\Controllers\EditionsAdminController;
// Helpers
use AgreablePugpigPlugin\Services\LoaderHelper;
// Hooks
use AgreablePugpigPlugin\Hooks\EditionHooks;
use AgreablePugpigPlugin\Hooks\PostHooks;
// Post Types
use AgreablePugpigPlugin\CustomPostTypes\EditionPostType;
use AgreablePugpigPlugin\CustomPostTypes\BundlePostType;
// Register Post Types
(new EditionPostType)->register();
(new BundlePostType)->register();
// Hooks
(new EditionHooks)->init();
(new PostHooks)->init();

// Register twig files
(new LoaderHelper)->init();
// Admin modifications
(new EditionsAdminController)->init();
