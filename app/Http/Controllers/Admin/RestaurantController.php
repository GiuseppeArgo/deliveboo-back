<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Restaurant;
use App\Models\Type;
use App\Models\Dish;
use App\Http\Requests\UpdateRestaurantRequest;
use App\Http\Requests\StoreRestaurantRequest;




class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::id();
        // Recupera la lista dei ristoranti
        if(Auth::user()->id != 1){
            $restaurants = Restaurant::with('types')->where('user_id',$user_id)->get();
        } else{
            $restaurants = Restaurant::with('types')->get();
        }
        // dd($restaurants);
        // Passa la lista alla vista index
        return view('admin.restaurants.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // fare controllo se l'utente ha gia un ristorante e riportarlo all index.
        $listTypes = Type::all();
        return view("admin.restaurants.create", compact("listTypes"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRestaurantRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $data['city'] = 'Milano';
        $data['image'] = Storage::put('img', $data['image']);
        $data['slug'] = Str::slug($data['name'] . '-' . $data['user_id']);
        $newRestaurant = new Restaurant();
        $newRestaurant->Fill($data);
        $newRestaurant->save();
        $newRestaurant->types()->sync($request->tipologies);

        return redirect()->route("admin.restaurants.index", ["restaurant" => $newRestaurant->slug]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {

        // fare easyloading della lista dei piatti
        return view("admin.restaurants.show", compact("restaurant"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        //  controllo utente puoi vedere e modificare solo i tuoi ristoranti
        if($restaurant->user_id !== Auth::id()){
            abort(403);
         }
        $listTypes = Type::all();
        return view('admin.restaurants.edit', compact('restaurant', 'listTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {

            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $data['slug'] = Str::slug($data['name'] . '-' . $data['user_id']);
            if (isset($data['image'])) {
                if ($restaurant->image) {
                    Storage::delete($restaurant->image);
                }
                $data['image'] = Storage::put('img', $data['image']);
            }
            $restaurant->update($data);
            $restaurant->types()->sync($request->tipologies);
            return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
