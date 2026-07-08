<?php

declare(strict_types=1);

namespace Weave\Http\Data;

class UploadedFile
{
    public function __construct(
        public readonly string $name,
        public readonly string $type,
        public readonly string $tmpName,
        public readonly int $error,
        public readonly int $size,
    ) {}

    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK && is_uploaded_file($this->tmpName);
    }

    public function moveTo(string $destination): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        return move_uploaded_file($this->tmpName, $destination);
    }

    public function extension(): string
    {
        return strtolower(pathinfo($this->name, PATHINFO_EXTENSION));
    }

    public function errorMessage(): string
    {
        return match ($this->error) {
            UPLOAD_ERR_OK => 'Nenhum erro.',
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Arquivo excede o tamanho máximo permitido.',
            UPLOAD_ERR_PARTIAL => 'O upload foi feito parcialmente.',
            UPLOAD_ERR_NO_FILE => 'Nenhum arquivo foi enviado.',
            UPLOAD_ERR_NO_TMP_DIR => 'Diretório temporário ausente.',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever o arquivo em disco.',
            UPLOAD_ERR_EXTENSION => 'Upload interrompido por uma extensão do PHP.',
            default => 'Erro desconhecido no upload.',
        };
    }
}
