<?php

namespace Bavix\CupKit\Storage;

use Bavix\CupKit\CupKitServiceProvider;
use Illuminate\Foundation\Application;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use Bavix\CupKit\Client;

class CupAdapter extends AbstractAdapter
{

    /**
     * @var Client
     */
    protected $cup;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $config;

    /**
     * CupAdapter constructor.
     * @param Application $app
     * @param array $config
     */
    public function __construct(Application $app, array $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * @return Client
     */
    protected function cup(): Client
    {
        if (!$this->cup) {
            $this->cup = app(CupKitServiceProvider::SINGLETON_CLIENT);
        }
        return $this->cup;
    }

    /**
     * @param string $path
     * @return array
     */
    protected function parse(string $path): array
    {
        return \explode('.', $path);
    }

    /**
     * {uuid}
     * {uuid}.webp
     * {view}.{uuid}
     * {view}.{uuid}.webp
     *
     * @param $path
     * @return string
     */
    public function getUrl($path): string
    {
        return $path;
    }

    /**
     * @param string $path
     * @param string $contents
     * @param Config $config
     * @return array|false
     */
    public function write($path, $contents, Config $config)
    {
        return $this->writeStream($path, \stream_context_create($contents), $config);
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param Config $config
     * @return array|false
     */
    public function writeStream($path, $resource, Config $config)
    {
        [$bucket, $uuid] = $this->parse($path);
        return $this->cup()->createImage($bucket, $resource, $uuid);
    }

    /**
     * @param string $path
     * @param string $contents
     * @param Config $config
     * @return array|bool|false
     */
    public function update($path, $contents, Config $config)
    {
        return false;
    }

    /**
     * @param string $path
     * @param resource $resource
     * @param Config $config
     * @return array|bool|false
     */
    public function updateStream($path, $resource, Config $config)
    {
        return false;
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function rename($path, $newpath): bool
    {
        return false;
    }

    /**
     * @param string $path
     * @param string $newpath
     * @return bool
     */
    public function copy($path, $newpath): bool
    {
        return false;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function delete($path): bool
    {
        return $this->cup()->dropImage(...$this->parse($path));
    }

    /**
     * @param string $dirname
     * @return bool
     */
    public function deleteDir($dirname): bool
    {
        return $this->cup()->dropBucket($dirname);
    }

    /**
     * @param string $dirname
     * @param Config $config
     * @return array|false
     */
    public function createDir($dirname, Config $config)
    {
        return $this->cup()->createBucket($dirname);
    }

    /**
     * @param string $path
     * @param string $visibility
     * @return array|bool|false
     */
    public function setVisibility($path, $visibility)
    {
        return false;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function has($path): bool
    {
        try {
            $this->cup()->getImage(...$this->parse($path));
            return true;
        } catch (\Throwable $throwable) {
            return false;
        }
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function read($path)
    {
        return false;
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function readStream($path)
    {
        return false;
    }

    /**
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function listContents($directory = '', $recursive = false)
    {
        return [];
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getMetadata($path)
    {
        return false;
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getSize($path)
    {
        return false;
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getMimetype($path)
    {
        return false;
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getTimestamp($path)
    {
        return false;
    }

    /**
     * @param string $path
     * @return array|bool|false
     */
    public function getVisibility($path)
    {
        return false;
    }
    
}
