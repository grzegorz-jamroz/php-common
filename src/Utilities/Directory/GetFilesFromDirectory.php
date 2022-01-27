<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Acquirable;
use PlainDataTransformer\Transform;

class GetFilesFromDirectory implements Acquirable
{
    /**
     * @param array $options
     * @description options:
     * extension => string
     */
    public function __construct(
        private string $directoryPath,
        private array $options = [],
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function acquire(): array
    {
        return $this->getFiles($this->directoryPath, $this->options);
    }

    private function getFiles(string $dirPath, array $options = []): array
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException(sprintf('%s is not directory.', $dirPath));
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) !== '/') {
            $dirPath .= '/';
        }

        $pattern = sprintf('%s*%s', $dirPath, $this->getExtension($options));
        $filenames = glob($pattern, GLOB_MARK) ?: [];

        return array_values(array_filter($filenames, fn (string $filename) => is_file($filename)));
    }

    private function getExtension(array $options): string
    {
        $extension = Transform::toString($options['extension'] ?? '');

        if ($extension === '') {
            return '';
        }

        return sprintf('.%s', str_replace('.', '', $extension));
    }
}
