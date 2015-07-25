<?php

require __DIR__ . '/app/autoload.php';

// Clear cache
passthru(sprintf(
    'php "Tests/app/console" cache:clear --env=test --no-warmup'
));

// Update sqlite DB
passthru(sprintf(
    'php "Tests/app/console" doctrine:schema:update --env=test --force'
));