<?php

declare(strict_types=1);

namespace Weave\Http\Data;

class Files extends Parameters
{
    public function __construct(array $files = [])
    {
        parent::__construct($this->normalizeFiles($files));
    }

    private function normalizeFiles(array $files): array
    {
        $normalized = [];

        foreach ($files as $key => $file) {
            if (!\is_array($file) || !isset($file['name'])) {
                continue;
            }

            if (!\is_array($file['name'])) {
                $normalized[$key] = new UploadedFile(
                    (string) $file['name'],
                    (string) ($file['type'] ?? ''),
                    (string) ($file['tmp_name'] ?? ''),
                    (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE),
                    (int) ($file['size'] ?? 0),
                );

                continue;
            }

            $normalized[$key] = $this->normalizeNested(
                $file['name'],
                $file['type'] ?? [],
                $file['tmp_name'] ?? [],
                $file['error'] ?? [],
                $file['size'] ?? [],
            );
        }

        return $normalized;
    }

    private function normalizeNested(
        array $names,
        array $types,
        array $tmpNames,
        array $errors,
        array $sizes,
    ): array {
        $result = [];

        foreach ($names as $key => $name) {
            if (\is_array($name)) {
                $result[$key] = $this->normalizeNested(
                    $name,
                    $types[$key] ?? [],
                    $tmpNames[$key] ?? [],
                    $errors[$key] ?? [],
                    $sizes[$key] ?? [],
                );

                continue;
            }

            $result[$key] = new UploadedFile(
                (string) $name,
                (string) ($types[$key] ?? ''),
                (string) ($tmpNames[$key] ?? ''),
                (int) ($errors[$key] ?? UPLOAD_ERR_NO_FILE),
                (int) ($sizes[$key] ?? 0),
            );
        }

        return $result;
    }
}
