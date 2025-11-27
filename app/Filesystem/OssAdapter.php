<?php

namespace App\Filesystem;

use OSS\OssClient;
use OSS\Core\OssException;
use League\Flysystem\Config;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToRetrieveMetadata;

/**
 * Alibaba Cloud OSS Adapter untuk Laravel Filesystem
 * Menggunakan OSS SDK native (tanpa AWS SDK)
 */
class OssAdapter implements FilesystemAdapter
{
    private OssClient $client;
    private string $bucket;
    private string $endpoint;
    private string $url;

    public function __construct(array $config)
    {
        $accessKeyId = $config['key'] ?? $config['access_key_id'] ?? '';
        $accessKeySecret = $config['secret'] ?? $config['access_key_secret'] ?? '';
        $this->endpoint = $config['endpoint'] ?? '';
        $this->bucket = $config['bucket'] ?? '';
        $this->url = $config['url'] ?? '';

        // Validate required config
        if (empty($accessKeyId) || empty($accessKeySecret) || empty($this->endpoint) || empty($this->bucket)) {
            throw new \RuntimeException("OSS configuration incomplete. Required: key, secret, endpoint, bucket");
        }

        try {
            // Create OSS client
            // Parameters: accessKeyId, accessKeySecret, endpoint, isCName, securityToken, requestProxy
            $this->client = new OssClient($accessKeyId, $accessKeySecret, $this->endpoint, false);
            
            // Enable SSL (HTTPS) - OSS SDK defaults to HTTP, we need HTTPS
            $this->client->setUseSSL(true);
            
            // Set timeouts to handle network issues better
            $this->client->setTimeout(30); // Request timeout: 30 seconds
            $this->client->setConnectTimeout(10); // Connection timeout: 10 seconds
            
            // Note: If SSL connection fails, it might be due to:
            // 1. Windows Firewall/Antivirus blocking SSL connections
            // 2. Network proxy requirements
            // 3. SSL certificate verification issues
            // 4. Network connectivity problems
            // For production, ensure proper SSL configuration
        } catch (OssException $e) {
            throw new \RuntimeException("Failed to initialize OSS client: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to initialize OSS client: " . $e->getMessage());
        }
    }

    public function fileExists(string $path): bool
    {
        try {
            return $this->client->doesObjectExist($this->bucket, $path);
        } catch (OssException $e) {
            return false;
        }
    }

    public function directoryExists(string $path): bool
    {
        $path = rtrim($path, '/') . '/';
        try {
            $result = $this->client->listObjects($this->bucket, [
                'prefix' => $path,
                'max-keys' => 1,
            ]);
            return !empty($result->getObjectList());
        } catch (OssException $e) {
            return false;
        }
    }

