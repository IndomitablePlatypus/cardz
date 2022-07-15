<?php

namespace Codderz\Platypus\Infrastructure\Authorization\Abac;

use Codderz\Platypus\Contracts\Authorization\Abac\AttributeCollectionInterface;
use Codderz\Platypus\Contracts\Authorization\Abac\PermissionInterface;
use Codderz\Platypus\Contracts\Authorization\Abac\RuleInterface;
use Codderz\Platypus\Contracts\Authorization\Abac\PolicyInterface;
use Codderz\Platypus\Contracts\Authorization\AuthorizationResolution;
use Codderz\Platypus\Exceptions\AuthorizationFailedException;
use JetBrains\PhpStorm\Pure;

class Policy implements PolicyInterface
{
    /** AbacRuleInterface[] */
    protected array $rules;

    protected function __construct(
        protected PermissionInterface $permission,
        RuleInterface ...$rules
    ) {
        $this->rules = $rules;
    }

    #[Pure]
    public static function of(PermissionInterface $permission, RuleInterface ...$rules): static
    {
        return new static($permission, ...$rules);
    }

    public function forPermission(): PermissionInterface
    {
        return $this->permission;
    }

    public function applyRules(
        AttributeCollectionInterface $subject,
        AttributeCollectionInterface $object,
        AttributeCollectionInterface $config,
    ): AuthorizationResolution {
        $ruleApplicationStrategy = ResolutionStrategy::ofConfig($config);
        return $ruleApplicationStrategy->isPermissive()
            ? $this->applyPermissive($subject, $object, $config)
            : $this->applyRestrictive($subject, $object, $config);
    }

    /**
     * @throws AuthorizationFailedException
     */
    protected function applyPermissive(
        AttributeCollectionInterface $subject,
        AttributeCollectionInterface $object,
        AttributeCollectionInterface $config,
    ): AuthorizationResolution {
        $resolution = AuthorizationResolution::of();
        foreach ($this->rules as $rule) {
            $resolution = $rule->resolve($subject, $object, $config);
            if ($resolution->isPermissive()) {
                return $resolution;
            }
        }
        return $resolution;
    }

    /**
     * @throws AuthorizationFailedException
     */
    protected function applyRestrictive(
        AttributeCollectionInterface $subject,
        AttributeCollectionInterface $object,
        AttributeCollectionInterface $config,
    ): AuthorizationResolution {
        $resolution = AuthorizationResolution::of(false);
        foreach ($this->rules as $rule) {
            $resolution = $rule->resolve($subject, $object, $config);
            if ($resolution->isRestrictive()) {
                return $resolution;
            }
        }
        return $resolution;
    }
}
