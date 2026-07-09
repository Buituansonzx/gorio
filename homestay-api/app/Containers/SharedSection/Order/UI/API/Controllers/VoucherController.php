<?php

namespace App\Containers\SharedSection\Order\UI\API\Controllers;

use Apiato\Http\Response;
use App\Containers\AdminSection\Voucher\Actions\CreateVoucherAction;
use App\Containers\AdminSection\Voucher\Actions\UpdateVoucherAction;
use App\Containers\AdminSection\Voucher\UI\API\Requests\CreateVoucherRequest;
use App\Containers\AdminSection\Voucher\UI\API\Requests\UpdateVoucherRequest;
use App\Containers\SharedSection\Order\Actions\CheckVoucherAction;
use App\Containers\SharedSection\Order\Actions\FindVoucherByIDAction;
use App\Containers\SharedSection\Order\Actions\ListVoucherAvailableAction;
use App\Containers\SharedSection\Order\UI\API\Requests\CheckVoucherRequest;
use App\Containers\SharedSection\Order\UI\API\Requests\FindVoucherByIDRequest;
use App\Containers\SharedSection\Order\UI\API\Requests\listVoucherAvailableRequest;
use App\Containers\SharedSection\Order\UI\API\Transformers\ListVoucherTransformer;
use App\Containers\SharedSection\Order\UI\API\Transformers\VoucherTransformer;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

final class VoucherController extends ApiController
{
    public function listVoucherAvailable(listVoucherAvailableRequest $request, ListVoucherAvailableAction $action)
    {
        $userId = Auth::id();
        $data = $action->run($request,$userId);
        return Response::create($data, ListVoucherTransformer::class);
    }

    public function checkVoucherAvailable(CheckVoucherRequest $request, CheckVoucherAction $action)
    {
        $code = $request->code;
        $data = $action->run($code);
        return Response::create($data, VoucherTransformer::class);
    }



    public function find(FindVoucherByIDRequest $request,FindVoucherByIDAction $action)
    {
        $data = $action->run($request->id);
        return Response::create($data, VoucherTransformer::class);
    }
}
