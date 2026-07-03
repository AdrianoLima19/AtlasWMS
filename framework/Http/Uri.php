<?php

namespace Weave\Http;

class Uri
{

  /**
   * @param string $uri
   */
  public function __construct(string $uri) {}

  /**
   * @param array $server
   *
   * @return Uri
   */
  public static function fromGlobals(array $server = []): self
  {
    return new self('');
  }
}
