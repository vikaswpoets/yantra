<?php

namespace System;

abstract class Plugin
{

    private string $name;
    private string $description;
    private string $ver;
    private string $author;

    public function __construct($config=array()){
        $this->name = isset($config['name'])??'Sample Plugin';
        $this->description = isset($config['name'])??'';
        $this->ver = isset($config['name'])??'1.0.0';
        $this->author = isset($config['name'])??'Unknown';
    }

    abstract  public function activate();

    abstract public function deactivate();

    //** Default Functions for a plugin */
    public function getPluginName(): string
    {
        return $this->plugin_name;
    }

    public function getPluginDescription(): string
    {
        return $this->plugin_description;
    }

    public function getPluginVersion(): string
    {
        return $this->plugin_version;
    }

    public function getPluginAuthor(): string
    {
        return $this->plugin_author;
    }

    abstract public function path();
}