    public function write(string $path, string $contents, Config $config): void
    {
        try {
            // Get visibility from config (default to public for OSS)
            $visibility = $config->get('visibility', 'public');
            $options = [];
            
            // Set ACL to public-read if visibility is public
            // OSS SDK expects ACL in headers array
            if ($visibility === 'public') {
                $options[OssClient::OSS_HEADERS] = [
                    'x-oss-object-acl' => OssClient::OSS_ACL_TYPE_PUBLIC_READ,
                ];
            }
            
            // Upload file
            $this->client->putObject($this->bucket, $path, $contents, $options);
            
            // Double-check: Set ACL after upload to ensure it's applied
            // This is a fallback in case the header method doesn't work
            if ($visibility === 'public') {
                try {
                    $this->client->putObjectAcl($this->bucket, $path, OssClient::OSS_ACL_TYPE_PUBLIC_READ);
                } catch (OssException $aclException) {
                    // Log but don't fail the upload
                    \Illuminate\Support\Facades\Log::warning('Failed to set ACL after upload', [
                        'path' => $path,
                        'error' => $aclException->getMessage(),
                    ]);
                }
            }
        } catch (OssException $e) {
            // Provide more detailed error information
            $errorMessage = $e->getMessage();
            if ($e->getErrorCode()) {
                $errorMessage .= " (OSS Code: {$e->getErrorCode()})";
            }
            if ($e->getRequestId()) {
                $errorMessage .= " (Request ID: {$e->getRequestId()})";
            }
            if ($e->getHTTPStatus()) {
                $errorMessage .= " (HTTP Status: {$e->getHTTPStatus()})";
            }
            // Log the error for debugging
            \Illuminate\Support\Facades\Log::error('OSS write failed', [
                'path' => $path,
                'bucket' => $this->bucket,
                'endpoint' => $this->endpoint,
                'error' => $errorMessage,
                'oss_exception' => get_class($e),
            ]);
            throw UnableToWriteFile::atLocation($path, $errorMessage);
        } catch (\Exception $e) {
            // Catch any other exceptions (network, etc.)
            $errorMessage = "OSS write error: " . $e->getMessage();
            \Illuminate\Support\Facades\Log::error('OSS write exception', [
                'path' => $path,
                'bucket' => $this->bucket,
                'endpoint' => $this->endpoint,
                'error' => $errorMessage,
                'exception' => get_class($e),
            ]);
            throw UnableToWriteFile::atLocation($path, $errorMessage);
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        try {
            $streamContents = stream_get_contents($contents);
            
            // Get visibility from config (default to public for OSS)
            $visibility = $config->get('visibility', 'public');
            $options = [];
            
            // Set ACL to public-read if visibility is public
            // OSS SDK expects ACL in headers array
            if ($visibility === 'public') {
                $options[OssClient::OSS_HEADERS] = [
                    'x-oss-object-acl' => OssClient::OSS_ACL_TYPE_PUBLIC_READ,
                ];
            }
            
            // Upload file
            $this->client->putObject($this->bucket, $path, $streamContents, $options);
            
            // Double-check: Set ACL after upload to ensure it's applied
            // This is a fallback in case the header method doesn't work
            if ($visibility === 'public') {
                try {
                    $this->client->putObjectAcl($this->bucket, $path, OssClient::OSS_ACL_TYPE_PUBLIC_READ);
                } catch (OssException $aclException) {
                    // Log but don't fail the upload
                    \Illuminate\Support\Facades\Log::warning('Failed to set ACL after upload', [
                        'path' => $path,
                        'error' => $aclException->getMessage(),
                    ]);
                }
            }
        } catch (OssException $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage());
        }
    }

