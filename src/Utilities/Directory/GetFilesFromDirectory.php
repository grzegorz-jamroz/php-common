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
     * recursive => bool
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

        $pattern = sprintf('%s*%s', $dirPath, $this->getExtension());
        $files = glob($pattern, GLOB_MARK) ?: [];

        if ($this->isRecursive() === false) {
            return array_values(array_filter($files, fn (string $file) => is_file($file)));
        }

        return array_reduce(
            $files,
            function (array $acc, string $file) use ($options) {
                if (is_dir($file)) {
                    $acc = array_merge($acc, $this->getFiles($file, $options));
                }

                if (is_file($file)) {
                    $acc[] = $file;
                }

                return $acc;
            },
            []
        );
    }

    private function getExtension(): string
    {
        $extension = Transform::toString($this->options['extension'] ?? '');

        if ($extension === '') {
            return '';
        }

        return sprintf('.%s', str_replace('.', '', $extension));
    }

    private function isRecursive(): bool
    {
        return Transform::toBool($this->options['recursive'] ?? false);
    }
}
