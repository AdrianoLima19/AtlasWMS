<?php

declare(strict_types=1);

namespace Weave\Http\Data;

class Server extends Parameters
{
    public function getMethod(): string
    {
        return strtoupper((string) $this->get('REQUEST_METHOD', 'GET'));
    }

    public function getProtocolVersion(): string
    {
        $protocol = (string) $this->get('SERVER_PROTOCOL', 'HTTP/1.1');

        return str_replace('HTTP/', '', $protocol) ?: '1.1';
    }

    public function isSecure(): bool
    {
        $https = $this->get('HTTPS');

        return $https !== null && $https !== '' && strtolower((string) $https) !== 'off';
    }

    public function getHost(): string
    {
        return (string) ($this->get('HTTP_HOST', $this->get('SERVER_NAME', 'localhost')));
    }

    public function getPort(): ?int
    {
        $port = $this->get('SERVER_PORT');

        return $port !== null ? (int) $port : null;
    }

    public function getRequestUri(): string
    {
        return (string) $this->get('REQUEST_URI', '/');
    }
}
