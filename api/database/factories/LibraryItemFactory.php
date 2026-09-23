<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domain\Library\Enums\LibraryItemKind;
use App\Domain\Library\Enums\LibraryItemStatus;
use App\Domain\Library\Models\LibraryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LibraryItem>
 */
class LibraryItemFactory extends Factory
{
    protected $model = LibraryItem::class;

    /**
     * Defaults to a DRAFT file resource.
     *
     * Draft is the safe default on purpose: the interesting tests here are about
     * what the public can and cannot see, and a factory that published by default
     * would make "a draft is invisible" the awkward case to write rather than the
     * obvious one.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(4);

        return [
            'title' => rtrim($name, '.'),
            'description' => $this->faker->paragraph(),
            'category' => $this->faker->randomElement(array_keys((array) config('library.categories', ['policy' => 'Policies']))),
            'featured' => false,
            'kind' => LibraryItemKind::File->value,
            'stored_path' => 'library/files/'.$this->faker->uuid().'.pdf',
            'original_filename' => 'guidance.pdf',
            'mime_type' => 'application/pdf',
            'size_bytes' => $this->faker->numberBetween(20_000, 4_000_000),
            'checksum_sha256' => hash('sha256', $this->faker->uuid()),
            'external_url' => null,
            'status' => LibraryItemStatus::Draft->value,
            'published_at' => null,
            'download_count' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => LibraryItemStatus::Published->value,
            'published_at' => now()->subDays($this->faker->numberBetween(0, 60)),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (): array => [
            'status' => LibraryItemStatus::Archived->value,
            'published_at' => now()->subDays(90),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['featured' => true]);
    }

    /** A link resource: the file columns MUST be null or the CHECK constraint rejects it. */
    public function link(?string $url = null): static
    {
        return $this->state(fn (): array => [
            'kind' => LibraryItemKind::Link->value,
            'external_url' => $url ?? 'https://jigawastate.gov.ng/'.$this->faker->slug(),
            'stored_path' => null,
            'original_filename' => null,
            'mime_type' => null,
            'size_bytes' => null,
            'checksum_sha256' => null,
        ]);
    }

    public function withThumbnail(): static
    {
        return $this->state(fn (): array => [
            'thumbnail_path' => 'library/thumbnails/'.$this->faker->uuid().'.png',
            'thumbnail_mime' => 'image/png',
        ]);
    }
}
