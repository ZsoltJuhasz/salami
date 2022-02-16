<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Product;
use App\Models\Material;
use App\Http\Resources\Products as ProductsResources;
use App\Http\Resources\Material as MaterialResources;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductsController extends BaseController
{
    public function index(){
        $product = DB::table('products')
            ->join('components', 'products.cid', '=', 'componentss.id')
            ->select('name', 'price_kg', 'cid', 'production_time', 'created_at','updated_at')
            ->get();
        return $this->sendResponse( $product, "Termékek megjelenítve" );
    }

    public function show($id){
        $product = DB::table('products')
            ->join('components', 'products.cid', '=', 'components.id')
            ->select('products.id', 'name', 'price_kg', 'cid', 'production_time', 'created_at','updated_at')
            ->where('products.id', '=', $id)
            ->get();
        return $this->sendResponse( $product, "Termék betöltve");
    }

    public function create(Request $request){
        $input = $request->all();
        $validator = Validator::make($input, [
            "name" => "required",
            "price_kg" => "required",
            "component" => "required",
            "production_time" => "required"
        ]);
        if($validator->fails()){
            return $this->sendError($validator->errors());
        }
        $product = Product::create($request->all());
        return $this->sendResponse($product, 'Termék sikeresen létrehozva.');
    }
    
    public function update(Request $request, $id ) {
        try {
            $product = Product::find($id);
            $product->update($request->all());
            return $this->sendResponse($product, 'Termék sikeresen frissítve/módosítva.');
        } catch (\Throwable $er) {
            return $this->sendError("Hiba a módosítás során", $er, 403);
        }
    }

    public function destroy($id){
        $product = Product::find($id);
        if( is_null($product)){
            return $this->sendError("Nem találhattó ilyen termék");
        }
        Product::destroy($id);
        return $this->sendResponse( [], "A megadott termék törölve");
    }
    public function search($name){
        $product = DB::table('products')
            ->join('components', 'products.cid', '=', 'components.id')
            ->select('name', 'price_kg', 'cid', 'production_time', 'created_at','updated_at')
            ->where('name', 'like', '%'.$name.'%')
            ->get();
        if(count($product)==0){
            return $this->sendError("A megadott keresésre nincs találat");
        }
        return $this->sendResponse($product, "Keresési találatok sikeresen megjelenítve");
    }
    public function searchWithFilter($component){
        $product = DB::table('products')
            ->join('components', 'products.cid', '=', 'components.id')
            ->select('name', 'price_kg', 'cid', 'production_time', 'created_at','updated_at')
            ->where('componets.component', '=', $component)
            ->get();
        if(count($product)==0){
            return $this->sendError("Nincs találat a szűrésre");
        }
        return $this->sendResponse($product, "Szűrési találatok betöltve");
    }
    
}