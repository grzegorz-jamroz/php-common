<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Acquirable;
use PlainDataTransformer\Transform;

class GetFilesAndSubDirectoriesFromDirectory implements Acquirable
{
    private string $order;
    private bool $isRecursive;

    /**
     * @param array<string, mixed> $options
     * @description options:
     * recursive => bool | default: false
     * order => string (asc or desc) | default: asc
     */
    public function __construct(
        private string $directoryPath,
        private array $options = [],
    ) {
        $this->setIsRecursive();
        $this->setOrder();
    }

    /**
     * @return array<int, string>
     */
    public function acquire(): array
    {
        $files = $this->getFiles($this->directoryPath, $this->options);

        if ($this->order === 'desc') {
            return array_reverse($files);
        }

        return $files;
    }

    private function getFiles(string $dirPath, array $options = []): array
    {
        if (!is_dir($dirPath)) {
            throw new \InvalidArgumentException(sprintf('%s is not directory.', $dirPath));
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) !== '/') {
            $dirPath .= '/';
        }

        $pattern = sprintf('%s*', $dirPath);
        $files = glob($pattern, GLOB_MARK) ?: [];

        if ($this->isRecursive === false) {
            return array_values(array_filter($files, fn (string $file) => $file));
        }

        return array_reduce(
            $files,
            function (array $acc, string $file) use ($options) {
                $acc[] = $file;

                if (is_dir($file)) {
                    $acc = array_merge($acc, $this->getFiles($file, $options));
                }

                return $acc;
            },
            []
        );
    }

    private function setIsRecursive(): void
    {
        $this->isRecursive = Transform::toBool($this->options['recursive'] ?? false);
    }

    private function setOrder(): void
    {
        $order = strtolower(Transform::toString($this->options['order'] ?? 'asc'));
        $this->order = in_array($order, ['asc', 'desc']) ? $order : 'asc';
    }
}
