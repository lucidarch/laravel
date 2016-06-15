<?php

namespace App\Foundation;

/**
 * An abstract representation of a model's value.
 * Simply extend this class and override the setValue
 * method to perform validations etc.
 *
 * You may use the validation($input, $rules) method to
 * validate given input with given rules.
 */
class Value
{
    /**
     * The value of this value (valueception).
     *
     * @var mixed
     */
    protected $value;

    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * Get a Validation instance with the given rules and input.
     *
     * @param array $input
     * @param array $rules
     *
     * @return \Illuminate\Validation\Validation
     */
    public function validation(array $input, array $rules, array $messages = [], array $customAttributes = [])
    {
        return app('App\Foundation\Validation')->make($input, $rules, $messages, $customAttributes);
    }

    /**
     * Set the value of the value of the value.
     *
     * @param mixed $value
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get the value of the value of the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Return the string representation of the value of this instance.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s', $this->getValue());
    }

    /**
     * Make values immutable.
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
    }
}
