<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerFt8uJeO\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerFt8uJeO/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerFt8uJeO.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerFt8uJeO\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerFt8uJeO\App_KernelDevDebugContainer([
    'container.build_hash' => 'Ft8uJeO',
    'container.build_id' => '18febb0f',
    'container.build_time' => 1730726552,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerFt8uJeO');