    public function read(string $path): string
    {
        try {
            return $this->client->getObject($this->bucket, $path);
        } catch (OssException $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage());
        }
    }

    public function readStream(string $path)
    {
        try {
            $content = $this->client->getObject($this->bucket, $path);
            $stream = fopen('php://temp', 'r+');
            fwrite($stream, $content);
            rewind($stream);
            return $stream;
        } catch (OssException $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage());
        }
    }

    public function delete(string $path): void
    {
        try {
            $this->client->deleteObject($this->bucket, $path);
        } catch (OssException $e) {
            throw UnableToDeleteFile::atLocation($path, $e->getMessage());
        }
    }

    public function deleteDirectory(string $path): void
    {
        $path = rtrim($path, '/') . '/';
        try {
            $result = $this->client->listObjects($this->bucket, ['prefix' => $path]);
            $objects = [];
            foreach ($result->getObjectList() as $object) {
                $objects[] = $object->getKey();
            }
            if (!empty($objects)) {
                $this->client->deleteObjects($this->bucket, $objects);
            }
        } catch (OssException $e) {
            // Ignore if directory doesn't exist
        }
    }

    public function createDirectory(string $path, Config $config): void
    {
        // OSS doesn't have directories, but we can create a placeholder
        $path = rtrim($path, '/') . '/';
        try {
            $this->client->putObject($this->bucket, $path, '');
        } catch (OssException $e) {
            // Ignore if already exists
        }
    }

    public function setVisibility(string $path, string $visibility): void
    {
        try {
            $acl = $visibility === 'public' ? OssClient::OSS_ACL_TYPE_PUBLIC_READ : OssClient::OSS_ACL_TYPE_PRIVATE;
            $this->client->putObjectAcl($this->bucket, $path, $acl);
        } catch (OssException $e) {
            // Ignore visibility errors
        }
    }

    public function visibility(string $path): FileAttributes
    {
        try {
            $acl = $this->client->getObjectAcl($this->bucket, $path);
            $visibility = $acl === OssClient::OSS_ACL_TYPE_PUBLIC_READ ? 'public' : 'private';
            return new FileAttributes($path, null, $visibility);
        } catch (OssException $e) {
            return new FileAttributes($path, null, 'private');
        }
    }

    public function mimeType(string $path): FileAttributes
    {
        try {
            $meta = $this->client->getObjectMeta($this->bucket, $path);
            $mimeType = $meta['content-type'] ?? 'application/octet-stream';
            return new FileAttributes($path, null, null, null, $mimeType);
        } catch (OssException $e) {
            throw UnableToRetrieveMetadata::mimeType($path, $e->getMessage());
        }
    }

    public function lastModified(string $path): FileAttributes
    {
        try {
            $meta = $this->client->getObjectMeta($this->bucket, $path);
            $timestamp = isset($meta['last-modified']) 
                ? strtotime($meta['last-modified']) 
                : time();
            return new FileAttributes($path, null, null, $timestamp);
        } catch (OssException $e) {
            throw UnableToRetrieveMetadata::lastModified($path, $e->getMessage());
        }
    }

    public function fileSize(string $path): FileAttributes
    {
        try {
            $meta = $this->client->getObjectMeta($this->bucket, $path);
            $size = isset($meta['content-length']) ? (int)$meta['content-length'] : 0;
            return new FileAttributes($path, $size);
        } catch (OssException $e) {
            throw UnableToRetrieveMetadata::fileSize($path, $e->getMessage());
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        $path = rtrim($path, '/') . '/';
        try {
            $result = $this->client->listObjects($this->bucket, [
                'prefix' => $path,
                'delimiter' => $deep ? '' : '/',
            ]);

            foreach ($result->getObjectList() as $object) {
                $objectPath = $object->getKey();
                if ($objectPath === $path) {
                    continue; // Skip directory placeholder
                }

                yield new FileAttributes(
                    $objectPath,
                    $object->getSize(),
                    null,
                    strtotime($object->getLastModified()),
                    null
                );
            }

            // List common prefixes (directories)
            foreach ($result->getCommonPrefixes() as $prefix) {
                yield new DirectoryAttributes($prefix);
            }
        } catch (OssException $e) {
            // Return empty if error
        }
    }

    public function move(string $source, string $destination, Config $config): void
    {
        try {
            $this->client->copyObject($this->bucket, $source, $this->bucket, $destination);
            $this->client->deleteObject($this->bucket, $source);
        } catch (OssException $e) {
            throw new \RuntimeException("Failed to move file: " . $e->getMessage());
        }
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        try {
            $this->client->copyObject($this->bucket, $source, $this->bucket, $destination);
        } catch (OssException $e) {
            throw new \RuntimeException("Failed to copy file: " . $e->getMessage());
        }
    }

    /**
     * Get URL for file (used by Laravel FilesystemAdapter)
     * 
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return $this->publicUrl($path, new Config());
    }

    public function publicUrl(string $path, Config $config): string
    {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // If custom URL is configured, use it
        if ($this->url) {
            return rtrim($this->url, '/') . '/' . $path;
        }
        
        // Generate OSS public URL
        // Format: https://bucket-name.endpoint/path
        // Handle endpoint format (with or without protocol)
        $endpoint = $this->endpoint;
        if (str_starts_with($endpoint, 'http://') || str_starts_with($endpoint, 'https://')) {
            // Endpoint already has protocol, extract domain
            $endpoint = parse_url($endpoint, PHP_URL_HOST) ?: str_replace(['http://', 'https://'], '', $endpoint);
        }
        
        return "https://{$this->bucket}.{$endpoint}/{$path}";
    }

    public function temporaryUrl(string $path, \DateTimeInterface $expiresAt, Config $config): string
    {
        try {
            $timeout = $expiresAt->getTimestamp() - time();
            return $this->client->signUrl($this->bucket, $path, $timeout);
        } catch (OssException $e) {
            return $this->publicUrl($path, $config);
        }
    }
}
