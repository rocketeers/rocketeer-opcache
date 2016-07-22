<?php
namespace Rocketeer\Plugins\Opcache;

use Rocketeer\Tasks\AbstractTask;

class ClearOpcache extends AbstractTask
{
    public function execute()
    {
        // Download cachetool if needed
        $path = $this->paths->getFolder('shared/cachetool.phar');
        $this->downloadCachetool($path);

        // Get correct PHP version
        $version = $this->php()->run('version');
        $version = substr($version, 0, 3);
        $socket = '/var/run/php/php'.$version.'-fpm.sock';

        // Reset opcache cache
        $binary = $this->binary($path);
        $binary->setParent($this->php());
        if (!$binary->runSilently('opcache:reset')) {
            $binary->run('opcache:reset', null, ['--fcgi' => $socket]);
        }

        $this->explainer->success('Opcache successfully cleared');
    }

    /**
     * @param string $path
     */
    protected function downloadCachetool($path)
    {
        if ($this->fileExists($path)) {
            return;
        }

        $this->runInFolder('shared', [
            'curl -sO http://gordalina.github.io/cachetool/downloads/cachetool.phar',
            'chmod +x cachetool.phar',
        ]);
    }
}