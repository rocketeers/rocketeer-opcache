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
        $socket = $this->config->getPluginOption('rocketeer-opcache', 'socket');

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