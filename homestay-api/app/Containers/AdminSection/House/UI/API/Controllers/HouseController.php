<?php

namespace App\Containers\AdminSection\House\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\House\Actions\CreateHouseAction;
use App\Containers\AdminSection\House\Actions\ListingHouseAction;
use App\Containers\AdminSection\House\Actions\StoreCheckoutInstructionAction;
use App\Containers\AdminSection\House\Actions\StoreSpecialOfferAction;
use App\Containers\AdminSection\House\Actions\UpdateHouseAction;
use App\Containers\AdminSection\House\UI\API\Requests\CreateHouseRequest;
use App\Containers\AdminSection\House\UI\API\Requests\ListingHouseRequest;
use App\Containers\AdminSection\House\UI\API\Requests\StoreSpecialOfferRequest;
use App\Containers\AdminSection\House\UI\API\Requests\UpdateHouseRequest;
use App\Containers\AdminSection\House\UI\API\Transformers\HouseTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;

final class HouseController extends ApiController
{
    public function listing(ListingHouseRequest $request, ListingHouseAction $action)
    {
        $houses = $action->run($request->validated());
        return Response::create($houses, HouseTransformer::class)->ok();
    }

    public function update(UpdateHouseRequest $request, UpdateHouseAction $action)
    {
        $house = $action->run($request->validated(), $request->id);
        return Response::create($house, HouseTransformer::class);
    }

    public function create(CreateHouseRequest $request, CreateHouseAction $action)
    {
        $house = $action->run($request->validated());
        return Response::create($house, HouseTransformer::class);
    }

    public function storeSpecialOffer(StoreSpecialOfferRequest $request, StoreSpecialOfferAction $action)
    {
        $house = $action->run($request->validated(), $request->id);
        return response()->json([
            'message' => 'Special offer stored successfully',
        ], 200);
    }

    public function storeCheckoutInstruction(Request $request, StoreCheckoutInstructionAction $action)
    {
        $house = $action->run($request->id);
        return response()->json([
            'message' => 'Checkout instruction stored successfully',
        ], 200);
    }
}
