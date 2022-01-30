<?php

declare(strict_types=1);

namespace Ifrost\Common\Utilities\File;

interface FileInterface
{
    /**
     * Deletes the file if exists.
     * If the file does not exist, nothing will happen.
     */
    public function delete(): void;

    /**
     * Makes a copy of the file.
     */
    public function copy(string $newFilename): void;

    /**
     * Renames a file if it exists.
     * The new filename cannot exist.
     * The method will create the missing directories if necessary.
     */
    public function rename(string $newFilename): void;

    /**
     * Gets the path to the directory where the file is located.
     */
    public function getDirectoryPath(): string;

    /**
     * Gets the full path to the file.
     */
    public function getFilename(): string;

    /**
     * Gets the file name with extension (essay.txt, example-1.json).
     */
    public function getFullName(): string;

    /**
     * Gets the file name (essay, example-1).
     */
    public function getName(): string;

    /**
     * Gets the file extension (txt, json).
     */
    public function getExtension(): string;

    /**
     * Gets the number of lines the file contains.
     */
    public function countLines(): int;

    /**
     * Gets the selected line from the file.
     */
    public function getLine(int $lineNumber): string;
}
