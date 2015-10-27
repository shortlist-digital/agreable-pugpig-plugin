<?php

/** @var  \Herbert\Framework\Application $container */

use AgreablePugpigPlugin\CustomPostTypes\Editions;

// Register Editions post type
(new Editions)->register();
