<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\Directory;

use Ifrost\Common\Interfaces\Acquirable;
use PlainDataTransformer\Transform;

class GetSubDirectoriesFromDirectory implements Acquirable
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
        $files = $this->getFiles($this->directoryPath);

        if ($this->order === 'desc') {
            return array_reverse($files);
        }

        return $files;
    }

    /**
     * @return array<int, string>
     */
    private function getFiles(string $dirPath): array
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
            return array_reduce(
                $files,
                function (array $acc, string $file) {
                    if (is_dir($file)) {
                        $acc[] = rtrim($file, '/');
                    }

                    return $acc;
                },
                []
            );
        }

        return array_reduce(
            $files,
            function (array $acc, string $file) {
                if (is_dir($file)) {
                    $acc[] = rtrim($file, '/');
                    $acc = array_merge($acc, $this->getFiles($file));
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
