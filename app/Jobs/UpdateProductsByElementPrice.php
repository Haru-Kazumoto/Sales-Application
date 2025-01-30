<?php

namespace App\Jobs;

use App\Models\Products;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateProductsByElementPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $elementName,
        public float $newPrice
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $column = $this->getTargetColumn();

        if(!$column) return;

        Products::query()->update([$column => $this->newPrice]);

        // Products::query()
        //     ->when($column, function($query) use ($column) {
        //         $query->chunkById(1000, function($products) use ($column) {
        //             $products->each(function($product) use ($column) {
        //                 $product->update([
        //                     $column => $this->newPrice
        //                 ]);
        //             });
        //         });
        //     });
    }

    // Get the target column name based on the element name
    private function getTargetColumn(): ?string
    {
        return match ($this->elementName) {
            'BUDGET MARKETING' => 'saving_marketing',
            'BAD DEBT' => 'bad_debt_dd',
            default => null
        };
    }
}
