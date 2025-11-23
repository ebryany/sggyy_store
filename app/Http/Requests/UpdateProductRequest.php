<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product');
        if ($productId instanceof \App\Models\Product) {
            $productId = $productId->id;
        } else {
            $product = \App\Models\Product::findBySlugOrId($productId);
            $productId = $product ? $product->id : null;
        }

        return [
            // Basic Info
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['required', 'string'],
            'sku' => ['nullable', 'string', 'max:100', 'unique:products,sku,' . $productId],
            'category' => ['required', 'string', 'max:255'],
            'product_type' => ['nullable', 'string', 'max:100'],
            
            // Pricing
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'stock' => ['required', 'integer', 'min:0'],
            
            // Media
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'video_preview' => ['nullable', 'url', 'max:500'],
            'demo_link' => ['nullable', 'url', 'max:500'],
            
            // Files
            'file' => ['nullable', 'file', 'max:10240'], // 10MB max
            'file_size' => ['nullable', 'string', 'max:50'],
            'download_limit' => ['nullable', 'integer', 'min:1'],
            
            // Features & Requirements
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'system_requirements' => ['nullable', 'string', 'max:1000'],
            
            // Tags
            'tags' => ['nullable', 'array', 'max:20'],
            'tags.*' => ['string', 'max:50'],
            
            // Product Info
            'version' => ['nullable', 'string', 'max:50'],
            'license_type' => ['nullable', 'string', 'max:100'],
            'support_info' => ['nullable', 'string', 'max:500'],
            'warranty_days' => ['nullable', 'integer', 'min:0'],
            'delivery_days' => ['nullable', 'integer', 'min:0'],
            
            // SEO
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            
            // Status
            'is_active' => ['sometimes', 'boolean'],
            'is_draft' => ['sometimes', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
