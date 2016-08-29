<?php
namespace AFD;

/**
 * Wrapper around an array of key/value-pairs.
 *
 * @author Armin Reichert
 */
class PropertyBag
{

    /**
     *
     * @var array(string=>mixed)
     */
    private $properties;

    public function __construct(array $properties = [])
    {
        $this->properties = $properties;
    }

    public function has($key)
    {
        return array_key_exists($key, $this->properties);
    }

    public function get($key, $defaultValue = null)
    {
        if ($this->has($key)) {
            return $this->properties[$key];
        }
        if (! isset($defaultValue)) {
            throw new \Exception("No parameter with key " . $key);
        } else {
            return $defaultValue;
        }
    }

    public function set($key, $value)
    {
        $this->properties[$key] = $value;
    }

    public function keys()
    {
        return array_keys($this->properties);
    }

    public function all()
    {
        return $this->properties;
    }
}