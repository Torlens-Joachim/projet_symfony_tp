<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerWrhBbAE\App_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerWrhBbAE/App_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerWrhBbAE.legacy');

    return;
}

if (!\class_exists(App_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerWrhBbAE\App_KernelDevDebugContainer::class, App_KernelDevDebugContainer::class, false);
}

return new \ContainerWrhBbAE\App_KernelDevDebugContainer([
    'container.build_hash' => 'WrhBbAE',
    'container.build_id' => 'c7f6f11c',
    'container.build_time' => 1730728568,
    'container.runtime_mode' => \in_array(\PHP_SAPI, ['cli', 'phpdbg', 'embed'], true) ? 'web=0' : 'web=1',
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerWrhBbAE');