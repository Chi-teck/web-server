<?php

namespace Symfony\Bundle\WebServerBundle;

/**
 * Class WebServerConfig
 *
 * @package Symfony\Bundle\WebServerBundle
 */
class WebServerConfig
{
    private $hostname;
    private $port;
    private $documentRoot;
    private $router;

    public function __construct($documentRoot, $env, $address = null, $router = null)
    {
        if (!is_dir($documentRoot)) {
            throw new \InvalidArgumentException(sprintf('The document root directory "%s" does not exist.', $documentRoot));
        }

        $this->documentRoot = $documentRoot;

        if (null !== $router) {
            $absoluteRouterPath = realpath($router);

            if (false === $absoluteRouterPath) {
                throw new \InvalidArgumentException(sprintf('Router script "%s" does not exist.', $router));
            }

            $this->router = $absoluteRouterPath;
        } else {
            $this->router = __DIR__.'/router.php';
        }

        if (null === $address) {
            $this->hostname = '127.0.0.1';
            $this->port = $this->findBestPort();
        } elseif (false !== $pos = strrpos($address, ':')) {
            $this->hostname = substr($address, 0, $pos);
            $this->port = substr($address, $pos + 1);
        } elseif (ctype_digit($address)) {
            $this->hostname = '127.0.0.1';
            $this->port = $address;
        } else {
            $this->hostname = $address;
            $this->port = $this->findBestPort();
        }

        if (!ctype_digit($this->port)) {
            throw new \InvalidArgumentException(sprintf('Port "%s" is not valid.', $this->port));
        }
    }

    public function getDocumentRoot()
    {
        return $this->documentRoot;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getHostname()
    {
        return $this->hostname;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getAddress()
    {
        return $this->hostname.':'.$this->port;
    }

    private function findBestPort()
    {
        $port = 8000;
        while (false !== $fp = @fsockopen($this->hostname, $port, $errno, $errstr, 1)) {
            fclose($fp);
            if ($port++ >= 8100) {
                throw new \RuntimeException('Unable to find a port available to run the web server.');
            }
        }

        return $port;
    }
}
