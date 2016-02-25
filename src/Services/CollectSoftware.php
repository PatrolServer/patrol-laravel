<?php namespace PatrolServer\Patrol\Services;

class CollectSoftware {

    /**
     * Format:
     *
     * n: name
     * v: version
     * l: location
     * p: parent
     */

    private function composer() {
        $path = app_path() . '\\..\\composer.lock';

        if (!file_exists($path))
            return;

        // Parse composer.lock
        $content = @file_get_contents($path);
        $list = @json_decode($content);

        if (!$list)
            return;

        $list = object_get($list, 'packages', []);

        // Determe the parent of the composer modules, most likely this will
        // resolve to laravel/laravel.
        $parent = '';
        $parent_path = realpath(app_path() . '\\..\\composer.json');
        if (file_exists($parent_path)) {
            $parent_object = @json_decode(@file_get_contents($parent_path));

            if ($parent_object)
                $parent = object_get($parent_object, 'name', '');
        }

        // Store base package, which is laravel/laravel.
        $packages = [[
            'n' => $parent,
            'l' => $parent_path,
            'v' => ''
        ]];

        // Add each composer module to the packages list,
        // but respect the parent relation.
        foreach ($list as $package) {
            $packages[] = [
                'n' => $package->name,
                'v' => $package->version,
                'p' => $parent,
                'l' => $parent_path
            ];
        }

        return $packages;
    }

    public function softwareList() {
        $composer_modules = $this->composer();
        return array_merge([], $composer_modules);
    }

}
