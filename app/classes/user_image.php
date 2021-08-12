<?php

class UserImage
{
    public string $base_url = 'https://randomuser.me/api/portraits/thumb/';
    public string $uri;

    function __construct(
        public string $url
    ) {
        $this->uri = $this->splitPath();
    }

    protected function splitPath(): string
    {
        $base_url_len = strlen($this->base_url);

        if (substr($this->url, 0, $base_url_len) == $this->base_url) {
            return substr($this->url, $base_url_len);
        }

        return $this->url;
    }

    function getUrl(): string
    {
        return $this->base_url . $this->uri;
    }

    function getUri(): string
    {
        return $this->uri;
    }
}
