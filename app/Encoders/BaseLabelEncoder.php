<?php abstract class BaseLabelEncoder
{
    protected array $commands = [];
    protected int $dpi = 203;
    protected int $labelWidth = 0;
    protected int $labelHeight = 0;
    protected string $speed = 'normal';
    protected string $density = 'medium';
    protected int $copies = 1;
    public function setLabelSize(float $widthInInches, float $heightInInches, int $dpi = 203): self
    {
        $this->dpi = $dpi;
        $this->labelWidth = $this->inchToDots($widthInInches);
        $this->labelHeight = $this->inchToDots($heightInInches);
        return $this;
    }
    public function setSpeed(string $speed): self
    {
        $allowed = ['slow', 'normal', 'fast'];
        if (!in_array($speed, $allowed)) {
            throw new \InvalidArgumentException("Invalid speed: $speed");
        }
        $this->speed = $speed;
        return $this;
    }
    public function setDensity(string $density): self
    {
        $allowed = ['light', 'medium_light', 'medium', 'dark'];
        if (!in_array($density, $allowed)) {
            throw new \InvalidArgumentException("Invalid density: $density");
        }
        $this->density = $density;
        return $this;
    }
    public function setCopies(int $count): self
    {
        $this->copies = max(1, $count);
        return $this;
    }
    protected function inchToDots(float $inches): int
    {
        return (int) round($inches * $this->dpi);
    }
    protected function centerX(int $elementWidthDots): int
    {
        return max(0, (int)(($this->labelWidth - $elementWidthDots) / 2));
    }
    protected function rightX(int $elementWidthDots): int
    {
        return max(0, $this->labelWidth - $elementWidthDots);
    }
    public function getBuffer(): string
    {
        return implode("\n", $this->commands) . "\n";
    }
    abstract public function initialize(): self;
    abstract public function finalize(): self;
    abstract protected function mapSpeed(string $speed): string|int;
    abstract protected function mapDensity(string $density): string|int;
}
