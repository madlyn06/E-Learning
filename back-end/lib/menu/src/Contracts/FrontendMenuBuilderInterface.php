<?php

namespace Newnet\Menu\Contracts;

interface FrontendMenuBuilderInterface
{
    /**
     * Get Builder Title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get Builder View Name
     *
     * @return string
     */
    public function getViewName();

    /**
     * Get Frontend Menu Url
     *
     * @return string
     */
    public function getFrontendUrl();

    /**
     * Check current menu is active
     *
     * @return boolean
     */
    public function isActive();

    /**
     * Set menu item builder arguments
     *
     * @param $args
     * @return mixed
     */
    public function setArgs($args);
}
