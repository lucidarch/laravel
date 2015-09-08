<?php

/*
 * This file is part of the lucid package.
 *
 * © Vinelab <dev@vinelab.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Foundation;

/**
 * Validation factory.
 *
 * @author Abed Halawi <abed.halawi@vinelab.com>
 */
class Validation
{
    /**
     * Get a new validation instance for the given attributes and rules.
     *
     * @param  array  $data
     * @param  array  $rules
     * @param  array  $messages
     * @param  array  $customAttributes
     *
     * @return \Illuminate\Validation\Validator
     */
    public function make(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        return $this->getValidationFactory()->make($data, $rules, $messages, $customAttributes);
    }

    /**
     * Get a validation factory instance.
     *
     * @return \Illuminate\Validation\Factory
     */
    public function getValidationFactory()
    {
        return app('Illuminate\Contracts\Validation\Factory');
    }
}
