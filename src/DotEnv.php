<?php


namespace Clarence\DotEnv;

/**
 * Load environment variables from .env.php
 */
class DotEnv
{
    protected $path;
    protected $filename;

    /**
     * @param string $path     - specify from where to find the file
     * @param string $filename - specify the .env.php file name
     */
    public function __construct($path, $filename = '.env.php')
    {
        $this->path = $path;
        $this->filename = $filename;
    }

    /**
     * Load the environment variables
     * @param $override bool whether to override existing.
     */
    public function load($override = false)
    {
        $envVarList = require($this->path . DIRECTORY_SEPARATOR . $this->filename);
        foreach ($envVarList as $name => $value) {
            $this->setEnvironmentVariable($name, $value, $override);
        }
    }

    /**
     * Load the environment variables and override existing.
     */
    public function overload()
    {
        $this->load($override = true);
    }

    /**
     * Set an environment variable.
     *
     * This is done using:
     * - putenv,
     * - $_ENV,
     * - $_SERVER.
     *
     * The name and value are to be stripped whitespaces.
     *
     * @param string      $name
     * @param string|null $value
     * @param bool        $override    -- override original values?
     *
     * @return void
     */
    public function setEnvironmentVariable($name, $value = null, $override = false)
    {
        list($name, $value) = $this->normaliseEnvironmentVariable($name, $value);

        if (!$override) {
            $originalValue = $this->getEnvironmentVariable($name);
            if (!is_null($originalValue)) {
                $value = $originalValue;
            }
        }

        if (is_bool($value)){
            putenv("$name=".($value ? '(true)' : '(false)'));
        } elseif (is_null($value)){
            putenv("$name=(null)");
        } else {
            putenv("$name=$value");
        }

        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }

    public function getEnvironmentVariable($name)
    {
        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }

        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }

        if ($val = getenv($name)) {
            return $val;
        }

        return null;
    }

    /**
     * The name and value are to be stripped whitespaces.
     *
     * @param $name
     * @param $value
     * @return array [$name, $value]
     */
    protected function normaliseEnvironmentVariable($name, $value)
    {
        return [trim($name), is_string($value) ? trim($value) : $value];
    }
}