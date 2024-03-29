<?php

declare(strict_types=1);

namespace Leevel\Auth;

/**
 * 认证驱动抽象类.
 */
abstract class Auth implements IAuth
{
    /**
     * 配置.
     */
    protected array $config = [
        'token' => null,
        'expire' => null,
    ];

    /**
     * 构造函数.
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * {@inheritDoc}
     */
    public function isLogin(): bool
    {
        return (bool) $this->getLogin();
    }

    /**
     * {@inheritDoc}
     */
    public function getLogin(): array
    {
        return $this->tokenData();
    }

    /**
     * {@inheritDoc}
     */
    public function login(array $data, ?int $loginTime = null): string
    {
        return $this->tokenPersistence(
            $data,
            null !== $loginTime ? $loginTime : $this->config['expire'],
        );
    }

    /**
     * {@inheritDoc}
     */
    public function logout(): void
    {
        $this->tokenDelete();
    }

    /**
     * {@inheritDoc}
     */
    public function setTokenName(string $tokenName): void
    {
        $this->config['token'] = $tokenName;
    }

    /**
     * {@inheritDoc}
     *
     * @throws \RuntimeException
     */
    public function getTokenName(): string
    {
        if (!$this->config['token']) {
            throw new \RuntimeException('Token name was not set.');
        }

        return $this->config['token'];
    }

    /**
     * 认证信息持久化.
     */
    protected function tokenPersistence(array $data, ?int $loginTime = null): string
    {
        return $this->setPersistence($this->getTokenName(), json_encode($data), $loginTime);
    }

    /**
     * 认证信息获取.
     *
     * @throws \JsonException
     */
    protected function tokenData(): array
    {
        $data = $this->getPersistence($this->getTokenName());

        // @phpstan-ignore-next-line
        return $data ? (array) json_decode($data, true, 512, JSON_THROW_ON_ERROR) : [];
    }

    /**
     * 认证信息删除.
     */
    protected function tokenDelete(): void
    {
        $this->deletePersistence($this->getTokenName());
    }

    /**
     * 数据持久化.
     */
    abstract protected function setPersistence(string $key, string $value, ?int $expire = null): string;

    /**
     * 获取持久化数据.
     */
    abstract protected function getPersistence(string $key): mixed;

    /**
     * 删除持久化数据.
     */
    abstract protected function deletePersistence(string $key): void;
}
