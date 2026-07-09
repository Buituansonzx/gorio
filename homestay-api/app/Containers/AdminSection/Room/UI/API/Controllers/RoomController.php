<?php

namespace App\Containers\AdminSection\Room\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\Host\UI\API\Transformers\RoomTransformer;
use App\Containers\AdminSection\Room\Actions\AddMediaForRoomAction;
use App\Containers\AdminSection\Room\Actions\GetListingRoomAction;
use App\Containers\AdminSection\Room\Actions\GetRoomTimePricingAction;
use App\Containers\AdminSection\Room\Actions\ImportRankingRoomAction;
use App\Containers\AdminSection\Room\Actions\SortMediaAction;
use App\Containers\AdminSection\Room\Actions\UpdateRoomAction;
use App\Containers\AdminSection\Room\Actions\UpdateRoomOrderAction;
use App\Containers\AdminSection\Room\Actions\UpdateRoomTimePricingAction;
use App\Containers\AdminSection\Room\Actions\UpdateStatusRoomAction;
use App\Containers\AdminSection\Room\UI\API\Requests\AddMediaForRoomRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\GetListingRoomRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\GetRoomTimePricingRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\ImportRankingRoomRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\SortMediasRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\UpdateRoomOrderRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\UpdateRoomRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\UpdateRoomTimePricingRequest;
use App\Containers\AdminSection\Room\UI\API\Requests\UpdateStatusRoomRequest;
use App\Containers\AdminSection\Room\UI\API\Transformers\HourlyPriceTransformer;
use App\Containers\AdminSection\Room\UI\API\Transformers\RoomComboPricingTransformer;
use App\Containers\AdminSection\Room\UI\API\Transformers\RoomFixedCheckTimeTransformer;
use App\Containers\SharedSection\Room\Models\Media;
use App\Containers\SharedSection\Room\Models\Room;
use App\Ship\Helpers\S3Helper;
use App\Ship\Parents\Controllers\ApiController;
use App\Ship\Services\ImageService;
use Illuminate\Http\Request;

final class RoomController extends ApiController
{
    public function listingRoom(GetListingRoomRequest $request, GetListingRoomAction $action)
    {

        $rooms = $action->run($request);
        return Response::create($rooms, RoomTransformer::class)->ok();
    }
    public function updateStatusRoom(UpdateStatusRoomRequest $request, UpdateStatusRoomAction $action)
    {
        $room = Room::find($request->id);
        $result = $action->run($request->id);
        return response()->json(
            [
                'is_active' => $result->is_active,
            ]
        );
    }

    public function updateRoomOrder(UpdateRoomOrderRequest $request, UpdateRoomOrderAction $action)
    {
        $rooms = $action->run($request->validated());
        return response()->json([
            'status' => 'success',
            'data' => $rooms,
            'message' => 'Room order updated successfully',
        ], 200);
    }

    public function update(UpdateRoomRequest $request, UpdateRoomAction $action)
    {
        $roomId = $request->id;
        $data = $action->run($roomId, $request->validated());
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Room updated successfully',
        ]);
    }

    public function getMediasRoom(Request $request)
    {
        $roomId = $request->id;
        $medias = Media::where('room_id', $roomId)
            ->orderBy('sort_index')
            ->get()
            ->map(function($media) {
                $media->url = app(ImageService::class)->toAdminPayload($media)['img']['src'];
                return $media;
            });
        return response()->json([
            'status' => 'success',
            'data' => $medias,
        ]);
    }

    public function sortMedias(SortMediasRequest $request, SortMediaAction $action)
    {
        $roomId = $request->id;
        $data = $action->run($roomId, $request->validated()['media']);
        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Room medias sorted successfully',
        ]);
    }

    public function addMedia(AddMediaForRoomRequest $request, AddMediaForRoomAction $action)
    {
        $medias = $action->run($request->id, $request->validated());
        return response()->json([
            'status' => 'success',
            'data' => $medias,
            'message' => 'Media added to room successfully',
        ]);
    }

    public function importRanking(ImportRankingRoomRequest $request, ImportRankingRoomAction $action)
    {
        $result = $action->run($request->file('file'));
        return response()->json([
            'status' => 'success',
            'message' => 'Import ranking thành công',
            'warnings' => $result['warnings'],
        ]);
    }

    public function getTimePricing(GetRoomTimePricingRequest $request, GetRoomTimePricingAction $action)
    {
        $data = $action->run($request->id);

        $fixedCheckTimes = Response::create($data['fixed_check_times'], RoomFixedCheckTimeTransformer::class)->ok();
        $comboPricing = Response::create($data['combo_pricing'], RoomComboPricingTransformer::class)->ok();
        $hourlyPricing = Response::create($data['hourly_pricing'], HourlyPriceTransformer::class)->ok();

        return response()->json([
            'status' => 'success',
            'data' => [
                'fixed_check_times' => $fixedCheckTimes->getData(true)['data'] ?? [],
                'combo_pricing' => $comboPricing->getData(true)['data'] ?? [],
                'hourly_pricing' => $hourlyPricing->getData(true)['data'] ?? [],
            ],
        ]);
    }

    public function updateTimePricing(UpdateRoomTimePricingRequest $request, UpdateRoomTimePricingAction $action)
    {
        $data = $action->run($request->id, $request->validated());

        $fixedCheckTimes = Response::create($data['fixed_check_times'], RoomFixedCheckTimeTransformer::class)->ok();
        $comboPricing = Response::create($data['combo_pricing'], RoomComboPricingTransformer::class)->ok();
        $hourlyPricing = Response::create($data['hourly_pricing'], HourlyPriceTransformer::class)->ok();

        return response()->json([
            'status' => 'success',
            'message' => 'Room time pricing updated successfully',
            'data' => [
                'fixed_check_times' => $fixedCheckTimes->getData(true)['data'] ?? [],
                'combo_pricing' => $comboPricing->getData(true)['data'] ?? [],
                'hourly_pricing' => $hourlyPricing->getData(true)['data'] ?? [],
            ],
        ]);
    }
}
