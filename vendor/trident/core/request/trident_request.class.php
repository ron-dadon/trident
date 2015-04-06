<?php


class Trident_Request
{

    /**
     * @var Trident_Request_Post
     */
    public $post;
    /**
     * @var Trident_Request_Get
     */
    public $get;
    /**
     * @var Trident_Request_Cookie
     */
    public $cookie;
    /**
     * @var Trident_Request_Files
     */
    public $files;

    function __construct()
    {
        $this->post = new Trident_Request_Post();
        $this->get = new Trident_Request_Get();
        $this->cookie = new Trident_Request_Cookie();
    }
}