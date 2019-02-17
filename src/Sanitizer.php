<?php

namespace Laravie\Codex;

class Sanitizer implements Contracts\Sanitizer
{
    /**
     * Sanitization rules.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Add sanitization rules.
     *
     * @param  string|array  $group
     * @param  \Laravie\Codex\Contracts\Cast  $cast
     *
     * @return $this
     */
    public function add($group, Contracts\Cast $cast)
    {
        $this->casts = \igorw\assoc_in($this->casts, (array) $group, $cast);

        return $this;
    }

    /**
     * Sanitize request.
     *
     * @param  array  $inputs
     * @param  array  $group
     *
     * @return array
     */
    public function from(array $inputs, array $group = []): array
    {
        $data = [];

        foreach ($inputs as $name => $input) {
            $data[$name] = $this->sanitizeFrom($input, $name, $group);
        }

        return $data;
    }

    /**
     * Sanitize response.
     *
     * @param  array  $inputs
     * @param  array  $group
     *
     * @return array
     */
    public function to(array $inputs, array $group = []): array
    {
        $data = [];

        foreach ($inputs as $name => $input) {
            $data[$name] = $this->sanitizeTo($input, $name, $group);
        }

        return $data;
    }

    /**
     * Sanitize request from.
     *
     * @param  mixed  $value
     * @param  string  $name
     * @param  array  $group
     *
     * @return mixed
     */
    protected function sanitizeFrom($value, string $name, array $group = [])
    {
        \array_push($group, $name);

        $caster = $this->getCaster($group);

        if (\is_array($value) && \is_null($caster)) {
            return $this->from($value, $group);
        }

        return ! \is_null($caster)
                    ? $caster->from($value)
                    : $value;
    }

    /**
     * Sanitize response to.
     *
     * @param  mixed  $value
     * @param  string  $name
     * @param  array  $group
     *
     * @return mixed
     */
    protected function sanitizeTo($value, string $name, array $group = [])
    {
        \array_push($group, $name);

        $caster = $this->getCaster($group);

        if (\is_array($value) && \is_null($caster)) {
            return $this->to($value, $group);
        }

        return ! \is_null($caster)
                    ? $caster->to($value)
                    : $value;
    }

    /**
     * Get caster.
     *
     * @param  string|array  $group
     *
     * @return \Laravie\Codex\Contracts\Cast|null
     */
    protected function getCaster($group): ?Contracts\Cast
    {
        $cast = \igorw\get_in($this->casts, (array) $group);

        if (\is_subclass_of($cast, Contracts\Cast::class)) {
            return \is_string($cast) ? new $cast() : $cast;
        }

        return null;
    }
}
