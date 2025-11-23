<?php

namespace App\Http\Controllers;

    use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        // Generate slug for products that don't have one
        Product::whereNull('slug')
            ->orWhere('slug', '')
            ->chunk(100, function ($products) {
                foreach ($products as $product) {
                    $product->slug = Product::generateSlug($product->title, $product->id);
                    $product->save();
                }
            });
        
        // Generate slug for services that don't have one
        \App\Models\Service::whereNull('slug')
            ->orWhere('slug', '')
            ->chunk(100, function ($services) {
                foreach ($services as $service) {
                    $service->slug = \App\Models\Service::generateSlug($service->title, $service->id);
                    $service->save();
                }
            });
        
        return view('home');
    }
}
