<?php
/**
 * Created by enea dhack - 26/06/2020 15:07.
 */

namespace Enea\Sequenceable;

final class Serie
{
    private string $column;

    private ?string $columnAlias = null;

    private ?string $sequenceClassName;

    private ?int $fixedLength = null;

    public function __construct(string $column, ?string $sequenceClassName = null)
    {
        $this->column = $column;
        $this->sequenceClassName = $sequenceClassName;
    }

    public static function lineal(string $column): self
    {
        return new static($column);
    }

    public function alias(?string $columnAlias): self
    {
        $this->columnAlias = $columnAlias;
        return $this;
    }

    public function length(?int $fixedLength): self
    {
        $this->fixedLength = $fixedLength;
        return $this;
    }

    public function hasAlias(): bool
    {
        return $this->columnAlias != null;
    }

    public function getColumnName(): string
    {
        return $this->column;
    }

    public function getFixedLength(): int
    {
        return $this->fixedLength ?: 0;
    }

    public function getAliasForColumn(): ?string
    {
        return $this->columnAlias;
    }

    public function getSequenceClassName(): ?string
    {
        return $this->sequenceClassName;
    }

    public function getColumnKeyName(): string
    {
        return $this->hasAlias() ? "{$this->getColumnName()}.{$this->getAliasForColumn()}" : $this->getColumnName();
    }
}
