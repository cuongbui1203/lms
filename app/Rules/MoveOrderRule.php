<?php

namespace App\Rules;

use App\Models\Order;
use App\Models\WorkPlate;
use Cache;
use DB;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Collection;

class MoveOrderRule implements Rule
{
    protected array $errors;
    protected Collection $allOrderIds;
    protected Collection $allWardIds;
    protected Collection $allWpIds;

    public function __construct()
    {
        $this->errors = [];
        $this->allOrderIds = Cache::remember('order_ids', now()->addMinutes(5), function () {
            return collect(Order::all('id'));
        });
        $this->allWpIds = Cache::remember('wp_ids', now()->addMinutes(5), function () {
            return collect(WorkPlate::all('id'));
        });
        $this->allWardIds = Cache::remember('ward_ids', now()->addMinutes(100), function () {
            return DB::connection('sqlite_vn_map')
                ->table('wards')
                ->get('*');
        });
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!json_validate($value)) {
            $this->errors[] = 'must be valid json string';

            return false;
        }

        $data = json_decode($value);
        if (!is_array($data)) {
            $this->errors[] = 'must be array';

            return false;
        }

        $data = collect($data);
        $data->map(function ($e, $i) {
            $attribute = 'request-no-' . ($i + 1);
            $errors = $this->check($e);
            if (count($errors) !== 0) {
                $this->errors[$attribute] = $errors;
            }
        });

        return count($this->errors) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errors;
    }

    private function check($order)
    {
        $errors = [];

        $skipFromId = property_exists($order, 'from_id') ? $order->from_id === null : true;
        $skipFromAddressId = property_exists($order, 'from_address_id') ? $order->from_address_id === null : true;

        $skipToId = property_exists($order, 'to_id') ? $order->to_id === null : true;
        $skipToAddressId = property_exists($order, 'to_address_id') ? $order->to_address_id === null : true;

        if (!$this->checkValid($order, $errors, $skipFromId, $skipFromAddressId, $skipToId, $skipToAddressId)) {
            return $errors;
        }

        $this->checkOrderIdValid($order, $errors);

        $this->checkHasTo($errors, $skipToId, $skipToAddressId);

        $this->checkHasFrom($errors, $skipFromId, $skipFromAddressId);

        $this->checkFromValid($order, $errors, $skipFromId, $skipFromAddressId);

        $this->checkToValid($order, $errors, $skipToId, $skipToAddressId);

        return $errors;
    }

    private function checkValid($order, &$errors, $skipFromId, $skipFromAddressId, $skipToId, $skipToAddressId): bool
    {
        if (
            ($skipToId && $skipToAddressId && $skipFromId && $skipFromAddressId) ||
            !property_exists($order, 'orderId')
        ) {
            $errors['request'] = 'request invalid';

            return false;
        }

        return true;
    }

    private function checkOrderIdValid($order, &$errors)
    {
        if (!$this->allOrderIds->contains('id', '=', $order->orderId)) {
            $errors['orderId'] = 'OrderId invalid';
        }
    }

    private function checkHasFrom(&$errors, $skipFromId, $skipFromAddressId)
    {
        if ($skipFromId && $skipFromAddressId) {
            $errors['from'] = 'must has one of from id or from address id';
        }
    }

    private function checkFromValid($order, &$errors, $skipFromId, $skipFromAddressId)
    {
        if (!$skipFromId && $this->allWpIds->where('id', '=', $order->from_id)->count() === 0) {
            $errors['from_id'] = 'from id invalid';
        }

        if (!$skipFromAddressId) {
            if (!is_string($order->from_address_id)) {
                $errors['from_address_id'] = 'must be string';
            } else if ($this->allWardIds->where('code', '=', $order->from_address_id)->count() === 0) {
                $errors['from_address_id'] = 'from address id invalid.';
            }
        }
    }

    private function checkHasTo(&$errors, $skipToId, $skipToAddressId)
    {
        if ($skipToId && $skipToAddressId) {
            $errors['to'] = 'must has one of to id or to address id';
        }
    }

    private function checkToValid($order, &$errors, $skipToId, $skipToAddressId)
    {
        if (!$skipToId && $this->allWpIds->where('id', '=', $order->to_id)->count() === 0) {
            $errors['to_id'] = 'to id invalid';
        }

        if (!$skipToAddressId) {
            if (!is_string($order->to_address_id)) {
                $errors['to_address_id'] = 'must be string';
            } else if ($this->allWardIds->where('code', '=', $order->to_address_id)->count() === 0) {
                $errors['to_address_id'] = 'to address id invalid.';
            }
        }
    }
}
